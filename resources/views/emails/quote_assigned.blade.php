<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quote Request Received</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    <h2>Hello {{ $name }},</h2>

    <p>Thank you for your insurance quote request.</p>

    <p>
        We have received your request and assigned it to one of our staff members
        (<strong>{{ $assignedTo }}</strong>).
    </p>

    <p>
        Our staff will contact you soon via the phone number you provided:
        <strong>{{ $phone }}</strong>.
    </p>

    <p>
        If you have any questions, feel free to reply to this email.
    </p>

    <br>
    <p>Best regards,</p>
    <p><strong>InsuranceWise Team</strong></p>
</body>
</html>
