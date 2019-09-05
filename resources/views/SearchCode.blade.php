@extends('layouts.app')

@section('javascript')
    <script>
      function OnChange() {
        var select = document.getElementById("date").value;
        window.location.replace("/search/code?date="+select);
      }
    </script>
@endsection

@section('content')
<p id="t"></p>
<div class="container">
    <form>
      <div class="form-group">
        <label for="usr">Date:</label>
        <input type="date" class="form-control" id="date" value="{{$date}}" onchange="OnChange()">
      </div>
    </form>
    <h2>期號賽果</h2>
    <p>The .table-bordered class adds borders to a table:</p>            
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>期號</th>
          <th>賽果</th>
          <th>開盤時間</th>
          <th>關盤時間</th>
          {{-- <th>狀態</th> --}}
        </tr>
      </thead>
      <tbody>
        @foreach ($data_arr as $item)
        <tr>
          <td>{{$item->issue}}</td>
          <td>{{$item->code}}</td>
          <td>{{$item->opentime}}</td>
          <td>{{$item->closetime}}</td>
          {{-- <td>{{$item->status}}</td> --}}
        </tr>
        @endforeach
      </tbody>
    </table>
</div>


@endsection