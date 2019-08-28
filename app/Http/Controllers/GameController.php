<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use App\Http\Repositories\GameRepository;
use Datetime;

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
        $code = '0'.$million.',0'.$thousand.',0'.$hundred.',0'.$ten.',0'.$one;
        $money = $Request->money;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:9090/search?name=$UserName");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $temp = curl_exec($ch);
        curl_close($ch);
        $isTrue = json_decode($temp, true);


        if ($isTrue['money'] < $money){
            return '餘額不足,扣款失敗';
        }


        $dt = Carbon::now();
        $GameRepository = new GameRepository;
        $TimeTable = $GameRepository->GetTimeTable();
        $now =DateTime::createFromFormat('H:i:s',$dt->toTimeString());
        $issue;


        foreach ($TimeTable as $key => $value) { //取得目前期數
            $opentime = DateTime::createFromFormat('H:i:s',$value['opentime']);
            $closetime =DateTime::createFromFormat('H:i:s',$value['closetime']);
            if($now>=$opentime && $now <= $closetime ){
                $issue = $value['issue_num'];
            }
        }
        $str = str_replace('-','',$dt->toDateString());
        $issue = $str .'-' . $issue;        
        $GameRepository->InsertBetlist($UserName,$issue,$code,$money); //新增下注資料


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:9090/out?name=$UserName&money=$money");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $temp = curl_exec($ch);
        curl_close($ch);
        echo '下注完成,扣款成功';

        return redirect('/index/main');



        


        return $issue;

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
    public function info()
    {
        echo phpinfo();
        return "";
    }

    // public function server(){
        
    //     $data = file_get_contents('http://www.tjflcpw.com/report/ssc_jiben_report.aspx?term_num=100');
    //     preg_match_all('/["][0-9\W]{29}["]/', $data, $output_array);

    //     $str = $output_array[0]; // $str[0] 格式為:"20190823009", "02|05|01|07|09"


    //     for($i=0 ; $i<sizeof($str) ; $i++ )
    //     {
    //         $str2 = preg_replace('/[2][0-9]{7}/', '$0-', $str[$i]); // str2 = "20190823-009", "02|05|01|07|09"
    //         preg_match('/[2][0-9\W]{11}/', $str2, $str3); // $str3[0] = "20190823-008"
    //         preg_match('/([0-9]{2}[|]){4}[0-9]{2}/', $str2, $str4); // $str4[0] = "02|05|01|07|09"
    //         $str4[0]=str_replace("|",",",$str4[0]);
    //         $data_arr[$str3[0]]=$str4[0];
    //     }

    //     foreach ($data_arr as $issue => $code) {
    //         $GameRepository = new \App\Http\Repositories\GameRepository();
    //         try {
    //             $GameRepository->insertGameList($issue,$code);
    //         } catch (\Throwable $th) {

    //         }
            
    //     }


    //     return $data_arr;
        

        
    // }
}