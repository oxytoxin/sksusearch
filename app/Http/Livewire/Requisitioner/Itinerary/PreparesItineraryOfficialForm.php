<?php

namespace App\Http\Livewire\Requisitioner\Itinerary;

use Carbon\Carbon;

trait PreparesItineraryOfficialForm
{
    protected function itineraryFormData(): array
    {
        $itinerary = $this->itinerary;
        $travelOrder = $this->travel_order ?? $itinerary->travel_order;
        $coverageRows = collect($this->coverage ?? $itinerary->coverage ?? []);
        $entriesByDate = $itinerary->itinerary_entries->groupBy(fn ($entry) => $entry->date?->format('Y-m-d'));
        $signatories = $travelOrder->signatories ?? collect();
        $preparedBy = $itinerary->user;
        $immediateSupervisor = $signatories->firstWhere('pivot.role', 'immediate_supervisor') ?? $signatories->first();
        $approvedBy = $signatories->firstWhere('pivot.role', 'university_president') ?? $signatories->last();
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
                'immediate_supervisor' => [
                    'name' => $immediateSupervisor?->employee_information?->full_name ?? $immediateSupervisor?->name,
                    'signature' => $immediateSupervisor?->pivot?->is_approved ? $immediateSupervisor->signature?->content : null,
                ],
                'approved_by' => [
                    'name' => $approvedBy?->employee_information?->full_name ?? $approvedBy?->name,
                    'signature' => $approvedBy?->pivot?->is_approved ? $approvedBy->signature?->content : null,
                ],
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
}
