<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <title>Main</title>
</head>
<body>
    Welcome {{ $UserName }} !<br>
    <a href="/index/pay"><label for="male">去下注</a>
    <a href="/index/result"><label for="male">看結果</a>
    <a href="/index"><label for="male">登出</a>
    <hr>
    @foreach ($ShowBetLists as $ShowBetList) 
        <p>期數：{{ $ShowBetList->issue }}<br>
        注單號碼：{{ $ShowBetList->code }}<br>
        下注金額：{{ $ShowBetList->money }}<br>
        結算時間：{{ $ShowBetList->closetime }}</p>
        <hr>
    @endforeach
</body>
</html>