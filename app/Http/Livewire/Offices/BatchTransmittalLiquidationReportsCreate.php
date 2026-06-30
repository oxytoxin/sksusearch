<?php

namespace App\Http\Livewire\Offices;

use App\Models\BatchTransmittal;
use App\Models\LiquidationReport;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BatchTransmittalLiquidationReportsCreate extends Component
{
    public $selectedLrs = [];
    public $lrRemarks = [];
    public $scanInput = '';
    public $officeGroupId;
    public $officeName;
    public $destination = '';
    public $availableDestinations = [];

    public function mount()
    {
        $office = auth()->user()->employee_information?->office;
        if (! in_array($office?->office_group_id, [2])) {
            abort(403);
        }

        $this->officeGroupId = $office->office_group_id;
        $this->officeName = $office->name;

        $this->loadAvailableDestinations();

        if (count($this->availableDestinations) === 1) {
            $this->destination = array_key_first($this->availableDestinations);
        }
    }

    /**
     * Certified liquidation reports resting in this office that are ready to be forwarded.
     */
    protected function baseQuery()
    {
        return LiquidationReport::with(['current_step', 'requisitioner.employee_information', 'disbursement_voucher'])
            ->whereRelation('current_step', 'office_group_id', $this->officeGroupId)
            ->where('certified_by_accountant', true)
            ->where('for_cancellation', false)
            ->whereNull('pending_return_step_id')
            ->latest('report_date')
            ->get()
            ->filter(fn ($lr) => $this->isForwardable($lr));
    }

    protected function isForwardable(LiquidationReport $lr): bool
    {
        return $lr->current_step
            && $lr->current_step->process !== 'Forwarded to'
            && $lr->current_step->next_step;
    }

    public function loadAvailableDestinations()
    {
        $destinations = [];
        foreach ($this->baseQuery() as $lr) {
            $recipient = $lr->current_step->next_step?->recipient;
            if ($recipient) {
                $destinations[$recipient] = $recipient;
            }
        }

        $this->availableDestinations = $destinations;
    }

    public function getForwardableLrsProperty()
    {
        return $this->baseQuery()
            ->filter(function ($lr) {
                if ($this->destination) {
                    return $lr->current_step->next_step?->recipient === $this->destination;
                }

                return true;
            })
            ->values();
    }

    public function updatedDestination()
    {
        $this->selectedLrs = [];
        $this->lrRemarks = [];
    }

    public function toggleLr($lrId)
    {
        if (in_array($lrId, $this->selectedLrs)) {
            $this->selectedLrs = array_values(array_diff($this->selectedLrs, [$lrId]));
        } else {
            $this->selectedLrs[] = $lrId;
        }
    }

    public function selectAll()
    {
        $this->selectedLrs = $this->forwardableLrs->pluck('id')->toArray();
    }

    public function deselectAll()
    {
        $this->selectedLrs = [];
    }

    public function updatedScanInput($value)
    {
        if (empty($value)) {
            return;
        }

        // QR codes may embed a URL — fall back to the last path segment as the tracking number.
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            $value = trim(parse_url($value, PHP_URL_PATH) ?? '', '/');
            $value = substr(strrchr('/'.$value, '/'), 1);
        }

        $lr = LiquidationReport::with(['current_step'])
            ->where('tracking_number', $value)
            ->whereRelation('current_step', 'office_group_id', $this->officeGroupId)
            ->first();

        if ($lr && ! in_array($lr->id, $this->selectedLrs)) {
            if ($lr->certified_by_accountant && ! $lr->for_cancellation && blank($lr->pending_return_step_id) && $this->isForwardable($lr)) {
                $recipient = $lr->current_step->next_step?->recipient;
                if (! $this->destination) {
                    $this->destination = $recipient;
                }
                if ($recipient === $this->destination) {
                    $this->selectedLrs[] = $lr->id;
                    Notification::make()->title("Added: {$lr->tracking_number}")->success()->send();
                } else {
                    Notification::make()->title("LR destination ({$recipient}) does not match selected destination ({$this->destination}).")->warning()->send();
                }
            } else {
                Notification::make()->title('LR is not a certified report ready to forward.')->warning()->send();
            }
        } elseif ($lr && in_array($lr->id, $this->selectedLrs)) {
            Notification::make()->title('LR already in batch.')->warning()->send();
        } else {
            Notification::make()->title('Certified LR not found in this office.')->warning()->send();
        }

        $this->scanInput = '';
    }

    public function createAndForward()
    {
        if (empty($this->selectedLrs)) {
            Notification::make()->title('Please select at least one Liquidation Report.')->warning()->send();

            return;
        }

        if (empty($this->destination)) {
            Notification::make()->title('Please select a destination.')->warning()->send();

            return;
        }

        $lrs = LiquidationReport::with(['current_step'])
            ->whereIn('id', $this->selectedLrs)
            ->get();

        DB::transaction(function () use ($lrs) {
            $serial = BatchTransmittal::generateSerialNumber($this->officeGroupId);

            $batch = BatchTransmittal::create([
                'office_group_id' => $this->officeGroupId,
                'document_type' => 'liquidation_report',
                'serial_number' => $serial,
                'from_office_name' => $this->officeName,
                'to_office_name' => $this->destination,
                'created_by' => auth()->id(),
                'forwarded_by' => auth()->id(),
                'forwarded_at' => now(),
            ]);

            foreach ($lrs as $lr) {
                $batch->items()->create([
                    'liquidation_report_id' => $lr->id,
                    'remarks' => $this->lrRemarks[$lr->id] ?? null,
                ]);

                if ($lr->certified_by_accountant && ! $lr->for_cancellation && blank($lr->pending_return_step_id) && $this->isForwardable($lr)) {
                    $lr->update([
                        'current_step_id' => $lr->current_step->next_step->id,
                    ]);
                    $lr->refresh();
                    $lr->activity_logs()->create([
                        'description' => $lr->current_step->process.' '.$lr->current_step->recipient.' by '.auth()->user()->employee_information->full_name,
                        'remarks' => 'Batch Transmittal No. '.$serial.($this->lrRemarks[$lr->id] ?? '' ? ' — '.$this->lrRemarks[$lr->id] : ''),
                    ]);
                }
            }

            Notification::make()
                ->title("Batch Transmittal No. {$serial} created and forwarded with {$lrs->count()} Liquidation Report(s).")
                ->success()
                ->send();

            $this->redirect(route('office.batch-transmittal.print', $batch));
        });
    }

    public function render()
    {
        return view('livewire.offices.batch-transmittal-liquidation-reports-create');
    }
}
