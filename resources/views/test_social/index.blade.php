<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Test Social</title>
</head>
<body>
    <a href="{{ route('auth.social', ['provider' => 'google']) }}">Đăng nhập gmail</a>
    <br>
    <a href="{{ route('auth.social', ['provider' => 'facebook']) }}">Đăng nhập facebook</a>
</body>
</html>