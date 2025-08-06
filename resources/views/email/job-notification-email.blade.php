<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Job Notification Email</title>
</head>

<body>
    <h1>Hello {{ $mailInfo['employer']->name }}</h1>
    <p>Job Title: {{ $mailInfo['job']->title }}</p>

    <p>Employee Detail:</p>

    <p>Name: {{ $mailInfo['user']->name }}</p>
    <p>Email: {{ $mailInfo['user']->email }}</p>
    <p>Mobile No: {{ $mailInfo['user']->mobile }}</p>
</body>

</html>
