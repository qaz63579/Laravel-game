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

    public function gamelistcount(){
        $this->gamelist = new Gamelist;
        $gamelistcount = $this->gamelist->count();
        return $gamelistcount;
    }

    public function addbetlist($UserName, $Addissue, $code, $money){
        $dt = Carbon::now();
        $this->betlist = new Betlist;
        $this->betlist->insert(array('name' => "{$UserName}", 'issue' => "{$Addissue}", 'code' => "{$code}", 'money' => "{$money}", 'time' => "{$dt}"));
    }

}