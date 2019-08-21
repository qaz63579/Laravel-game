<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form method="POST" action="/index/pay">
        @csrf
        號碼：<input type="text" name="number"/><br>
		下注：<input type="text" name="money"/><br>
		<input type="submit" value="付款"/>
	</form>
</body>
</html>