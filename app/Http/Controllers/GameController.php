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
            return view("home.index");
        } else {
            return view("home.index");
        }
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
        $ShowBetLists = $GameRepository->showbetlists($UserName);
        return view("home.main", compact('ShowBetLists', 'UserName'));
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
        $code = $million.','.$thousand.','.$hundred.','.$ten.','.$one;
        $money = $Request->money;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://bank:9090/search?name=$UserName");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $temp = curl_exec($ch);
        curl_close($ch);
        $isTrue = json_decode($temp, true);
        $Addissue = '';

        if ($isTrue['money'] >= $money) {
            $dt = Carbon::now()->toTimeString();
            $dt = str_replace( ':', '', $dt );
            $GameRepository = new \App\Http\Repositories\GameRepository();
            $GameLists = $GameRepository->gamelist();
            $GameListsCount = $GameRepository->gamelistcount();
            $i = 0;

            while ($i < $GameListsCount){
                $OpenTime = $GameLists[$i]['opentime'];
                $CloseTime = $GameLists[$i]['closetime'];
                $OpenTime = str_replace( ':', '', $OpenTime );
                $CloseTime = str_replace( ':', '', $CloseTime );

                if ($dt > $OpenTime and $dt < $CloseTime){
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
            return redirect('/index/main');
        } else {
            echo '餘額不足,扣款失敗';
        }
    }

    public function result(){
        $UserName = Session::get('UserName');
        $dt = Carbon::now()->toTimeString();
        $dt = str_replace( ':', '', $dt );
        $GameRepository = new \App\Http\Repositories\GameRepository();
        $ShowBetLists = $GameRepository->showbetlists($UserName);
        $ShowBetListsCount = $GameRepository->showbetlistscount($UserName);
        $i = 0;    
        
        while ($i < $ShowBetListsCount){
            $CloseTime = $ShowBetLists[$i]['closetime'];
            $CloseTime = str_replace( ':', '', $CloseTime );
            
            if ($dt > $CloseTime) {
                $BetCode = $ShowBetLists[$i]['code'];
                $BetCode_exp = explode(',', $BetCode);
                $BetIssue = $ShowBetLists[$i]['issue'];
                $GameCode = $GameRepository->gamecode($BetIssue);
                $GameCode = $GameCode[0]['code'];
                $GameCode_exp = explode('|', $GameCode);
                $BetId = $ShowBetLists[$i]['id'];
                $j = 0;
                $win = 0; 

                while ($j < 5) {
                
                    if ($BetCode_exp[$j] == $GameCode_exp[$j]){
                        $win = $win + 1;
                        $j = $j + 1;
                    } else {
                        $j = $j + 1;
                    }
                }
                
                $GetMoney = $ShowBetLists[$i]['money'] * $win * 2;
                $GetMoney = $GetMoney - $ShowBetLists[$i]['money'];
                
                if($ShowBetLists[$i]['close'] == 'No'){
                    $GameRepository->updatebetlist($BetId, $GetMoney, $UserName);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "http://bank:9090/insert?name=$UserName&money=$GetMoney");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $temp = curl_exec($ch);
                    curl_close($ch);
                }
            }

            $i++;
            
        }
        return view('home.result', compact('ShowBetLists', 'UserName'));
    }

    public function server(){
        $GameRepository = new \App\Http\Repositories\GameRepository();
        $GameRepository->cleargamelist();
        $dt = Carbon::now()->toDateString();
        $dt = str_replace( '-', '', $dt );
        $OpenTime = '09:20:00';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://curlapi:9092/regextest.php");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $temp = curl_exec($ch);
        curl_close($ch); 
        $temp = json_decode($temp, true);
        $i = count($temp) - 1;

        while ($i > -1){

            $DataIssue = strstr($temp[$i],',',true);
            $DataIssue = substr($DataIssue, 0, 11);

            if (strpos($DataIssue, '0823')) {
                $DataCode = strstr($temp[$i], ',');
                $DataCode = substr($DataCode, 2, 14);
                $OpenTime = str_replace( ':', '', $OpenTime );
                $CloseTime = $OpenTime + 1959;
                
                if (strlen($OpenTime) < 8) {
                    $OpenTime = substr($OpenTime, 0, 2).':'.substr($OpenTime, 2, 2).':'.substr($OpenTime, 4, 2);
                }

                if (strlen($CloseTime) < 6) {
                    $CloseTime = '0'.$CloseTime;
                }

                $CloseTime = substr($CloseTime, 0, 2).':'.substr($CloseTime, 2, 2).':'.substr($CloseTime, 4, 2);
                $AddGameList = $GameRepository->addgamelist($DataIssue, $DataCode, $OpenTime, $CloseTime);
                $OpenTime = str_replace( ':', '', $OpenTime );
                $CloseTime = str_replace( ':', '', $CloseTime );
                $OpenTime = $CloseTime + 41;

                if (strlen($OpenTime) < 6){
                    $OpenTime = '0'.$OpenTime;
                }

                if ($OpenTime >= substr($OpenTime, 0, 2).'6000') {
                    $OpenTime = $OpenTime + 4000;
                }
            } 
  
            $i = $i - 1;
        }
        
    }
}