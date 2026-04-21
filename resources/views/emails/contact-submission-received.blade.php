<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('New Contact Message') }}</title>
</head>
<body style="margin: 0; padding: 24px 12px; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #111827;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 640px; margin: 0 auto; background-color: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden;">
        <tr>
            <td style="padding: 20px 24px; border-bottom: 1px solid #e5e7eb;">
                <p style="margin: 0; font-size: 13px; color: #6b7280;">{{ __('Contact Form') }}</p>
                <h1 style="margin: 6px 0 0; font-size: 20px; line-height: 1.3; color: #111827;">{{ __('New Contact Message') }}</h1>
            </td>
        </tr>

        <tr>
            <td style="padding: 16px 24px;">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="padding: 6px 0; width: 120px; font-size: 13px; color: #6b7280;">{{ __('Name') }}</td>
                        <td style="padding: 6px 0; font-size: 14px; color: #111827;">{{ $contactSubmission->name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; width: 120px; font-size: 13px; color: #6b7280;">{{ __('Email') }}</td>
                        <td style="padding: 6px 0; font-size: 14px; color: #111827;">
                            <a href="mailto:{{ $contactSubmission->email }}" style="color: #047857; text-decoration: none;">{{ $contactSubmission->email }}</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; width: 120px; font-size: 13px; color: #6b7280;">{{ __('Phone') }}</td>
                        <td style="padding: 6px 0; font-size: 14px; color: #111827;">{{ $contactSubmission->phone ?: __('N/A') }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; width: 120px; font-size: 13px; color: #6b7280;">{{ __('Subject') }}</td>
                        <td style="padding: 6px 0; font-size: 14px; color: #111827; font-weight: 600;">{{ $contactSubmission->subject }}</td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td style="padding: 0 24px 24px;">
                <div style="padding: 14px 16px; background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px;">
                    <p style="margin: 0 0 8px; font-size: 13px; color: #6b7280;">{{ __('Message') }}</p>
                    <p style="margin: 0; font-size: 14px; line-height: 1.7; color: #111827;">{!! nl2br(e($contactSubmission->message)) !!}</p>
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
