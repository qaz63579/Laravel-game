<?php

namespace App\Http\Controllers;

use App\Events\UserPostBetlist;
use App\Events\UserPostBetlistException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use Carbon\Carbon;
use App\Http\Repositories\GameRepository;
use App\Services\GameRecommand;
use Illuminate\Support\Facades\Redis;
use Datetime;
use Exception;


class GameController extends Controller
{
    protected $GameReco;

    function __construct()
    {
        $this->GameReco = new GameRecommand;
    }
    public function main()
    {
        if (Auth::check()) {
            $UserName = Auth::user()->name;
            $ShowBetLists = $this->GameReco->Data_For_main($UserName);
            return view("main", compact('ShowBetLists', 'UserName'));
        } else {
            return view("home");
        }
    }
    public function pay()
    {
        return view("home.pay");
    }

    public function postpay(Request $Request)
    {

        $UserName = Auth::user()->name;
        $odds = $Request->gmae_type; //取得遊戲規則與賠率
        $code = $this->GameReco->Get_code_postpay($Request);
        $money = $Request->money;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:9090/search?name=$UserName");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $temp = curl_exec($ch);
        curl_close($ch);
        $isTrue = json_decode($temp, true);


        if ($isTrue['money'] < $money) {
            return '餘額不足,扣款失敗';
        }


        $dt = Carbon::now();

        $now = DateTime::createFromFormat('H:i:s', $dt->toTimeString());
        //$issue;

        $str = date_format($now, 'H:i:s') . $UserName;
        if (Redis::get($UserName) == md5($str)) {
            return '一秒只能連線一次';
        } else {
            Redis::set($UserName, md5($str));
            Redis::expire($UserName, 3);
        }

        $GameRepository = new GameRepository;
        $TimeTable = $GameRepository->GetTimeTable();
        foreach ($TimeTable as $key => $value) { //取得目前期數
            $opentime = DateTime::createFromFormat('H:i:s', $value['opentime']);
            $closetime = DateTime::createFromFormat('H:i:s', $value['closetime']);
            if ($now >= $opentime && $now <= $closetime) {
                $issue = $value['issue_num'];
                $myclosetime = $closetime;
            }
        }
        $str = str_replace('-', '', $dt->toDateString());
        $issue = $str . '-' . $issue;

        try { //紀錄成功或失敗的資料
            $GameRepository->InsertBetlist($UserName, $issue, $code, $money, $odds, $myclosetime); //新增下注資料
        } catch (Exception $e) {
            event(new UserPostBetlistException($e));
            return 'something error';
        }
        event(new UserPostBetlist($Request));



        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:9090/out?name=$UserName&money=$money");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $temp = curl_exec($ch);
        curl_close($ch);
        echo '下注完成,扣款成功';

        return redirect('/index/main');
    }


    public function SearchCode(Request $Request)
    {
        $data_arr = array();
        $data_arr = $this->GameReco->Data_For_SearchCode($Request);

        return view('SearchCode', ['data_arr' => $data_arr, 'date' => $Request->date]);
    }

    public function SearchAdmin()
    {
        $data_arr = array();
        return view('SearchAdmin', ['data_arr' => $data_arr]);
    }

    public function SearchAdminPost(Request $Request)
    {
        $data_arr = array();

        try {
            $data_arr = $this->GameReco->Data_For_SearchAdmnPost($Request);
        } catch (Exception $e) {
            event(new UserPostBetlistException($e));
            return 'something error';
        }
        event(new UserPostBetlist($Request));

        return view('SearchAdmin', ['data_arr' => $data_arr]);
    }
}
