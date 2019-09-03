<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class count_result extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:ResultCount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Count get money and gift money';

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
}
