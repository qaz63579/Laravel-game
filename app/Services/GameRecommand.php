<?php

namespace App\Services;

use App\Http\Repositories\BetRepository;
use  App\Http\Repositories\GameRepository;

class GameRecommand
{
    protected $BetRepo;
    protected $GameRepo;

    function __construct()
    {
        $this->BetRepo = new BetRepository;
        $this->GameRepo = new GameRepository;
    }

    public function Get_code_postpay($Request)
    {
        $million = $Request->million;
        $thousand = $Request->thousand;
        $hundred = $Request->hundred;
        $ten = $Request->ten;
        $one = $Request->one;
        $code = '0' . $million . ',0' . $thousand . ',0' . $hundred . ',0' . $ten . ',0' . $one;
        return $code;
    }

    public function Data_For_main($UserName)
    {
        $ShowBetLists = $this->GameRepo->showbetlists($UserName);
        return $ShowBetLists;
    }

    public function Data_For_SearchCode($Request)
    {
        $data_arr = $this->GameRepo->GetListByDate(str_replace('-', '', $Request->date));
        return $data_arr;
    }

    public function Data_For_SearchAdmnPost($Request)
    {
        $input = $Request->all();
        $data_arr = $this->GameRepo->GetListByIssue_ID_Stasus($input['issue'], $input['id'], $input['status']);
        return $data_arr;
    }
}
