<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
<body>
    <form method="POST" action="/index/login">
    @csrf
        帳號：<input type="text" name="UserName"/><br>
        密碼：<input type="text" name="PassWord"/><br>
        <input type="submit" value="登入"/>	
    </form>
</body>
</html>