<?php
namespace App\Services;

use App\Http\Repositories\BetRepository;
use  App\Http\Repositories\GameRepository;

class RecommandService  
{
    protected $BetRepo;
    protected $GameRepo;

    public function __construct()
    {
        $this->BetRepo = new BetRepository;
        $this->GameRepo = new GameRepository;
    }

    public function GetTest()
    {
        return $this->BetRepo->GetSomethin();
    }
}
