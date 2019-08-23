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
        $this->betlist->insert(array('name' => "{$UserName}", 'issue' => "{$Addissue}", 'code' => "{$code}", 'money' => "{$money}", 'time' => "{$dt}", 'getmoney' => '---', 'close' => 'No', 'gift' => 'No'));
    }

    public function showbetlists($UserName){
        $this->betlist = new Betlist;
        $ShowBetLists = $this->betlist->join('gamelist', 'betlist.issue', '=', 'gamelist.issue')
                                      ->select('betlist.id', 'betlist.issue', 'betlist.code', 'betlist.money', 'gamelist.closetime', 'betlist.getmoney', 'betlist.close', 'betlist.gift')
                                      ->where('betlist.name', $UserName)
                                      ->get();
        return $ShowBetLists;
    }

    public function showbetlistscount($UserName){
        $this->betlist = new Betlist;
        $ShowBetListscount = $this->betlist->join('gamelist', 'betlist.issue', '=', 'gamelist.issue')
                                      ->select('betlist.id', 'betlist.issue', 'betlist.code', 'betlist.money', 'gamelist.closetime', 'betlist.getmoney', 'betlist.close', 'betlist.gift')
                                      ->where('betlist.name', $UserName)
                                      ->count();
        return $ShowBetListscount;
    }

    public function gamecode($BetIssue){
        $this->gamelist = new Gamelist;
        $gamecode = $this->gamelist->select('code')->where('issue', $BetIssue)->get();
        return $gamecode;
    }

    public function updatebetlist($BetId, $GetMoney, $UserName){
        $this->betlist = new Betlist;
        $this->betlist->where('id', "{$BetId}")->where('name', "{$UserName}")->update(array('getmoney' => "{$GetMoney}", 'close' => "ok", 'gift' => 'ok'));
    }

    public function addgamelist($DataIssie, $DataCode, $OpenTime, $CloseTime){
        $this->gamelist = new Gamelist;
        $this->gamelist->insert(array('issue' => "{$DataIssie}", 'code' => "{$DataCode}", 'opentime' => "{$OpenTime}", 'closetime' => "{$CloseTime}"));
    }

    public function cleargamelist(){
        $this->gamelist = new Gamelist;
        $this->gamelist->truncate();
    }
}