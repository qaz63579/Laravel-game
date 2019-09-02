<?php

namespace App\Http\Repositories;

use Illuminate\Support\Facades\DB;
use App\Betlist;
use App\Menber;
use App\Gamelist;
use App\time;
use Carbon\Carbon;

class GameRepository
{
    public $betlist;
    public $menber;
    public $gamelist;

    public function login($UserName, $PassWord)
    {
        $this->menber = new Menber;
        $login = $this->menber->where('username', $UserName)->where('password', $PassWord)->count();
        return $login;
    }

    public function gamelist()
    {
        $this->gamelist = new Gamelist;
        $gamelist = $this->gamelist->get();
        return $gamelist;
    }

    public function gamelistcount()
    {
        $this->gamelist = new Gamelist;
        $gamelistcount = $this->gamelist->count();
        return $gamelistcount;
    }

    public function addbetlist($UserName, $Addissue, $code, $money)
    {
        $dt = Carbon::now();
        $this->betlist = new Betlist;
        $this->betlist->insert(array('name' => "{$UserName}", 'issue' => "{$Addissue}", 'code' => "{$code}", 'money' => "{$money}", 'time' => "{$dt}", 'getmoney' => '---', 'close' => 'No', 'gift' => 'No'));
    }

    public function showbetlists($UserName)
    {
        $this->betlist = new Betlist;
        $ShowBetLists = $this->betlist->select()
                                      ->where('betlist.name', $UserName)
                                      ->orderBy('id', 'desc')
                                      ->get();
        return $ShowBetLists;
    }

    public function showbetlistscount($UserName)
    {
        $this->betlist = new Betlist;
        $ShowBetListscount = $this->betlist->join('gamelist', 'betlist.issue', '=', 'gamelist.issue')
            ->select('betlist.id', 'betlist.issue', 'betlist.code', 'betlist.money', 'gamelist.closetime', 'betlist.getmoney', 'betlist.close', 'betlist.gift')
            ->where('betlist.name', $UserName)
            ->count();
        return $ShowBetListscount;
    }

    public function gamecode($BetIssue)
    {
        $this->gamelist = new Gamelist;
        $gamecode = $this->gamelist->select('code')->where('issue', $BetIssue)->get();
        return $gamecode;
    }

    public function updatebetlist($BetId, $GetMoney, $UserName)
    {
        $this->betlist = new Betlist;
        $this->betlist->where('id', "{$BetId}")->where('name', "{$UserName}")->update(array('getmoney' => "{$GetMoney}", 'close' => "ok", 'gift' => 'ok'));
    }

    public function addgamelist($DataIssie, $DataCode, $OpenTime, $CloseTime)
    {
        $this->gamelist = new Gamelist;
        $this->gamelist->insert(array('issue' => "{$DataIssie}", 'code' => "{$DataCode}", 'opentime' => "{$OpenTime}", 'closetime' => "{$CloseTime}"));
    }

    public function cleargamelist()
    {
        $this->gamelist = new Gamelist;
        $this->gamelist->truncate();
    }

    public function insertGameList($issue, $code)
    {
        $insert = new Gamelist;
        $insert->issue = $issue;
        $insert->code = $code;
        $insert->save();
    }

    public function GetNewestIssue()
    {
        $getIssue = new Gamelist;
        $data = $getIssue->select('issue')->orderBy('issue', 'DESC')->limit(1)->get();
        return $data;
    }

    public function GetTimeTable()
    {
        $getTable = new time;
        $data = $getTable->select('issue_num', 'opentime', 'closetime')->orderBy('issue_num', 'ASC')->get();
        return $data;
    }

    public function InsertBetlist($name, $issue, $code, $money, $odds)
    {
        $insert = new Betlist;
        $insert->name = $name;
        $insert->issue = $issue;
        $insert->odds = $odds;
        $insert->code = $code;
        $insert->money = $money;
        $insert->getmoney = $money * $odds;
        $insert->status = 0;
        $insert->save();
    }

    public function InsertDayIssue($issue, $opentime, $closetime)
    {
        $insert = new Gamelist;
        $insert->issue = $issue;
        $insert->opentime = $opentime;
        $insert->closetime = $closetime;
        $insert->status = 0 ;
        $insert->save();
    }

    public function UpdateCode($issue, $code)
    {
        $update = new Gamelist;
        $update->select('gamelist')
            ->where('issue', $issue)
            ->update(['code' => $code]);
    }

    public function GetGift_NO()
    {
        $GetGift = new Betlist;
        $data = $GetGift->select()
            ->where('gift', 'No')
            ->where('close', 'Yes')
            ->get();
        return $data;
    }
    public function GetCodeByIssue($issue)
    {
        $GetCode = new Gamelist;
        $code = $GetCode->select('code')
            ->where('issue', $issue)
            ->get();
        return $code;
    }
    public function UpdateBetlistGetMoney($id, $GetMoney)
    {
        $UpdateGetMoney = new Betlist;
        $UpdateGetMoney->select('betlist')
            ->where('id', $id)
            ->update(['getmoney' => $GetMoney, 'gift' => 'Yes']);
    }

    public function UpdateGamelistColseByIssue($issue)
    {
        $update = new Gamelist;
        $update->select()
               ->where('issue',$issue)
               ->where('status',0)
               ->update(['status'=>1]);
    }
    
}
