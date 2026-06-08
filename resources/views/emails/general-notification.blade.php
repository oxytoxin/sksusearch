<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f7; font-family: Arial, Helvetica, sans-serif; color:#333333;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f4f7; padding:24px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background-color:#ffffff; border-radius:8px; overflow:hidden; border:1px solid #e5e7eb;">
                    {{-- Header --}}
                    <tr>
                        <td style="background-color:#0f5132; padding:20px 32px;" align="left">
                            <span style="color:#ffffff; font-size:18px; font-weight:bold; letter-spacing:0.5px;">S.E.A.R.C.H</span>
                            <div style="color:#cfe3d8; font-size:12px; margin-top:2px;">Sultan Kudarat State University</div>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:32px;">
                            <h1 style="margin:0 0 16px; font-size:20px; color:#111827;">{{ $title }}</h1>
                            <div style="font-size:15px; line-height:1.6; color:#374151;">
                                {!! nl2br(e($bodyMessage)) !!}
                            </div>

                            @if (!empty($actionUrl))
                                <table role="presentation" cellpadding="0" cellspacing="0" style="margin:28px 0;">
                                    <tr>
                                        <td align="center" style="border-radius:6px; background-color:#0f5132;">
                                            <a href="{{ $actionUrl }}"
                                               style="display:inline-block; padding:12px 24px; font-size:14px; color:#ffffff; text-decoration:none; border-radius:6px;">
                                                View Details
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                                <p style="font-size:12px; color:#6b7280; word-break:break-all;">
                                    Or copy this link: {{ $actionUrl }}
                                </p>
                            @endif
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:20px 32px; background-color:#f9fafb; border-top:1px solid #e5e7eb;">
                            <p style="margin:0; font-size:12px; color:#9ca3af;">
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
