<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pay</title>
</head>
<body>
    <form method="POST" action="/index/pay">
        @csrf
        萬位：<input type="text" name="million"/><br>
        千位：<input type="text" name="thousand"/><br>
        百位：<input type="text" name="hundred"/><br>
        十位：<input type="text" name="ten"/><br>
        個位：<input type="text" name="one"/><br>
        <hr>
		下注：<input type="text" name="money"/>
        <input type="submit" value="付款"/>	
        <a href="/index/main "><label for="male">回首頁</a>
	</form>
</body>
</html>