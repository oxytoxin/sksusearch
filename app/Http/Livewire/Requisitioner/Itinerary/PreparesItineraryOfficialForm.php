<?php

namespace App\Http\Livewire\Requisitioner\Itinerary;

use App\Models\Itinerary;
use App\Models\TravelOrder;
use App\Models\User;
use Carbon\Carbon;

trait PreparesItineraryOfficialForm
{
    protected function itineraryFormData(): array
    {
        return $this->prepareItineraryOfficialForm(
            $this->itinerary,
            $this->travel_order ?? $this->itinerary->travel_order,
            $this->itinerary->itinerary_entries,
            $this->coverage ?? $this->itinerary->coverage ?? [],
        );
    }

    protected function prepareItineraryOfficialForm(
        Itinerary $itinerary,
        ?TravelOrder $travelOrder = null,
        $itineraryEntries = null,
        $coverage = null,
    ): array {
        $travelOrder ??= $itinerary->travel_order;
        $coverageRows = collect($coverage ?? $itinerary->coverage ?? []);
        $entries = collect($itineraryEntries ?? $itinerary->itinerary_entries ?? []);
        $entries->each->loadMissing('mot');
        $entriesByDate = $entries->groupBy(fn ($entry) => $entry->date?->format('Y-m-d'));
        $signatories = $travelOrder->signatories ?? collect();
        $preparedBy = $itinerary->user ?? $itinerary->user()->with([
            'employee_information.position',
            'employee_information.office',
            'signature',
        ])->first() ?? auth()->user();
        $itinerarySignatories = $this->prepareItinerarySignatories($signatories);
        $certifyingSignatory = $itinerarySignatories->first() ?? [
            'heading' => 'Certified by:',
            'designation' => '',
            'name' => '',
            'signature' => null,
        ];
        $approvingSignatories = $itinerarySignatories->count() > 1
            ? collect([$itinerarySignatories->last()])
            : collect();
        $rows = [];
        $perDiemTotal = 0;
        $transportationTotal = 0;
        $otherTotal = 0;

        if ($travelOrder->has_registration) {
            $registrationAmount = $travelOrder->registration_amount ?? 0;
            $otherTotal += $registrationAmount;
            $rows[] = [
                'date' => '',
                'place' => 'Registration Amount',
                'departure' => '-',
                'arrival' => '-',
                'means' => '-',
                'transportation' => '-',
                'per_diem' => '-',
                'others' => $this->formatItineraryAmount($registrationAmount),
                'total' => $this->formatItineraryAmount($registrationAmount),
            ];
        }

        foreach ($coverageRows as $covered) {
            $coveredDate = $covered['date'] ?? null;
            $coveredDateKey = $coveredDate ? Carbon::parse($coveredDate)->format('Y-m-d') : null;
            $perDiem = $covered['per_diem'] ?? 0;
            $perDiemTotal += $perDiem;

            $rows[] = [
                'date' => $coveredDateKey ? Carbon::parse($coveredDateKey)->format('M d, Y') : '',
                'place' => '-',
                'departure' => '-',
                'arrival' => '-',
                'means' => '-',
                'transportation' => '-',
                'per_diem' => $this->formatItineraryAmount($perDiem),
                'others' => '-',
                'total' => $this->formatItineraryAmount($perDiem),
            ];

            foreach ($entriesByDate->get($coveredDateKey, collect()) as $entry) {
                $transportation = $entry->transportation_expenses ?? 0;
                $others = $entry->other_expenses ?? 0;
                $rowTotal = $transportation + $others;
                $transportationTotal += $transportation;
                $otherTotal += $others;

                $rows[] = [
                    'date' => $entry->date?->format('M d, Y'),
                    'place' => $entry->place,
                    'departure' => $entry->departure_time?->format('g:i A'),
                    'arrival' => $entry->arrival_time?->format('g:i A'),
                    'means' => $entry->mot?->name,
                    'transportation' => $this->formatItineraryAmount($transportation),
                    'per_diem' => '-',
                    'others' => $this->formatItineraryAmount($others),
                    'total' => $this->formatItineraryAmount($rowTotal),
                ];
            }
        }

        return [
            'tracking_code' => $travelOrder->tracking_code,
            'fund_cluster' => $travelOrder->disbursement_vouchers?->first()?->fund_cluster?->name,
            'date_of_travel' => $travelOrder->date_from?->format('M d, Y').' to '.$travelOrder->date_to?->format('M d, Y'),
            'purpose' => filled($itinerary->purpose) ? $itinerary->purpose : $travelOrder->purpose,
            'traveler' => [
                'name' => $preparedBy->employee_information?->full_name ?? $preparedBy->name,
                'position' => $preparedBy->employee_information?->position?->description,
                'station' => $preparedBy->employee_information?->office?->name,
                'signature' => $preparedBy->signature?->content,
            ],
            'signatures' => [
                'certifying' => $certifyingSignatory,
                'approving' => $approvingSignatories,
                'right_rowspan' => $approvingSignatories->count() + 1,
            ],
            'rows' => $rows,
            'blank_rows' => max(0, 22 - count($rows)),
            'totals' => [
                'transportation' => $this->formatItineraryAmount($transportationTotal),
                'per_diem' => $this->formatItineraryAmount($perDiemTotal),
                'others' => $this->formatItineraryAmount($otherTotal),
                'grand' => $this->formatItineraryAmount($perDiemTotal + $transportationTotal + $otherTotal),
            ],
        ];
    }

    protected function itineraryTotalAmount(): string
    {
        $totalAmount = $this->travel_order->registration_amount ?? 0;

        foreach ($this->coverage ?? [] as $covered) {
            $totalAmount += $covered['total_expenses'] ?? 0;
        }

        return number_format($totalAmount, 2);
    }

    protected function formatItineraryAmount($amount): string
    {
        return $amount ? number_format($amount, 2) : '-';
    }

    protected function prepareItinerarySignatories($signatories)
    {
        $oicIds = collect($signatories)
            ->map(fn ($signatory) => $signatory->pivot?->approved_by_oic_id)
            ->filter()
            ->unique()
            ->all();
        $oicUsers = $oicIds
            ? User::with(['signature', 'employee_information.position', 'employee_information.office'])->findMany($oicIds)->keyBy('id')
            : collect();

        return collect($signatories)
            ->sortBy(fn ($signatory) => $signatory->pivot?->id ?? 0)
            ->values()
            ->map(function ($signatory, int $index) use ($oicUsers) {
                $oic = $signatory->pivot?->approved_by_oic_id ? $oicUsers->get($signatory->pivot->approved_by_oic_id) : null;
                $signer = $oic ?? $signatory;
                $approved = $signatory->pivot?->is_approved;
                $officialDesignation = $signatory->pivot?->designation ?: ($index === 0 ? 'Immediate Supervisor' : 'Agency Head/Authorized Representative');

                return [
                    'heading' => $signatory->pivot?->heading ?: ($index === 0 ? 'Certified by:' : 'Approved by:'),
                    'designation' => $officialDesignation,
                    'name' => $signatory->employee_information?->full_name ?? $signatory->name,
                    'signature' => $approved ? $signer->signature?->content : null,
                    'esign_name' => $approved ? ($signer->employee_information?->full_name ?? $signer->name) : null,
                    'signed_by_oic' => filled($oic),
                    'approved_at' => $signatory->pivot?->approved_at,
                ];
            });
    }
}
