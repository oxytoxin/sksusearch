@php
    $entries = $itinerary->itinerary_entries ?? collect();
    $coverageRows = collect($coverage ?? $itinerary->coverage ?? []);
    $entriesByDate = $entries->groupBy(fn ($entry) => $entry->date?->format('Y-m-d'));
    $voucher = $travel_order->disbursement_vouchers?->first();
    $signatories = $travel_order->signatories ?? collect();
    $preparedBy = $itinerary->user;
    $approvedBy = $signatories->firstWhere('pivot.role', 'university_president') ?? $signatories->last();
    $immediateSupervisor = $signatories->firstWhere('pivot.role', 'immediate_supervisor') ?? $signatories->first();
    $purposeText = filled($itinerary->purpose) ? $itinerary->purpose : $travel_order->purpose;
    $perDiemTotal = 0;
    $transportationTotal = 0;
    $otherTotal = 0;
    $printedRows = 0;
    $minimumRows = 22;
@endphp

<div class="itinerary-form mx-auto bg-white font-serif text-black">
    <style>
        .itinerary-form {
            width: 100%;
            max-width: 13in;
            font-size: 11px;
            line-height: 1.12;
        }

        .itinerary-form table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        .itinerary-form th,
        .itinerary-form td {
            border: 1px solid #000;
            padding: 2px 4px;
            vertical-align: top;
        }

        .itinerary-form th {
            font-weight: 700;
            text-align: center;
        }

        .itinerary-form .no-border {
            border: 0;
        }

        .itinerary-form .outer-border {
            border: 2px solid #000;
        }

        .itinerary-form .title-row {
            border: 2px solid #168044;
            font-size: 14px;
            font-weight: 700;
            height: 20px;
            text-align: center;
        }

        .itinerary-form .appendix {
            font-size: 13px;
            font-style: italic;
            text-align: right;
        }

        .itinerary-form .label {
            font-weight: 700;
        }

        .itinerary-form .amount {
            text-align: right;
            white-space: nowrap;
        }

        .itinerary-form .center {
            text-align: center;
        }

        .itinerary-form .entry-row td {
            height: 15px;
        }

        .itinerary-form .total-row td {
            border-top: 2px solid #000;
            font-weight: 700;
        }

        .itinerary-form .signature-cell {
            height: 72px;
            position: relative;
            text-align: center;
            vertical-align: bottom;
        }

        .itinerary-form .signature-name {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 62%;
            padding: 0 8px 1px;
            position: relative;
        }

        .itinerary-form .signature-caption {
            display: block;
            font-size: 10px;
        }

        .itinerary-form .certification {
            height: 156px;
            text-align: center;
            vertical-align: top;
        }

        .itinerary-form .certification-text {
            margin-top: 28px;
            text-align: justify;
            text-align-last: center;
        }

        @media print {
            @page {
                size: legal landscape;
                margin: 8mm;
            }

            body {
                margin: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .itinerary-form {
                max-width: none;
                width: 100%;
                font-size: 10px;
            }
        }
    </style>

    <table>
        <colgroup>
            <col style="width: 7.5%">
            <col style="width: 28.5%">
            <col style="width: 7.25%">
            <col style="width: 7.75%">
            <col style="width: 13%">
            <col style="width: 9%">
            <col style="width: 9%">
            <col style="width: 9%">
            <col style="width: 9%">
        </colgroup>
        <tbody>
            <tr>
                <td class="appendix no-border" colspan="9">Appendix 45</td>
            </tr>
            <tr>
                <td class="title-row" colspan="9">ITINERARY OF TRAVEL</td>
            </tr>
            <tr>
                <td class="no-border" colspan="9" style="height: 28px;"></td>
            </tr>
            <tr>
                <td class="no-border" colspan="6">
                    <span class="label">Entity Name :</span>
                    <span class="label">SULTAN KUDARAT STATE UNIVERSITY</span>
                </td>
                <td class="no-border" colspan="3">
                    <span class="label">No.:</span>
                    <span class="label">{{ $travel_order->tracking_code }}</span>
                </td>
            </tr>
            <tr>
                <td class="no-border" colspan="9">
                    <span class="label">Fund Cluster:</span>
                    {{ $voucher?->fund_cluster?->name }}
                </td>
            </tr>
        </tbody>
    </table>

    <table class="outer-border">
        <colgroup>
            <col style="width: 7.5%">
            <col style="width: 28.5%">
            <col style="width: 7.25%">
            <col style="width: 7.75%">
            <col style="width: 13%">
            <col style="width: 9%">
            <col style="width: 9%">
            <col style="width: 9%">
            <col style="width: 9%">
        </colgroup>
        <thead>
            <tr>
                <td colspan="4">
                    <div><span class="label">Name :</span> <span class="label">{{ $preparedBy->employee_information?->full_name ?? $preparedBy->name }}</span></div>
                    <div><span class="label">Position :</span> {{ $preparedBy->employee_information?->position?->description }}</div>
                    <div><span class="label">Official Station :</span> {{ $preparedBy->employee_information?->office?->name }}</div>
                </td>
                <td colspan="5">
                    <div>
                        <span class="label">Date of Travel :</span>
                        {{ $travel_order->date_from?->format('M d, Y') }} to {{ $travel_order->date_to?->format('M d, Y') }}
                    </div>
                    <div><span class="label">Purpose of Travel :</span> <span class="whitespace-pre-line">{{ $purposeText }}</span></div>
                </td>
            </tr>
            <tr>
                <th rowspan="2">Date</th>
                <th rowspan="2">Places to be visited<br>(Destination)</th>
                <th colspan="2">T I M E</th>
                <th rowspan="2">Means of<br>Transportation</th>
                <th rowspan="2">Transpor-<br>tation</th>
                <th rowspan="2">Per<br>Diem</th>
                <th rowspan="2">Others</th>
                <th rowspan="2">Total Amount</th>
            </tr>
            <tr>
                <th>Departure</th>
                <th>Arrival</th>
            </tr>
        </thead>
        <tbody>
            @if ($travel_order->has_registration)
                @php
                    $registrationAmount = $travel_order->registration_amount ?? 0;
                    $otherTotal += $registrationAmount;
                    $printedRows++;
                @endphp
                <tr class="entry-row">
                    <td></td>
                    <td class="center">Registration Amount</td>
                    <td class="center">-</td>
                    <td class="center">-</td>
                    <td class="center">-</td>
                    <td class="center">-</td>
                    <td class="center">-</td>
                    <td class="amount">{{ $registrationAmount ? number_format($registrationAmount, 2) : '-' }}</td>
                    <td class="amount">{{ $registrationAmount ? number_format($registrationAmount, 2) : '-' }}</td>
                </tr>
            @endif

            @foreach ($coverageRows as $covered)
                @php
                    $coveredDate = $covered['date'] ?? null;
                    $coveredDateKey = $coveredDate ? \Carbon\Carbon::parse($coveredDate)->format('Y-m-d') : null;
                    $perDiem = $covered['per_diem'] ?? 0;
                    $perDiemTotal += $perDiem;
                    $printedRows++;
                @endphp
                <tr class="entry-row">
                    <td class="center">{{ $coveredDateKey ? \Carbon\Carbon::parse($coveredDateKey)->format('M d, Y') : '' }}</td>
                    <td class="center">-</td>
                    <td class="center">-</td>
                    <td class="center">-</td>
                    <td class="center">-</td>
                    <td class="center">-</td>
                    <td class="amount">{{ $perDiem ? number_format($perDiem, 2) : '-' }}</td>
                    <td class="center">-</td>
                    <td class="amount">{{ $perDiem ? number_format($perDiem, 2) : '-' }}</td>
                </tr>

                @foreach ($entriesByDate->get($coveredDateKey, collect()) as $entry)
                    @php
                        $transportation = $entry->transportation_expenses ?? 0;
                        $others = $entry->other_expenses ?? 0;
                        $rowTotal = $transportation + $others;
                        $transportationTotal += $transportation;
                        $otherTotal += $others;
                        $printedRows++;
                    @endphp
                    <tr class="entry-row">
                        <td class="center">{{ $entry->date?->format('M d, Y') }}</td>
                        <td>{{ $entry->place }}</td>
                        <td class="center">{{ $entry->departure_time?->format('g:i A') }}</td>
                        <td class="center">{{ $entry->arrival_time?->format('g:i A') }}</td>
                        <td class="center">{{ $entry->mot?->name }}</td>
                        <td class="amount">{{ $transportation ? number_format($transportation, 2) : '-' }}</td>
                        <td class="center">-</td>
                        <td class="amount">{{ $others ? number_format($others, 2) : '-' }}</td>
                        <td class="amount">{{ $rowTotal ? number_format($rowTotal, 2) : '-' }}</td>
                    </tr>
                @endforeach
            @endforeach

            @for ($i = $printedRows; $i < $minimumRows; $i++)
                <tr class="entry-row">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="amount">-</td>
                </tr>
            @endfor

            @php
                $grandTotal = $perDiemTotal + $transportationTotal + $otherTotal;
            @endphp
            <tr class="total-row">
                <td colspan="5" class="center">TOTAL</td>
                <td class="amount">{{ $transportationTotal ? number_format($transportationTotal, 2) : '-' }}</td>
                <td class="amount">{{ $perDiemTotal ? number_format($perDiemTotal, 2) : '-' }}</td>
                <td class="amount">{{ $otherTotal ? number_format($otherTotal, 2) : '-' }}</td>
                <td class="amount">{{ $grandTotal ? number_format($grandTotal, 2) : '-' }}</td>
            </tr>
            <tr>
                <td class="certification" colspan="4" rowspan="2">
                    <p class="certification-text">
                        I certify that : (1) I have reviewed the foregoing itinerary, (2) the travel is necessary to the service, (3) the period covered is reasonable and (4) the expenses claimed are proper.
                    </p>
                    <div style="margin-top: 66px;">
                        <span class="signature-name">
                            @if ($immediateSupervisor?->pivot?->is_approved)
                                <x-signature-block :signature="$immediateSupervisor->signature?->content" width="9rem" maxHeight="3.5rem" bottom="100%" translateY="1.25rem" />
                            @endif
                            {{ $immediateSupervisor?->employee_information?->full_name ?? $immediateSupervisor?->name }}
                        </span>
                        <span class="signature-caption">Signature over Printed Name</span>
                        <span class="signature-caption">Immediate Supervisor</span>
                    </div>
                </td>
                <td colspan="5">
                    <span class="label">Prepared by :</span>
                    <div class="signature-cell">
                        <span class="signature-name">
                            <x-signature-block :signature="$preparedBy->signature?->content" width="9rem" maxHeight="3.5rem" bottom="100%" translateY="1.25rem" />
                            {{ $preparedBy->employee_information?->full_name ?? $preparedBy->name }}
                        </span>
                        <span class="signature-caption">Signature over Printed Name</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan="5">
                    <span class="label">Approved by:</span>
                    <div class="signature-cell">
                        <span class="signature-name">
                            @if ($approvedBy?->pivot?->is_approved)
                                <x-signature-block :signature="$approvedBy->signature?->content" width="9rem" maxHeight="3.5rem" bottom="100%" translateY="1.25rem" />
                            @endif
                            {{ $approvedBy?->employee_information?->full_name ?? $approvedBy?->name }}
                        </span>
                        <span class="signature-caption">Signature over Printed Name</span>
                        <span class="signature-caption">Agency Head/Authorized Representative</span>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
