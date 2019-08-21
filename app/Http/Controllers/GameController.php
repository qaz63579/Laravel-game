<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class GameController extends Controller
{
    public function index(){
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
        $number = $Request->number;
        $money = $Request->money;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://bank:9090/search?name=david");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $temp = curl_exec($ch);
        curl_close($ch);
        $istrue = json_decode($temp, true);

        if ($istrue['money'] >= 0) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://bank:9090/out?name=david&money=$money");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $temp = curl_exec($ch);
            curl_close($ch);
            $pay = json_decode($temp, true);
            $GameRepository = new \App\Http\Repositories\GameRepository();
            $paymoney = $GameRepository->paymoney($number, $money);
            dd($pay['money']);
        } else {
            exit();
        }
    }
}
