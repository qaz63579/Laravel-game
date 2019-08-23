<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Result</title>
</head>
<body>
    <a href="/index/main"><label for="male">回主頁</a>
    <hr>
    @foreach ($ShowBetLists as $ShowBetList) 
        <p>期數：{{ $ShowBetList->issue }} 賽果：{{ $ShowBetList->code }} 金額：{{ $ShowBetList->money }} 結算時間：{{ $ShowBetList->closetime }}</p>
        <p>輸贏金額：{{ $ShowBetList->getmoney }} 結算：{{ $ShowBetList->close }} 派彩：{{ $ShowBetList->gift }}</p>
        <hr>
    @endforeach
</body>
</html>