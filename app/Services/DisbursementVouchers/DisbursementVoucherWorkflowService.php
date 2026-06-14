<?php

namespace App\Services\DisbursementVouchers;

use App\Models\CategoryItemBudget;
use App\Models\DisbursementVoucher;
use App\Models\DisbursementVoucherStep;
use App\Models\Mop;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DisbursementVoucherWorkflowService
{
    public function quickCreate(array $data): DisbursementVoucher
    {
        return DB::transaction(function () use ($data) {
            $voucher = DisbursementVoucher::create([
                'voucher_subtype_id' => $data['voucher_subtype_id'],
                'user_id' => $data['requisitioner_id'],
                'signatory_id' => $data['signatory_id'],
                'mop_id' => $data['mop_id'] ?? null,
                'payee' => $data['payee'],
                'responsibility_center' => $data['responsibility_center'] ?? null,
                'tracking_number' => DisbursementVoucher::generateTrackingNumber(),
                'submitted_at' => now(),
                'other_details' => [
                    'activity_date_from' => $data['activity_date_from'] ?? null,
                    'activity_date_to' => $data['activity_date_to'] ?? null,
                ],
                'current_step_id' => 3000,
                'previous_step_id' => 2000,
            ]);

            foreach ($data['particulars'] as $particular) {
                $voucher->disbursement_voucher_particulars()->create([
                    'purpose' => $particular['purpose'],
                    'mfo_pap' => $particular['mfo_pap'] ?? '',
                    'amount' => $particular['amount'],
                ]);
            }

            $voucher->refresh();
            $voucher->activity_logs()->create([
                'description' => $voucher->current_step->process.' '.$voucher->signatory->employee_information->full_name.' '.$voucher->current_step->sender,
            ]);

            return $voucher;
        });
    }

    public function receive(DisbursementVoucher $voucher, User $actor, array $options = []): DisbursementVoucher
    {
        $voucher->refresh();
        if ($voucher->current_step->process !== 'Forwarded to' || $voucher->for_cancellation || filled($voucher->pending_return_step_id)) {
            throw ValidationException::withMessages([
                'voucher' => 'This disbursement voucher cannot be received from its current state.',
            ]);
        }

        return DB::transaction(function () use ($voucher, $actor, $options) {
            $voucher->update([
                'current_step_id' => $voucher->current_step->next_step->id,
            ]);
            $voucher->refresh();
            $description = ($options['include_recipient'] ?? true)
                ? $voucher->current_step->process.' '.$voucher->current_step->recipient.' by '.$this->actorName($actor, $options)
                : $voucher->current_step->process.' '.$this->actorName($actor, $options);

            $voucher->activity_logs()->create([
                'description' => $description,
            ]);

            if (in_array($voucher->current_step_id, [8000, 11000])) {
                $voucher->update([
                    'current_step_id' => $voucher->current_step_id + 1000,
                ]);
                $voucher->refresh();
                $voucher->activity_logs()->create([
                    'description' => $voucher->current_step->process,
                ]);
            }

            return $voucher->refresh();
        });
    }

    public function forward(DisbursementVoucher $voucher, User $actor, ?string $remarks = null, array $options = []): DisbursementVoucher
    {
        $voucher->refresh();
        if (! $this->canBeForwarded($voucher)) {
            throw ValidationException::withMessages([
                'voucher' => 'This disbursement voucher cannot be forwarded yet.',
            ]);
        }

        return DB::transaction(function () use ($voucher, $actor, $remarks, $options) {
            if ($voucher->current_step_id >= ($voucher->previous_step_id ?? 0)) {
                $voucher->update([
                    'current_step_id' => $voucher->current_step->next_step->id,
                ]);

                if (($options['approve_actual_itinerary'] ?? false) && $voucher->travel_order_id && in_array($voucher->voucher_subtype_id, [6, 7])) {
                    $actualItinerary = $voucher->travel_order?->itineraries()->whereIsActual(true)->first();
                    if (! $actualItinerary) {
                        throw ValidationException::withMessages([
                            'voucher' => 'Actual itinerary not found.',
                        ]);
                    }

                    $actualItinerary->update([
                        'approved_at' => now(),
                    ]);
                }
            } else {
                $voucher->update([
                    'current_step_id' => $voucher->previous_step_id,
                ]);
            }

            $voucher->refresh();
            $description = $voucher->current_step_id == 13000
                ? $voucher->current_step->process.' '.$voucher->current_step->recipient
                : $voucher->current_step->process.' '.$voucher->current_step->recipient.' by '.$this->actorName($actor, $options);

            $voucher->activity_logs()->create([
                'description' => $description,
                'remarks' => $remarks,
            ]);

            return $voucher->refresh();
        });
    }

    public function returnToStep(DisbursementVoucher $voucher, int $returnStepId, ?string $remarks = null, array $options = []): DisbursementVoucher
    {
        $voucher->refresh();
        if ($voucher->for_cancellation || filled($voucher->pending_return_step_id)) {
            throw ValidationException::withMessages([
                'voucher' => 'This disbursement voucher cannot be returned from its current state.',
            ]);
        }

        $destinationStep = DisbursementVoucherStep::where('process', 'Forwarded to')
            ->where('id', '<', $voucher->current_step_id)
            ->find($returnStepId);

        if (! $destinationStep) {
            throw ValidationException::withMessages([
                'return_step_id' => 'Select a valid previous return destination.',
            ]);
        }

        return DB::transaction(function () use ($voucher, $destinationStep, $remarks, $options) {
            $voucher->update([
                'pending_return_step_id' => $destinationStep->id,
            ]);

            $description = 'DV marked for return to '.($destinationStep->recipient ?? 'Unknown').'. Awaiting physical release.';
            if ($options['is_oic'] ?? false) {
                $description .= "\nOIC: ".$this->baseActorName($options['actor'] ?? null).'.';
            }

            $voucher->activity_logs()->create([
                'description' => $description,
                'remarks' => $remarks,
            ]);

            return $voucher->refresh();
        });
    }

    public function releaseReturn(DisbursementVoucher $voucher, User $actor, string $logNumber, ?string $note = null, array $options = []): DisbursementVoucher
    {
        $voucher->refresh();
        if (blank($voucher->pending_return_step_id)) {
            throw ValidationException::withMessages([
                'voucher' => 'This disbursement voucher has no pending return to release.',
            ]);
        }

        return DB::transaction(function () use ($voucher, $actor, $logNumber, $note, $options) {
            $destinationStepId = $voucher->pending_return_step_id;

            $previousStepId = $voucher->current_step_id < ($voucher->previous_step_id ?? 0)
                ? $voucher->previous_step_id
                : DisbursementVoucherStep::where('process', 'Forwarded to')
                    ->where('id', '<', $voucher->current_step_id)
                    ->latest('id')
                    ->first()
                    ->id;

            $voucher->update([
                'current_step_id' => $destinationStepId,
                'previous_step_id' => $previousStepId,
                'pending_return_step_id' => null,
            ]);
            $voucher->refresh();

            $description = 'DV released to '.$voucher->current_step->recipient.'. Log #: '.$logNumber.' by '.$this->actorName($actor, $options);
            if (filled($note)) {
                $description .= "\nNote: ".$note;
            }

            $voucher->activity_logs()->create([
                'description' => $description,
            ]);

            return $voucher->refresh();
        });
    }

    public function verifyRelatedDocuments(DisbursementVoucher $voucher, array $data, array $options = []): DisbursementVoucher
    {
        $voucher->refresh();
        if ($voucher->current_step_id != 6000 || $voucher->for_cancellation || filled($voucher->pending_return_step_id)) {
            throw ValidationException::withMessages([
                'voucher' => 'Related documents can only be verified during pre-audit receiving.',
            ]);
        }

        return DB::transaction(function () use ($voucher, $data, $options) {
            $voucher->update([
                'log_number' => $data['log_number'] ?? null,
                'documents_verified_at' => now(),
                'related_documents' => [
                    'items' => collect($data['items'] ?? [])->map(fn ($item) => [
                        'document' => $item['document'] ?? '',
                        'status' => $item['status'] ?? 'required',
                        'remarks' => $item['remarks'] ?? null,
                    ])->values()->all(),
                    'remarks' => $data['remarks'] ?? '',
                ],
            ]);

            $description = 'Related documents have been verified.';
            if ($options['is_oic'] ?? false) {
                $description .= "\nOIC: ".$this->baseActorName($options['actor'] ?? null).'.';
            }

            $voucher->activity_logs()->create([
                'description' => $description,
            ]);

            return $voucher->refresh();
        });
    }

    public function assignOrsBurs(DisbursementVoucher $voucher, array $data, array $options = []): DisbursementVoucher
    {
        $voucher->refresh();
        if ($voucher->current_step_id != 9000 || $voucher->for_cancellation || filled($voucher->pending_return_step_id)) {
            throw ValidationException::withMessages([
                'voucher' => 'ORS/BURS can only be assigned at the Budget Office preparation step.',
            ]);
        }

        $allocations = collect($data['uacs_allocations'] ?? [])
            ->map(fn ($allocation) => [
                'category_item_budget_id' => $allocation['category_item_budget_id'] ?? null,
                'amount' => $allocation['amount'] ?? null,
            ])
            ->filter(fn ($allocation) => filled($allocation['category_item_budget_id']) && filled($allocation['amount']))
            ->values();

        $this->validateUacsAllocations($voucher, $allocations);

        return DB::transaction(function () use ($voucher, $data, $allocations, $options) {
            $voucher->update([
                'ors_burs' => $data['ors_burs'],
                'responsibility_center' => $data['responsibility_center'],
                'fund_cluster_id' => $data['fund_cluster_id'],
            ]);

            $voucher->uacs_allocations()->delete();
            $voucher->uacs_allocations()->createMany($allocations->map(fn ($allocation) => [
                'category_item_budget_id' => $allocation['category_item_budget_id'],
                'amount' => $allocation['amount'],
            ])->all());

            $description = 'ORS/BURS, Fund Cluster, and UACS allocations assigned to Disbursement Voucher.';
            if ($options['is_oic'] ?? false) {
                $description .= "\nOIC: ".$this->baseActorName($options['actor'] ?? null).'.';
            }

            $voucher->activity_logs()->create([
                'description' => $description,
            ]);

            return $voucher->refresh();
        });
    }

    public function recordAccounting(DisbursementVoucher $voucher, string $dvNumber, string $journalDate, array $options = []): DisbursementVoucher
    {
        $voucher->refresh();
        if ($voucher->current_step_id != 12000 || filled($voucher->journal_date) || filled($voucher->dv_number) || $voucher->for_cancellation || filled($voucher->pending_return_step_id)) {
            throw ValidationException::withMessages([
                'voucher' => 'Accounting details can only be recorded at the accounting verification step.',
            ]);
        }

        return DB::transaction(function () use ($voucher, $dvNumber, $journalDate, $options) {
            $voucher->update([
                'dv_number' => $dvNumber,
                'journal_date' => $journalDate,
            ]);

            $description = 'Disbursement Voucher verified.';
            if ($options['is_oic'] ?? false) {
                $description .= "\nOIC: ".$this->baseActorName($options['actor'] ?? null).'.';
            }

            $voucher->activity_logs()->create([
                'description' => $description,
            ]);

            return $voucher->refresh();
        });
    }

    public function certify(DisbursementVoucher $voucher, array $options = []): DisbursementVoucher
    {
        $voucher->refresh();
        if ($voucher->current_step_id != 13000 || $voucher->for_cancellation || $voucher->certified_by_accountant || filled($voucher->pending_return_step_id)) {
            throw ValidationException::withMessages([
                'voucher' => 'This disbursement voucher cannot be certified from its current state.',
            ]);
        }

        return DB::transaction(function () use ($voucher, $options) {
            $voucher->update([
                'certified_by_accountant' => true,
            ]);

            $description = 'Disbursement voucher certified.';
            if ($options['is_oic'] ?? false) {
                $description .= "\nOIC: ".$this->baseActorName($options['actor'] ?? null).'.';
            }

            $voucher->activity_logs()->create([
                'description' => $description,
            ]);

            return $voucher->refresh();
        });
    }

    public function makeChequeAda(DisbursementVoucher $voucher, int $mopId, string $chequeNumber, array $options = []): DisbursementVoucher
    {
        $voucher->refresh();
        if ($voucher->current_step_id != 17000 || filled($voucher->cheque_number) || $voucher->for_cancellation || filled($voucher->pending_return_step_id)) {
            throw ValidationException::withMessages([
                'voucher' => 'Cheque/ADA can only be recorded at the Cashier receiving step.',
            ]);
        }

        if (! Mop::find($mopId)) {
            throw ValidationException::withMessages([
                'mop_id' => 'Select a valid mode of payment.',
            ]);
        }

        return DB::transaction(function () use ($voucher, $mopId, $chequeNumber, $options) {
            $voucher->update([
                'mop_id' => $mopId,
                'cheque_number' => $chequeNumber,
                'current_step_id' => $voucher->current_step_id + 1000,
                'cheque_number_added_at' => now(),
            ]);

            $description = 'Cheque/ADA made for requisitioner.';
            if ($options['is_oic'] ?? false) {
                $description .= "\nOIC: ".$this->baseActorName($options['actor'] ?? null).'.';
            }

            $voucher->activity_logs()->create([
                'description' => $description,
            ]);

            $endDate = $this->voucherEndDate($voucher);
            $liquidationPeriodEndDate = $this->liquidationPeriodEndDate($voucher, $endDate);

            if (! $voucher->cash_advance_reminder()->exists()) {
                $voucher->cash_advance_reminder()->create([
                    'status' => 'On-Going',
                    'voucher_end_date' => $endDate,
                    'liquidation_period_end_date' => $liquidationPeriodEndDate,
                    'step' => 1,
                    'is_sent' => false,
                    'title' => 'Send FMR',
                    'message' => 'Ongoing liquidation of cash advance.',
                    'user_id' => $voucher->user_id,
                ]);
            }

            return $voucher->refresh();
        });
    }

    public function canBeForwarded(DisbursementVoucher $voucher): bool
    {
        $voucher->loadMissing(['current_step', 'voucher_subtype.related_documents_list']);

        if (filled($voucher->pending_return_step_id) || $voucher->for_cancellation) {
            return false;
        }

        return ($voucher->current_step->process == 'Received in' && ! in_array($voucher->current_step_id, [6000, 9000, 13000, 17000]))
            || in_array($voucher->current_step_id, [2000, 4000])
            || ($voucher->current_step_id == 9000 && filled($voucher->ors_burs) && filled($voucher->fund_cluster_id) && $voucher->hasValidUacsAllocations())
            || ($voucher->current_step_id == 12000 && filled($voucher->journal_date) && filled($voucher->dv_number))
            || ($voucher->current_step_id == 13000 && $voucher->certified_by_accountant)
            || ($voucher->current_step_id == 18000 && filled($voucher->cheque_number))
            || ($voucher->current_step_id == 6000 && (! $voucher->voucher_subtype?->related_documents_list || $voucher->hasCompletedRelatedDocumentsVerification()));
    }

    public function returnStepOptions(DisbursementVoucher $voucher): array
    {
        return DisbursementVoucherStep::where('process', 'Forwarded to')
            ->where('recipient', '!=', $voucher->current_step?->recipient)
            ->where('id', '<', $voucher->current_step_id)
            ->pluck('recipient', 'id')
            ->all();
    }

    public function defaultUacsAllocations(DisbursementVoucher $voucher): array
    {
        $voucher->loadMissing(['uacs_allocations', 'disbursement_voucher_particulars']);

        if ($voucher->uacs_allocations->isNotEmpty()) {
            return $voucher->uacs_allocations->map(fn ($allocation) => [
                'category_item_budget_id' => $allocation->category_item_budget_id,
                'amount' => $allocation->amount,
            ])->values()->all();
        }

        return [[
            'category_item_budget_id' => CategoryItemBudget::query()->value('id'),
            'amount' => $voucher->totalSumDisbursementVoucherParticular() ?: 1000,
        ]];
    }

    private function validateUacsAllocations(DisbursementVoucher $voucher, $allocations): void
    {
        if ($allocations->isEmpty()) {
            throw ValidationException::withMessages([
                'uacs_allocations' => 'At least one UACS allocation is required.',
            ]);
        }

        if ($allocations->pluck('category_item_budget_id')->duplicates()->isNotEmpty()) {
            throw ValidationException::withMessages([
                'uacs_allocations' => 'Each UACS code can only be selected once.',
            ]);
        }

        $allocationTotal = $allocations->sum(fn ($allocation) => (float) $allocation['amount']);
        $voucherTotal = $voucher->totalSumDisbursementVoucherParticular();

        if ($this->amountToCents($allocationTotal) !== $this->amountToCents($voucherTotal)) {
            throw ValidationException::withMessages([
                'uacs_allocations' => 'The total UACS allocation must equal the DV amount of PHP '.number_format($voucherTotal, 2).'.',
            ]);
        }
    }

    private function amountToCents($amount): int
    {
        return (int) round((float) $amount * 100);
    }

    private function actorName(User $actor, array $options = []): string
    {
        $name = $this->baseActorName($actor);

        return ($options['is_oic'] ?? false) ? 'OIC: '.$name.'.' : $name;
    }

    private function baseActorName(?User $actor): string
    {
        if (! $actor) {
            return 'Super Admin';
        }

        return $actor->employee_information->full_name ?? $actor->name ?? 'Super Admin';
    }

    private function voucherEndDate(DisbursementVoucher $voucher): ?string
    {
        return $voucher->travel_order()->exists()
            ? $voucher->travel_order->date_to
            : ($voucher->other_details['activity_date_to'] ?? null);
    }

    private function liquidationPeriodEndDate(DisbursementVoucher $voucher, ?string $endDate): ?string
    {
        if (! $endDate) {
            return null;
        }

        $days = match ((int) $voucher->voucher_subtype_id) {
            1 => 30,
            2 => 60,
            3 => 20,
            4, 5 => 5,
            default => null,
        };

        return $days ? Carbon::parse($endDate)->addDays($days)->format('Y-m-d') : null;
    }
}
