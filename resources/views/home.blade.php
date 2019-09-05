@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Hi !  {{ Auth::user()->name }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    目前金額 : {{$mymoney}}<p>
                    <a  class="btn btn-primary" href="/index/pay">下注</a>
                    <a  class="btn btn-primary" href="/main">查詢注單(byUser)</a>
                    <a  class="btn btn-primary" href="/main">查詢注單(Admin)</a>
                    <a  class="btn btn-primary" href="/search/code?date={{$date}}">賽果查詢</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
