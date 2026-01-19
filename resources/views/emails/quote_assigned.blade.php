<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quote Request Assigned</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">
    <h2>Hello {{ $name }},</h2>

    <p>Your insurance quote request has been assigned to our staff member:</p>

    <p><strong>{{ $assignedTo }}</strong></p>

    <p>They will contact you soon via your phone number: <strong>{{ $phone }}</strong></p>

    <p>If you have any questions, reply to this email.</p>

    <p>Best regards,</p>
    <p><strong>InsuranceWise Team</strong></p>
</body>
</html>
