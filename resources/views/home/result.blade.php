<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Result</title>
</head>
<body>
    @foreach ($ShowBetLists as $ShowBetList) 
        <p>期數：{{ $ShowBetList->issue }} 賽果：{{ $ShowBetList->code }} 金額：{{ $ShowBetList->money }} 結算時間：{{ $ShowBetList->closetime }}</p>
        狀態：{{ $type }} 總額：{{ $WinMoney }} 獲利：{{ $GetMoney }} 入款：
        <hr>
    @endforeach
</body>
</html>