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
        <p>結果：{{ $ShowBetList->winmoney }} 淨獲利：{{ $ShowBetList->getmoney }} 入賬：{{ $ShowBetList->close }}</p>
        <hr>
     
    @endforeach
</body>
</html>