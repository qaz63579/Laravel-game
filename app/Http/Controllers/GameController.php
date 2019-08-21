<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;

class GameController extends Controller
{
    public function index(){
        if (Session::has('UserName')){
            Session::forget('UserName');
        } else {
            exit();
        }
        return view("home.index");
    }

    public function login(Request $Request){
        $UserName = $Request->UserName;
        $PassWord = $Request->PassWord;
        $GameRepository = new \App\Http\Repositories\GameRepository();
        $Login = $GameRepository->login($UserName, $PassWord);

        if ($Login == 1) {
            Session::put('UserName', $UserName);
            return redirect('/index/main');
        }
    }

    public function main(){
        $UserName = Session::get('UserName');
        $GameRepository = new \App\Http\Repositories\GameRepository();
        $GameLists = $GameRepository->gamelist();
        return view("home.main", compact('GameLists', 'UserName'));
    }

    public function pay(){
        return view("home.pay");
    }
    
    public function postpay(Request $Request){
        $UserName = Session::get('UserName');
        $million = $Request->million;
        $thousand = $Request->thousand;
        $hundred = $Request->hundred;
        $ten = $Request->ten;
        $one = $Request->one;
        $code = $million.'|'.$thousand.'|'.$hundred.'|'.$ten.'|'.$one;
        $money = $Request->money;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://bank:9090/search?name=$UserName");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $temp = curl_exec($ch);
        curl_close($ch);
        $isTrue = json_decode($temp, true);

        if ($isTrue['money'] >= $money) {
            $dt = Carbon::now()->toTimeString();
            $dt = str_replace( ':', '', $dt );
            $GameRepository = new \App\Http\Repositories\GameRepository();
            $GameLists = $GameRepository->gamelist();
            $GameListsCount = $GameRepository->gamelistcount();
            $i = 0;

            while ($i < $GameListsCount-1){
                $OpenTime = $GameLists[$i]['opentime'];
                $CloseTime = $GameLists[$i]['closetime'];
                $OpenTime = str_replace( ':', '', $OpenTime );
                $CloseTime = str_replace( ':', '', $CloseTime );

                if ($dt >= $OpenTime and $dt <= $CloseTime){
                    $Addissue = $GameLists[$i]['issue'];
                }
                $i++;
            }
            $AddBetList = $GameRepository->addbetlist($UserName, $Addissue, $code, $money);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://bank:9090/out?name=$UserName&money=$money");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $temp = curl_exec($ch);
            curl_close($ch);
            echo '下注完成,扣款成功';
        } else {
            echo '餘額不足,扣款失敗';
        }
    }

    public function result(){
        return view("home.result");
    }
}
