<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;
use App\Http\Repositories\GameRepository;
use Datetime;

class GameController extends Controller
{
    public function index()
    {
        if (Session::has('UserName')) {
            Session::forget('UserName');
            return view("home.index");
        } else {
            return view("home.index");
        }
    }

    public function login(Request $Request)
    {
        $UserName = $Request->UserName;
        $PassWord = $Request->PassWord;
        $GameRepository = new \App\Http\Repositories\GameRepository();
        $Login = $GameRepository->login($UserName, $PassWord);

        if ($Login == 1) {
            Session::put('UserName', $UserName);
            return redirect('/index/main');
        }
    }

    public function main()
    {
        $UserName = Session::get('UserName');
        $GameRepository = new \App\Http\Repositories\GameRepository();
        $ShowBetLists = $GameRepository->showbetlists($UserName);
        return view("home.main", compact('ShowBetLists', 'UserName'));
    }

    public function pay()
    {
        return view("home.pay");
    }

    public function postpay(Request $Request)
    {
        $UserName = Session::get('UserName');
        $million = $Request->million;
        $thousand = $Request->thousand;
        $hundred = $Request->hundred;
        $ten = $Request->ten;
        $one = $Request->one;
        $code = '0' . $million . ',0' . $thousand . ',0' . $hundred . ',0' . $ten . ',0' . $one;
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
        $GameRepository = new GameRepository;
        $TimeTable = $GameRepository->GetTimeTable();
        $now = DateTime::createFromFormat('H:i:s', $dt->toTimeString());
        //$issue;


        foreach ($TimeTable as $key => $value) { //取得目前期數
            $opentime = DateTime::createFromFormat('H:i:s', $value['opentime']);
            $closetime = DateTime::createFromFormat('H:i:s', $value['closetime']);
            if ($now >= $opentime && $now <= $closetime) {
                $issue = $value['issue_num'];
            }
        }
        $str = str_replace('-', '', $dt->toDateString());
        $issue = $str . '-' . $issue;


        $GameRepository->InsertBetlist($UserName, $issue, $code, $money); //新增下注資料


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:9090/out?name=$UserName&money=$money");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $temp = curl_exec($ch);
        curl_close($ch);
        echo '下注完成,扣款成功';

        return redirect('/index/main');






        return $issue;
    }

    public function result()
    {
        $UserName = Session::get('UserName');

        $GameRepository = new \App\Http\Repositories\GameRepository();
        $ShowBetLists = $GameRepository->showbetlists($UserName);
        $ShowBetListsCount = $GameRepository->showbetlistscount($UserName);

        

        return view('home.result', compact('ShowBetLists', 'UserName'));
    }


    public function info()
    {
        echo phpinfo();
        return "";
    }

    

}
