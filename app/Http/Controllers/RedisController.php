<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Datetime;

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
}
