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
        <select name="gmae_type">
            　<option value="3">買五中三，賠率3</option>
            　<option value="4">買五中四，賠率4</option>
            　<option value="5">買五中五，賠率5</option>

            </select><p>
        萬位：<input type="text" name="million" pattern="[0-9]" required title="只接受一碼數字"/><br>
        千位：<input type="text" name="thousand"pattern="[0-9]" required title="只接受一碼數字"/><br>
        百位：<input type="text" name="hundred"pattern="[0-9]" required title="只接受一碼數字"/><br>
        十位：<input type="text" name="ten"pattern="[0-9]" required title="只接受一碼數字"/><br>
        個位：<input type="text" name="one"pattern="[0-9]" required title="只接受一碼數字"/><br>
        <hr>
		下注：<input type="text" name="money"/>
        <input type="submit" value="付款"/>	
        <a href="/index/main "><label for="male">回首頁</a>
	</form>
</body>
</html>