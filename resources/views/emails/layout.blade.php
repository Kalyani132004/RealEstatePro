<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('subject', 'RealEstatePro')</title>
    <!--[if mso]>
    <noscript><xml><o:OfficeDocumentSettings><o:PixelsPerInch>96</o:PixelsPerInch></o:OfficeDocumentSettings></xml></noscript>
    <![endif]-->
    <style>
        body, table, td { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
        body { margin: 0; padding: 0; background-color: #F4F6F9; }
        a { color: #0EA5A0; }
        .rep-email-btn:hover { background-color: #2C5282 !important; }
        @media (max-width: 620px) {
            .rep-email-container { width: 100% !important; }
            .rep-email-padding { padding: 24px !important; }
        }
    </style>
</head>
<body style="margin:0; padding:0; background-color:#F4F6F9;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#F4F6F9; padding: 32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" class="rep-email-container" width="600" cellpadding="0" cellspacing="0" style="width:600px; max-width:100%; background-color:#FFFFFF; border-radius:16px; overflow:hidden; box-shadow: 0 8px 24px rgba(15,23,42,0.08);">

                    {{-- Header --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #1E3A5F, #0EA5A0); padding: 28px 32px;" align="center">
                            <span style="font-family: Helvetica, Arial, sans-serif; font-size: 22px; font-weight: 700; color: #ffffff; letter-spacing: -0.02em;">
                                RealEstate<span style="color:#D4A853;">Pro</span>
                            </span>
                        </td>
                    </tr>

                    {{-- Content --}}
                    <tr>
                        <td class="rep-email-padding" style="padding: 40px 32px; color:#1A202C; font-size:15px; line-height:1.6;">
                            @yield('content')
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding: 24px 32px; background-color:#F4F6F9; text-align:center;">
                            <p style="margin:0 0 8px; font-size:12px; color:#64748B;">
                                &copy; {{ date('Y') }} RealEstatePro. All rights reserved.
                            </p>
                            <p style="margin:0; font-size:12px; color:#64748B;">
                                This is an automated message — please do not reply directly to this email.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
