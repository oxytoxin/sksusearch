<div>
    <style>
        @media print {
            /* Hide all app layout elements */
            nav, header, aside,
            .no-print,
            .md\:fixed,
            .md\:flex-col.md\:w-64,
            [x-data="{ opensidebar: false }"],
            .filament-notifications {
                display: none !important;
            }
            body, .min-h-screen {
                background: white !important;
            }
            .md\:pl-64 {
                padding-left: 0 !important;
            }
            .print-container {
                padding: 0 !important;
                margin: 0 !important;
                max-width: 100% !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }
        }

        /* Screen-only styling */
        @media screen {
            .print-page-wrapper {
                min-height: 100vh;
                background: #f1f5f9;
                padding: 2rem 1rem;
            }
            .print-container {
                background: white;
                box-shadow: 0 1px 3px rgba(0,0,0,0.1), 0 4px 20px rgba(0,0,0,0.06);
                border-radius: 8px;
            }
            .print-table th {
                background: #f8fafc;
            }
            .print-table tr:hover {
                background: #f8fafc;
            }
        }

        .print-container {
            max-width: 8.5in;
            margin: 0 auto;
            padding: 0.5in;
            font-family: 'Times New Roman', serif;
        }
        .print-table {
            width: 100%;
            border-collapse: collapse;
        }
        .print-table th, .print-table td {
            border: 1px solid black;
            padding: 4px 8px;
            font-size: 12px;
        }
        .print-table th {
            text-align: center;
            font-weight: bold;
        }
    </style>

    <div class="print-page-wrapper">
        <div class="no-print" style="max-width: 8.5in; margin: 0 auto 1.5rem; display: flex; justify-content: center; gap: 0.5rem;">
            <button onclick="window.print()" class="rounded-lg bg-primary-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-700 transition-colors">
                Print
            </button>
            <a href="{{ route('office.batch-transmittal.show', $batch) }}" class="rounded-lg border border-gray-300 bg-white px-6 py-2.5 text-sm font-semibold text-gray-700 shadow-sm hover:bg-gray-50 transition-colors">
                Back
            </a>
        </div>

    <div class="print-container">
        {{-- Header --}}
        <div style="text-align: center; margin-bottom: 20px;">
            <p style="font-size: 12px; margin: 0;">Republic of the Philippines</p>
            <p style="font-size: 14px; font-weight: bold; margin: 2px 0;">SULTAN KUDARAT STATE UNIVERSITY</p>
            <p style="font-size: 12px; margin: 0;">EJC Montilla, Tacurong City, 9800</p>
            <p style="font-size: 14px; font-weight: bold; margin: 8px 0 2px;">{{ strtoupper($batch->from_office_name) }}</p>
            <p style="font-size: 13px; margin: 0;">Transmittal to {{ $batch->to_office_name }}</p>
        </div>

        {{-- Transmittal info --}}
        <div style="display: flex; justify-content: flex-end; margin-bottom: 10px; font-size: 12px;">
            <div>
                <p style="margin: 0;">Transmittal No: <strong>{{ $batch->serial_number }}</strong></p>
                <p style="margin: 0;">Date: {{ $batch->forwarded_at?->format('m/d/Y') ?? $batch->created_at->format('m/d/Y') }}</p>
            </div>
        </div>

        {{-- Table --}}
        @if ($batch->document_type === 'liquidation_report')
            <table class="print-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No.</th>
                        <th style="width: 14%;">LR No.</th>
                        <th>Requisitioner</th>
                        <th style="width: 16%;">DV No.</th>
                        <th style="width: 14%;">Amount</th>
                        <th style="width: 14%;">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($batch->items as $index => $item)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td style="text-align: center;">{{ $item->liquidation_report?->lr_number ?? '' }}</td>
                            <td>{{ $item->liquidation_report?->requisitioner?->employee_information?->full_name ?? '' }}</td>
                            <td style="text-align: center;">{{ $item->liquidation_report?->disbursement_voucher?->tracking_number ?? '' }}</td>
                            <td style="text-align: right;">{{ number_format($item->liquidation_report?->total_amount ?? 0, 2) }}</td>
                            <td>{{ $item->remarks ?? '' }}</td>
                        </tr>
                    @endforeach
                    {{-- Empty rows to fill the page --}}
                    @for ($i = $batch->items->count(); $i < max($batch->items->count(), 10); $i++)
                        <tr>
                            <td style="height: 24px;">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        @else
            <table class="print-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">No.</th>
                        <th style="width: 10%;">DV No.</th>
                        <th>Payee</th>
                        <th>Particulars</th>
                        <th style="width: 12%;">Gross Amount</th>
                        <th style="width: 12%;">Net Amount</th>
                        <th style="width: 12%;">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($batch->items as $index => $item)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td style="text-align: center;">{{ $item->disbursement_voucher->dv_number ?? '' }}</td>
                            <td>{{ $item->disbursement_voucher->payee }}</td>
                            <td>{{ $item->disbursement_voucher->disbursement_voucher_particulars->pluck('purpose')->join('; ') }}</td>
                            <td style="text-align: right;">{{ number_format($item->disbursement_voucher->gross_amount ?? $item->disbursement_voucher->disbursement_voucher_particulars->sum('amount'), 2) }}</td>
                            <td style="text-align: right;">{{ number_format($item->disbursement_voucher->disbursement_voucher_particulars->sum('amount'), 2) }}</td>
                            <td>{{ $item->remarks ?? '' }}</td>
                        </tr>
                    @endforeach
                    {{-- Empty rows to fill the page --}}
                    @for ($i = $batch->items->count(); $i < max($batch->items->count(), 10); $i++)
                        <tr>
                            <td style="height: 24px;">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        @endif

        {{-- Footer signatures - aligned right --}}
        <div style="display: flex; justify-content: flex-end; margin-top: 40px; font-size: 12px;">
            <div>
                <p style="margin: 0;">Signature: ___________________________</p>
                <p style="margin: 10px 0 0;">Received by: ________________________</p>
                <p style="margin: 10px 0 0;">Date: _______________________________</p>
            </div>
        </div>
    </div>
    </div>
</div>
