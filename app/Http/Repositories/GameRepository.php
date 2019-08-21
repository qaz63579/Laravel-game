<?php
namespace App\Http\Repositories;

use Illuminate\Support\Facades\DB;
use App\Betlist;
use App\Menber;
use App\Gamelist;
use Carbon\Carbon;

class GameRepository
{
    public $betlist;
    public $menber;
    public $gamelist;

    public function login($UserName, $PassWord){
        $this->menber = new Menber;
        $login = $this->menber->where('username', $UserName)->where('password', $PassWord)->count();
        return $login;
    }

    public function gamelist(){
        $this->gamelist = new Gamelist;
        $gamelist = $this->gamelist->get();
        return $gamelist;
    }

    public function paymoney($number, $money){
        $dt = Carbon::now();
        $this->betlist = new Betlist;
        $this->betlist->insert(array('name' => "david", 'issue' => '20190816001', 'code' => "{$number}", 'money' => "{$money}", 'time' => "{$dt}"));
    }
}