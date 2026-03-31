<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Message</title>
</head>
<body style="margin:0;padding:24px;background:#f4f7f4;font-family:Arial, Helvetica, sans-serif;color:#123127;">
    <div style="max-width:720px;margin:0 auto;background:#ffffff;border:1px solid #d9e5dd;border-radius:20px;overflow:hidden;">
        <div style="padding:24px 28px;background:#0f3d2e;color:#ffffff;">
            <div style="font-size:12px;letter-spacing:0.18em;text-transform:uppercase;opacity:0.8;">PH Haiyan Advocacy Inc.</div>
            <h1 style="margin:10px 0 0;font-size:28px;line-height:1.15;font-weight:700;">New contact inquiry received</h1>
        </div>

        <div style="padding:28px;">
            <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;">
                <tr>
                    <td style="padding:0 0 18px;">
                        <div style="font-size:12px;text-transform:uppercase;letter-spacing:0.16em;color:#5c746a;font-weight:700;">Inquiry Type</div>
                        <div style="margin-top:8px;font-size:18px;color:#123127;font-weight:700;">{{ $inquiryLabel }}</div>
                    </td>
                    <td style="padding:0 0 18px;">
                        <div style="font-size:12px;text-transform:uppercase;letter-spacing:0.16em;color:#5c746a;font-weight:700;">Submitted</div>
                        <div style="margin-top:8px;font-size:18px;color:#123127;font-weight:700;">{{ optional($contactMessage->submitted_at)->format('F d, Y h:i A') }}</div>
                    </td>
                </tr>
            </table>

            <div style="margin-top:6px;padding:18px 20px;border:1px solid #dfe8e2;border-radius:16px;background:#fafcfb;">
                <div style="font-size:12px;text-transform:uppercase;letter-spacing:0.16em;color:#5c746a;font-weight:700;">Subject</div>
                <div style="margin-top:8px;font-size:22px;line-height:1.3;color:#123127;font-weight:700;">{{ $contactMessage->subject }}</div>
            </div>

            <div style="margin-top:20px;display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px;">
                <div style="padding:16px 18px;border:1px solid #dfe8e2;border-radius:16px;background:#ffffff;">
                    <div style="font-size:12px;text-transform:uppercase;letter-spacing:0.16em;color:#5c746a;font-weight:700;">Name</div>
                    <div style="margin-top:8px;font-size:16px;color:#123127;font-weight:700;">{{ $contactMessage->name }}</div>
                </div>
                <div style="padding:16px 18px;border:1px solid #dfe8e2;border-radius:16px;background:#ffffff;">
                    <div style="font-size:12px;text-transform:uppercase;letter-spacing:0.16em;color:#5c746a;font-weight:700;">Email</div>
                    <div style="margin-top:8px;font-size:16px;color:#123127;font-weight:700;">{{ $contactMessage->email }}</div>
                </div>
                <div style="padding:16px 18px;border:1px solid #dfe8e2;border-radius:16px;background:#ffffff;">
                    <div style="font-size:12px;text-transform:uppercase;letter-spacing:0.16em;color:#5c746a;font-weight:700;">Phone</div>
                    <div style="margin-top:8px;font-size:16px;color:#123127;font-weight:700;">{{ $contactMessage->phone ?: 'Not provided' }}</div>
                </div>
                <div style="padding:16px 18px;border:1px solid #dfe8e2;border-radius:16px;background:#ffffff;">
                    <div style="font-size:12px;text-transform:uppercase;letter-spacing:0.16em;color:#5c746a;font-weight:700;">Organization</div>
                    <div style="margin-top:8px;font-size:16px;color:#123127;font-weight:700;">{{ $contactMessage->organization ?: 'Not provided' }}</div>
                </div>
            </div>

            <div style="margin-top:20px;padding:20px;border:1px solid #dfe8e2;border-radius:16px;background:#ffffff;">
                <div style="font-size:12px;text-transform:uppercase;letter-spacing:0.16em;color:#5c746a;font-weight:700;">Message</div>
                <div style="margin-top:12px;font-size:16px;line-height:1.8;color:#334a40;white-space:pre-line;">{{ $contactMessage->message }}</div>
            </div>

            <div style="margin-top:24px;">
                <a href="mailto:{{ $contactMessage->email }}?subject={{ rawurlencode('Re: ' . $contactMessage->subject) }}" style="display:inline-block;padding:14px 22px;border-radius:999px;background:#0f3d2e;color:#ffffff;text-decoration:none;font-weight:700;">
                    Reply to sender
                </a>
            </div>
        </div>
    </div>
</body>
</html>
