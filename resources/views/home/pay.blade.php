
@extends('layouts.app')

@section('content')
<body>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
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
        <a href="/home "><label for="male">回首頁</a>
	                    </form>

                </div>
            </div>
        </div>



</body>
@endsection