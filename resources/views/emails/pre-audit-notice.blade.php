@php
    // Status label + colour mapping (mirrors the on-screen notice)
    $statusLabels = [
        'required' => 'Complied',
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
<body style="margin:0; padding:0; background-color:#ffffff; font-family: Arial, Helvetica, sans-serif; color:#333333;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#ffffff; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="640" cellpadding="0" cellspacing="0" style="max-width:640px; width:100%; background-color:#ffffff;">

                    <tr>
                        <td style="padding:8px 32px 32px;">
                            <h1 style="margin:0 0 4px; font-size:20px; color:#111827;">Pre-Audit Notice</h1>
                            <p style="margin:0 0 20px; font-size:13px; color:#6b7280;">
                                Reference No. {{ $dv->tracking_number }}
                                @if ($dv->log_number) &middot; Log No. {{ $dv->log_number }} @endif
                                @if ($dv->documents_verified_at) &middot; {{ $dv->documents_verified_at->format('F d, Y') }} @endif
                            </p>

                            <p style="font-size:15px; line-height:1.6; color:#374151;">
                                This notice pertains to the pre-audit of your Disbursement Voucher.
                            </p>

                            {{-- Transaction details --}}
                            <h2 style="font-size:14px; color:#111827; margin:24px 0 8px; text-transform:uppercase; letter-spacing:0.5px;">Transaction Details</h2>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px; color:#374151; border-collapse:collapse;">
                                @if ($dv->dv_number)
                                <tr>
                                    <td style="padding:4px 0; width:160px; color:#6b7280;">DV No.</td>
                                    <td style="padding:4px 0;">{{ $dv->dv_number }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="padding:4px 0; width:160px; color:#6b7280;">Payee</td>
                                    <td style="padding:4px 0;">{{ $dv->payee ?? optional($dv->user)->name }}</td>
                                </tr>
                                @if (!empty($purposes))
                                <tr>
                                    <td style="padding:4px 0; color:#6b7280; vertical-align:top;">Particulars</td>
                                    <td style="padding:4px 0;">{{ $purposes }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="padding:4px 0; color:#6b7280;">Amount</td>
                                    <td style="padding:4px 0;">&#8369;{{ number_format((float) $dv->total_sum, 2) }}</td>
                                </tr>
                                @if ($dv->voucher_subtype)
                                <tr>
                                    <td style="padding:4px 0; color:#6b7280; vertical-align:top;">Transaction Type</td>
                                    <td style="padding:4px 0;">{{ optional($dv->voucher_subtype->voucher_type)->name }}</td>
                                </tr>
                                @endif
                            </table>

                            {{-- Pre-audit result --}}
                            <h2 style="font-size:14px; color:#111827; margin:24px 0 8px; text-transform:uppercase; letter-spacing:0.5px;">Pre-Audit Result</h2>
                            <p style="margin:0 0 8px; font-size:15px;">
                                <span style="color:#6b7280;">Status:</span>
                                <strong style="color:{{ $isForCompliance ? '#b91c1c' : '#0f5132' }};">{{ $isForCompliance ? 'FOR COMPLIANCE' : 'VERIFIED' }}</strong>
                            </p>
                            <p style="margin:0; font-size:14px; line-height:1.6; color:#374151;">
                                @if ($isForCompliance)
                                    The transaction has been evaluated and requires compliance with specific documentary and/or procedural requirements prior to final processing and release.
                                @else
                                    The transaction has been evaluated and found compliant with the documentary and procedural requirements. It is cleared for final processing and release.
                                @endif
                            </p>

                            {{-- Summary of findings --}}
                            <h2 style="font-size:14px; color:#111827; margin:24px 0 8px; text-transform:uppercase; letter-spacing:0.5px;">Summary of Findings</h2>
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:13px; color:#374151; border-collapse:collapse; border:1px solid #e5e7eb;">
                                <tr style="background-color:#f9fafb;">
                                    <th align="left" style="padding:8px 10px; border:1px solid #e5e7eb; font-size:12px; text-transform:uppercase; color:#6b7280;">Requirement</th>
                                    <th align="left" style="padding:8px 10px; border:1px solid #e5e7eb; font-size:12px; text-transform:uppercase; color:#6b7280; width:130px;">Status</th>
                                    <th align="left" style="padding:8px 10px; border:1px solid #e5e7eb; font-size:12px; text-transform:uppercase; color:#6b7280; width:150px;">Comments</th>
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

                            {{-- General comments (shows "None" when empty, to match the notice) --}}
                            <h2 style="font-size:14px; color:#111827; margin:24px 0 8px; text-transform:uppercase; letter-spacing:0.5px;">General Comments</h2>
                            <div style="font-size:14px; line-height:1.6; color:#374151;">
                                @if (filled($generalRemarks))
                                    {!! $generalRemarks !!}
                                @else
                                    None
                                @endif
                            </div>

                            {{-- Important notice (only for compliance) --}}
                            @if ($isForCompliance)
                                <h2 style="font-size:14px; color:#111827; margin:24px 0 8px; text-transform:uppercase; letter-spacing:0.5px;">Important Notice</h2>
                                <p style="margin:0; font-size:14px; line-height:1.7; color:#374151;">
                                    The Disbursement Voucher is now available for <span style="background-color:#dc2626; color:#ffffff; padding:1px 6px; font-weight:bold;">PICKUP</span> at the Office of the University Accountant following the pre-audit evaluation, which identified certain deficiencies for compliance. The client is advised to <span style="background-color:#dc2626; color:#ffffff; padding:1px 6px; font-weight:bold;">claim the voucher at the earliest possible time and address the noted requirements to avoid any delay in processing and release.</span>
                                </p>
                            @endif

                            {{-- Contact --}}
                            <p style="margin:24px 0 0; font-size:14px; line-height:1.6; color:#374151;">
                                For clarification or assistance, please coordinate with the Accounting Office. You may reach us through:
                            </p>
                            <p style="margin:6px 0 0; font-size:14px; line-height:1.8; color:#374151;">
                                &#128231; Email: <a href="mailto:accounting@sksu.edu.ph" style="color:#0f5132;">accounting@sksu.edu.ph</a><br>
                                &#128222; Contact Number: 0906-623-4007
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:20px 32px; background-color:#ffffff; border-top:1px solid #e5e7eb;">
                            <p style="margin:0 0 4px; font-size:13px; color:#374151;">
                                <strong>Office of the University Accountant</strong><br>
                                Sultan Kudarat State University
                            </p>
                            @if (filled($senderEmail))
                                <p style="margin:8px 0 0; font-size:12px; color:#6b7280;">
                                    Email initiated by: <a href="mailto:{{ $senderEmail }}" style="color:#0f5132;">{{ $senderEmail }}</a> via Voucher Management System (VMS)
                                </p>
                            @endif

                            <p style="margin:16px 0 0; font-size:11px; font-style:italic; color:#9ca3af;">
                                <strong>ELECTRONIC TRANSMITTAL REMINDER:</strong><br>
                                The transmittal of this e-mail carries full legal recognition pursuant to Section 6 of RA No. 8792. Moreover, Sections 8 and 9 of SKSU's Institutional Policies on Electronic Channels of Communication, adopted through BOR Resolution No. 56, s. 2024, provides that the duly recognized transmission time shall be the earlier of the following:
                            </p>
                            <p style="margin:8px 0 0; font-size:11px; font-style:italic; color:#9ca3af;">
                                (i) Date and time of the confirmation reply made through the concerned official mobile number or institutional email; or
                            </p>
                            <p style="margin:8px 0 0; font-size:11px; font-style:italic; color:#9ca3af;">
                                (ii) The twenty-fourth (24th) hour after the sending of the message or document provided the same was sent within regular working hours, i.e., 8:00AM-5:00PM on a regular workday. If the message or document were sent beyond said hours, the counting of the 24-hour period shall commence at 8:00AM of the immediately succeeding regular workday. Furthermore, the counting of the 24-hour period shall be suspended at all times of the day covering weekends, holidays, and other official non-working days. The fact that the employee is on any kind of travel (whether on official business or official time) shall not cause the suspension of the counting of the 24-hour period.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
