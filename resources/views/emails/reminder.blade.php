<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Appointment Reminder</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px;">
<table width="100%" cellpadding="0" cellspacing="0" style="max-width: 600px; margin: auto; background-color: #ffffff; border-radius: 10px; padding: 20px;">
    <tr>
        <!-- Image in top-left -->
        <td width="80" valign="top" style="padding-right: 15px;">
            <img src="{{ asset('assets/whitepill.png') }}" alt="Pill" style="width: 60px; display: block;">
        </td>
        <!-- Text content -->
        <td valign="top">
            <h2 style="color: #4856f2; margin: 0 0 10px 0;">Hello {{ $appointment->patient->user->name }},</h2>
            <p style="font-size: 16px; color: #333; margin: 0;">
                This is a reminder for your appointment on
                <strong>{{ $appointment->date->format('d M Y H:i') }}</strong>.
            </p>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="padding-top: 20px; font-size: 14px; color: #666;">
            Please make sure to arrive 10 minutes early.
        </td>
    </tr>
</table>
</body>
</html>
