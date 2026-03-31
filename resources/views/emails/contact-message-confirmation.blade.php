<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>We received your message</title>
</head>
<body style="margin:0;padding:24px;background:#f4f7f4;font-family:Arial, Helvetica, sans-serif;color:#123127;">
    <div style="max-width:720px;margin:0 auto;background:#ffffff;border:1px solid #d9e5dd;border-radius:20px;overflow:hidden;">
        <div style="padding:24px 28px;background:#0f3d2e;color:#ffffff;">
            <div style="font-size:12px;letter-spacing:0.18em;text-transform:uppercase;opacity:0.8;">PH Haiyan Advocacy Inc.</div>
            <h1 style="margin:10px 0 0;font-size:28px;line-height:1.15;font-weight:700;">Your message has been received</h1>
        </div>

        <div style="padding:28px;">
            <p style="margin:0;font-size:17px;line-height:1.8;color:#334a40;">
                Hello {{ $contactMessage->name }},
            </p>

            <p style="margin:18px 0 0;font-size:16px;line-height:1.8;color:#334a40;">
                Thank you for contacting PH Haiyan Advocacy Inc. We received your inquiry and the team will review it as soon as possible.
            </p>

            <div style="margin-top:22px;padding:18px 20px;border:1px solid #dfe8e2;border-radius:16px;background:#fafcfb;">
                <div style="font-size:12px;text-transform:uppercase;letter-spacing:0.16em;color:#5c746a;font-weight:700;">Inquiry Type</div>
                <div style="margin-top:8px;font-size:18px;color:#123127;font-weight:700;">{{ $inquiryLabel }}</div>

                <div style="margin-top:18px;font-size:12px;text-transform:uppercase;letter-spacing:0.16em;color:#5c746a;font-weight:700;">Subject</div>
                <div style="margin-top:8px;font-size:20px;line-height:1.4;color:#123127;font-weight:700;">{{ $contactMessage->subject }}</div>

                <div style="margin-top:18px;font-size:12px;text-transform:uppercase;letter-spacing:0.16em;color:#5c746a;font-weight:700;">Submitted</div>
                <div style="margin-top:8px;font-size:16px;color:#123127;font-weight:700;">{{ optional($contactMessage->submitted_at)->format('F d, Y h:i A') }}</div>
            </div>

            <div style="margin-top:20px;padding:20px;border:1px solid #dfe8e2;border-radius:16px;background:#ffffff;">
                <div style="font-size:12px;text-transform:uppercase;letter-spacing:0.16em;color:#5c746a;font-weight:700;">Your Message</div>
                <div style="margin-top:12px;font-size:16px;line-height:1.8;color:#334a40;white-space:pre-line;">{{ $contactMessage->message }}</div>
            </div>

            <p style="margin:22px 0 0;font-size:15px;line-height:1.8;color:#5c746a;">
                If you need to add more details, you can reply directly to this email.
            </p>

            <div style="margin-top:24px;">
                <a href="mailto:{{ config('site.contact.email') }}" style="display:inline-block;padding:14px 22px;border-radius:999px;background:#0f3d2e;color:#ffffff;text-decoration:none;font-weight:700;">
                    Contact PH Haiyan
                </a>
            </div>
        </div>
    </div>
</body>
</html>
