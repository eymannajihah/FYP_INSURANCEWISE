<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quote Assigned</title>
</head>
<body>
    <h2>Hello {{ $name }},</h2>

    <p>Your insurance quote request has been assigned to our staff member:</p>

    <ul>
        <li><strong>Staff Name:</strong> {{ $assignedTo }}</li>
        <li><strong>Phone:</strong> {{ $phone }}</li>
    </ul>

    <p>Please expect them to contact you shortly.</p>

    <p>Thank you for using our service!</p>
</body>
</html>
