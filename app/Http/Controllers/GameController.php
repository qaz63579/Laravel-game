<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index(){
        return view("home.index");
    }
    
    public function pay(Request $Request){
        $number = $Request->number;
        $money = $Request->money;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://mylaravel:9090/search?name=david");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $temp = curl_exec($ch);
        curl_close($ch);
        $istrue = json_decode($temp, true);
        dd($istrue['money']);

        /*if ($istrue['money'] >= 0) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "http://mylaravel:9090/out?name=david&money=$money");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $temp = curl_exec($ch);
            curl_close($ch);
            $pay = json_decode($temp, true);
            $GameRepository = new \App\Http\Repositories\GameRepository();
            $paymoney = $GameRepository->paymoney($number, $money);
            dd($pay['money']);
        } else {
            exit();
        }*/
    }
}
