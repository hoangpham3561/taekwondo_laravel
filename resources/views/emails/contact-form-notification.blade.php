<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Contact Form Notification</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.5; color: #222;">
    <h2>New contact form submission</h2>
    <p>You have received a new message from the website contact form.</p>

    <p><strong>Full name:</strong> {{ $payload['name'] }}</p>
    <p><strong>Email:</strong> {{ $payload['email'] }}</p>
    <p><strong>Phone:</strong> {{ $payload['phone'] }}</p>
    <p><strong>Subject:</strong> {{ $payload['subject'] }}</p>
    <p><strong>Message:</strong></p>
    <p>{{ $payload['message'] }}</p>
</body>
</html>
