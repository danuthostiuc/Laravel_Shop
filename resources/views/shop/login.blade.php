<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
<h1>
    {{ trans("Log In") }}
</h1>
<form method="post" action="/products">
    @csrf
    <input type="text" name="username" value="{{ old('username') }}" placeholder="{{ trans("Username") }}" required>
    <br>
    <input type="password" name="password" value="{{ old('password') }}" placeholder="{{ trans("Password") }}" required>
    <br>
    <input type="submit" name="submit" value="{{ trans("Login") }}">
</form>
</body>
</html>
