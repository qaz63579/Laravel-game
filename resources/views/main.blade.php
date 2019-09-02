@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Card Header and Footer</h2>
    <div class="row">
        @foreach ($ShowBetLists as $item)
        <div class="col-6 col-md-4 border bg-light">
            <div class="card">
                <div class="card-header">
                    期號 : {{$item->issue}}
                </div>
                <div class="card-body">
                    <p>下注號碼:{{$item->code}}</p>
                    <p>下注金額: {{$item->money}}</p>
                    <p>賠率: {{$item->odds}}</p>
                    <p>注單狀態: {{$item->status}}</p>
                    <p>結算時間: {{$item->closetime}}</p>
                    <p>中獎金額: {{$item->getmoney}}</p>
                </div> 
                <div class="card-footer">
                    
                    @if ($item->status == 2)
                        輸贏金額 : -{{$item->money}}
                    @else
                        輸贏金額 : {{$item->getmoney}}
                    @endif
                
                </div>
            </div>
        </div>
            
        @endforeach
    </div>
  </div>
@endsection