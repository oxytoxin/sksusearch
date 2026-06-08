@php
    // Status label + colour mapping (mirrors the ICU report)
    $statusLabels = [
        'required' => 'Completed',
        'not_required' => 'For Compliance',
        'not_applicable' => 'Not Applicable',
    ];
    $statusColors = [
        'required' => '#0f5132',
        'not_required' => '#b91c1c',
        'not_applicable' => '#6b7280',
    ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-Audit Notice</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f7; font-family: Arial, Helvetica, sans-serif; color:#333333;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f7; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="640" cellpadding="0" cellspacing="0" style="max-width:640px; width:100%; background-color:#ffffff; border-radius:8px; overflow:hidden; border:1px solid #e5e7eb;">

                    {{-- Header --}}
                    <tr>
                        <td style="background-color:#0f5132; padding:20px 32px;">
                            <span style="color:#ffffff; font-size:18px; font-weight:bold; letter-spacing:0.5px;">S.E.A.R.C.H</span>
                            <div style="color:#cfe3d8; font-size:12px; margin-top:2px;">Sultan Kudarat State University &middot; Internal Control Unit</div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:32px;">
                            <h1 style="margin:0 0 4px; font-size:20px; color:#111827;">Pre-Audit Notice</h1>
                            <p style="margin:0 0 20px; font-size:13px; color:#6b7280;">
                                Reference No. {{ $dv->tracking_number }}
                                @if ($dv->log_number) &middot; Log No. {{ $dv->log_number }} @endif
                                @if ($dv->documents_verified_at) &middot; {{ $dv->documents_verified_at->format('F d, Y') }} @endif
                            </p>

                            <p style="font-size:15px; line-height:1.6; color:#374151;">
                                Dear {{ $recipientName ?? 'Requisitioner' }},
                            </p>
                            <p style="font-size:15px; line-height:1.6; color:#374151;">
                                This notice pertains to the pre-audit of your Disbursement Voucher. Please review the summary of findings below.
                            </p>

                            {{-- Transaction details --}}
                            <h2 style="font-size:14px; color:#111827; margin:24px 0 8px; text-transform:uppercase; letter-spacing:0.5px;">Transaction Details</h2>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px; color:#374151; border-collapse:collapse;">
                                <tr>
                                    <td style="padding:4px 0; width:180px; color:#6b7280;">Reference No.</td>
                                    <td style="padding:4px 0;">{{ $dv->tracking_number }}</td>
                                </tr>
                                @if ($dv->dv_number)
                                <tr>
                                    <td style="padding:4px 0; color:#6b7280;">DV No.</td>
                                    <td style="padding:4px 0;">{{ $dv->dv_number }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="padding:4px 0; color:#6b7280;">Payee</td>
                                    <td style="padding:4px 0;">{{ $dv->payee ?? optional($dv->user)->name }}</td>
                                </tr>
                                @if ($dv->voucher_subtype)
                                <tr>
                                    <td style="padding:4px 0; color:#6b7280;">Type</td>
                                    <td style="padding:4px 0;">{{ optional($dv->voucher_subtype->voucher_type)->name }} for {{ $dv->voucher_subtype->name }}</td>
                                </tr>
                                @endif
                                @if (!empty($purposes))
                                <tr>
                                    <td style="padding:4px 0; color:#6b7280; vertical-align:top;">Particulars/Purpose</td>
                                    <td style="padding:4px 0;">{{ $purposes }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="padding:4px 0; color:#6b7280;">Amount</td>
                                    <td style="padding:4px 0;">&#8369;{{ number_format((float) $dv->total_sum, 2) }}</td>
                                </tr>
                            </table>

                            {{-- Summary of findings --}}
                            <h2 style="font-size:14px; color:#111827; margin:24px 0 8px; text-transform:uppercase; letter-spacing:0.5px;">Summary of Findings</h2>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:13px; color:#374151; border-collapse:collapse; border:1px solid #e5e7eb;">
                                <tr style="background-color:#f9fafb;">
                                    <th align="left" style="padding:8px 10px; border:1px solid #e5e7eb; font-size:12px; text-transform:uppercase; color:#6b7280;">Requirement</th>
                                    <th align="left" style="padding:8px 10px; border:1px solid #e5e7eb; font-size:12px; text-transform:uppercase; color:#6b7280; width:130px;">Status</th>
                                    <th align="left" style="padding:8px 10px; border:1px solid #e5e7eb; font-size:12px; text-transform:uppercase; color:#6b7280;">Comments</th>
                                </tr>
                                @forelse ($items as $item)
                                    @php $status = $item['status'] ?? 'required'; @endphp
                                    <tr>
                                        <td style="padding:8px 10px; border:1px solid #e5e7eb;">{{ $item['document'] ?? '' }}</td>
                                        <td style="padding:8px 10px; border:1px solid #e5e7eb; color:{{ $statusColors[$status] ?? '#374151' }}; font-weight:bold;">
                                            {{ $statusLabels[$status] ?? ucfirst($status) }}
                                        </td>
                                        <td style="padding:8px 10px; border:1px solid #e5e7eb;">{{ $item['remarks'] ?? '' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" style="padding:8px 10px; border:1px solid #e5e7eb; color:#6b7280;">No related documents required.</td>
                                    </tr>
                                @endforelse
                            </table>

                            {{-- Pre-audit result --}}
                            <h2 style="font-size:14px; color:#111827; margin:24px 0 8px; text-transform:uppercase; letter-spacing:0.5px;">Pre-Audit Result</h2>
                            <p style="margin:0; font-size:15px; font-weight:bold; color:{{ $isForCompliance ? '#b91c1c' : '#0f5132' }};">
                                {{ $isForCompliance ? 'For Compliance' : 'Verified / Compliant' }}
                            </p>

                            {{-- General comments --}}
                            <h2 style="font-size:14px; color:#111827; margin:24px 0 8px; text-transform:uppercase; letter-spacing:0.5px;">General Comments</h2>
                            <div style="font-size:14px; line-height:1.6; color:#374151;">
                                {!! filled($generalRemarks) ? $generalRemarks : '<span style="color:#6b7280;">No remarks.</span>' !!}
                            </div>

                            {{-- Important notice --}}
                            @if ($isForCompliance)
                                <div style="margin:24px 0 0; padding:14px 16px; background-color:#fef2f2; border:1px solid #fecaca; border-radius:6px;">
                                    <p style="margin:0; font-size:13px; color:#991b1b;">
                                        <strong>Important Notice:</strong> The Disbursement Voucher is now available for retrieval at the Office of the Internal Accountant.
                                        Kindly comply with the deficient requirement(s) noted above and re-submit to avoid delay in processing and release.
                                    </p>
                                </div>
                            @endif

                            {{-- Reviewer --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:32px;">
                                <tr>
                                    <td style="font-size:14px; color:#374151;">
                                        <div style="margin-bottom:24px;">Reviewed/Checked By:</div>
                                        <div style="font-weight:bold; text-decoration:underline;">{{ $reviewerName ?? '________________________' }}</div>
                                        @if ($reviewerPosition)
                                            <div style="font-size:13px; color:#6b7280;">{{ $reviewerPosition }}</div>
                                        @endif
                                        <div style="font-size:13px; color:#6b7280;">Internal Control Unit</div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:20px 32px; background-color:#f9fafb; border-top:1px solid #e5e7eb;">
                            <p style="margin:0 0 4px; font-size:12px; color:#6b7280;">
                                <strong>Office of the Internal Accountant</strong> &middot; Internal Control Unit (ICU)<br>
                                Sultan Kudarat State University &middot; ACCESS, EJC Montilla, 9800 City of Tacurong, Province of Sultan Kudarat
                            </p>
                            <p style="margin:8px 0 0; font-size:11px; color:#9ca3af;">
                                This is an automated message from the S.E.A.R.C.H system. Please do not reply to this email.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
