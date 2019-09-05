@extends('layouts.app')

@section('javascript')
    <script>

    </script>
@endsection

@section('content')
<p id="t"></p>
<div class="container">
    <form method="POST" action="/search/admin">
        @csrf
      <div class="form-group">
        <label for="id">ID:</label>
        <input type="text" class="form-control" id="id" name="id" value="">
        <label for="issue">期號:</label>
        <input type="text" class="form-control" id="issue" name="issue" value="">
        <p></p>
        <label for="status">派彩</label>
        <select name="status">
            <option value=""></option>
            <option value="3">是</option>
            <option value="2">否</option>
          </select>
      </div>
      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    <h2>注單查詢</h2>
    <p></p>            
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>編號</th>
          <th>期數</th>
          <th>下單號碼</th>
          <th>派彩</th>
          {{-- <th>狀態</th> --}}
        </tr>
      </thead>
      <tbody>
        @foreach ($data_arr as $item)
        <tr>
          <td>{{$item->id}}</td>
          <td>{{$item->issue}}</td>
          <td>{{$item->code}}</td>
          <td>{{$item->status}}</td>
          {{-- <td>{{$item->status}}</td> --}}
        </tr>
        @endforeach
      </tbody>
    </table>
</div>


@endsection