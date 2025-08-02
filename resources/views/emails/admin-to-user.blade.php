<!DOCTYPE html>
<html>

<head>
    <title>{{ $subject }}</title>
</head>

<body>
    <h1>Hello {{ $user->name }},</h1>

    <div>{!! nl2br(e($subject)) !!}</div>

    <div>{!! nl2br(e($messageContent)) !!}</div>

    <p>Best regards,</p>
</body>

</html>
