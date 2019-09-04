<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DateTime;

class loop extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:loop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Loop for count result & issue close & update code';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        while (true) {
            $this->update_code();
            $this->count_result();
            $this->issue_close();
            sleep(60);
        }

        echo "this is test \n\r";
    }

    public function update_code()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://www.tjflcpw.com/report/ssc_jiben_report.aspx?term_num=100");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);


        // $data = file_get_contents('http://www.tjflcpw.com/report/ssc_jiben_report.aspx?term_num=100');
        preg_match_all('/["][0-9\W]{29}["]/', $data, $output_array);
        $str = $output_array[0]; // $str[0] 格式為:"20190823009", "02|05|01|07|09"


        $data_arr = array();
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

    public function count_result()
    {
        $GameRepository = new \App\Http\Repositories\GameRepository();
        $BetRepo = new \App\Http\Repositories\BetRepository();
        $data = $BetRepo->GetStatus_1(); // should be status=1

        foreach ($data as $key) {
            $code = $GameRepository->GetCodeByIssue($key['issue']);

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

            $getmoney = 0;

            if ($count == $key['odds']) { // 玩法 = 命中數 => 派獎
                switch ($count) { //計算中獎金額
                    case 3:
                        // echo '命中3個' . '<p>';
                        $getmoney = $key['getmoney'];
                        break;
                    case 4:
                        // echo '命中4個' . '<p>';
                        $getmoney = $key['getmoney'];
                        break;
                    case 5:
                        // echo '命中5個' . '<p>';
                        $getmoney = $key['getmoney'];
                        break;
                }
                //update status =3
                $BetRepo->UpdateStatus_3($key['id']);
            } else {
                //update status =2
                $BetRepo->UpdateStatus_2($key['id']);
            }


            // $GameRepository->UpdateBetlistGetMoney($key['id'], $getmoney); //更新中獎金額

            if ($getmoney > 0) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "http://127.0.0.1:9090/insert?name=" . $key['name'] . "&money=$getmoney"); //派獎
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $temp = curl_exec($ch);
                curl_close($ch);
                echo $key['name'] . '得到' . $getmoney . "\r\n";
            }
        }
    }
    public function issue_close()
    {
        $GameRepository = new \App\Http\Repositories\GameRepository();
        $BetRepo = new \App\Http\Repositories\BetRepository();

        $TimeTable = $GameRepository->GetTimeTable();
        $today = date('Ymd');
        $now = date('H:i:s');

        foreach ($TimeTable as $key => $value) {
            if (DateTime::createFromFormat('H:i:s', $now) > DateTime::createFromFormat('H:i:s', $value['closetime'])) {
                $issue = $today . '-' . $value['issue_num'];
                $BetRepo->UpdateBetlistColseByIssue($issue);
                $GameRepository->UpdateGamelistColseByIssue($issue);
            }
        }
    }
}
