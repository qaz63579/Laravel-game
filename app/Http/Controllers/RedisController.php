<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Datetime;
use App\Http\Repositories\GameRepository;

class RedisController extends Controller
{
    public function index()
    {

        Redis::set('name', 'Taylor');
        echo Redis::get('name') . '<p>';
        $name = Redis::get('name');





        echo Redis::hget('tt1', $name) . '<p>';


        $dt = Carbon::now();
        $now = DateTime::createFromFormat('H:i:s', $dt->toTimeString());
        $str = date_format($now, 'H:i:s') . $name;
        if (Redis::hget('tt1', $name) == md5($str)) {
            return '一秒只能連線一次';
        } else {
            Redis::hset('tt1', $name, md5($str));
        }







        return '正確解答';
    }

    public function InsertIssue()
    {
        $GameRepository = new GameRepository;
        $TimeTable = $GameRepository->GetTimeTable();
        $today = date('Ymd');
        for ($i = 1; $i < 43; $i++) {
            $x = $i - 1;
            if (intval($TimeTable[$x]['issue_num']) == $i) {
                $issue = $today . '-' . $TimeTable[$x]['issue_num'];
                $opentime = $TimeTable[$x]['opentime'];
                $closetime = $TimeTable[$x]['closetime'];
                $GameRepository->InsertDayIssue($issue, $opentime, $closetime);
            }
        }
        return;
    }

    public function RenewCode()
    {
        $data = file_get_contents('http://www.tjflcpw.com/report/ssc_jiben_report.aspx?term_num=100');
        preg_match_all('/["][0-9\W]{29}["]/', $data, $output_array);
        $str = $output_array[0]; // $str[0] 格式為:"20190823009", "02|05|01|07|09"

        for ($i = 0; $i < sizeof($str); $i++) {
            $str2 = preg_replace('/[2][0-9]{7}/', '$0-', $str[$i]); // str2 = "20190823-009", "02|05|01|07|09"
            preg_match('/[2][0-9\W]{11}/', $str2, $str3); // $str3[0] = "20190823-008"
            preg_match('/([0-9]{2}[|]){4}[0-9]{2}/', $str2, $str4); // $str4[0] = "02|05|01|07|09"
            $str4[0] = str_replace("|", ",", $str4[0]);
            $data_arr[$str3[0]] = $str4[0];
        }

        foreach ($data_arr as $issue => $code) {
            $GameRepository = new \App\Http\Repositories\GameRepository();
            try {
                $GameRepository->UpdateCode($issue, $code);
            } catch (\Throwable $th) { }
        }
    }

    public function result()
    {
        $GameRepository = new \App\Http\Repositories\GameRepository();
        $data = $GameRepository->GetGift_NO();

        foreach ($data as $key) {
            $code = $GameRepository->GetCodeByIssue($key['issue']);
            echo $key;

            //拿取數字陣列
            preg_match_all('/[0][0-9]/', $code[0]['code'], $lottery);
            preg_match_all('/[0][0-9]/', $key['code'], $ans);

            $count = 0;
            //計算命中幾個數字
            for ($i = 0; $i < sizeof($lottery); $i++) {
                for ($x = 0; $x < sizeof($lottery[$i]); $x++) {
                    if ($lottery[$i][$x] == $ans[$i][$x])
                        $count++;
                }
            }

            $getmoney = $key['money'];
            switch ($count) { //計算中獎金額
                case 3:
                    echo '命中3個' . '<p>';
                    $getmoney *= 3;
                    break;
                case 4:
                    echo '命中4個' . '<p>';
                    $getmoney *= 4;
                    break;
                case 5:
                    echo '命中5個' . '<p>';
                    $getmoney *= 5;
                    break;
                default:
                    $getmoney = 0;
                    break;
            }
            if ($getmoney > 0) {
                $GameRepository->UpdateBetlistGetMoney($key['id'], $getmoney); //更新中獎金額

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:9090/insert?name=" . $key['name'] . "&money=$getmoney"); //派獎
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $temp = curl_exec($ch);
                curl_close($ch);
            }
        }
    }

    public function IsClosed()
    {
        $GameRepository = new \App\Http\Repositories\GameRepository();
        $TimeTable = $GameRepository->GetTimeTable();
        $today = date('Ymd');
        $now = date('H:i:s');

        foreach ($TimeTable as $key => $value) {
            if (DateTime::createFromFormat('H:i:s', $now) > DateTime::createFromFormat('H:i:s', $value['closetime']))
                $issue = $today . '-' . $value['issue_num'];
                $GameRepository->UpdateBetlistColseByIssue($issue);
        }
    }
}
