<?php
namespace App\Http\Repositories;

use App\Betlist;

class BetRepository
{
    protected $betlist;

    /*
    *    將需要使用的Model通過建構函式例項化
    */
    public function __construct ()
    {
        $this->betlist = new Betlist;
    }
    
    public function GetSomethin()
    {
        return $this->betlist->select()->get();
    }
    public function UpdateBetlistColseByIssue($issue)
    {
        $update = new Betlist;
        $update->select()
               ->where('issue',$issue)
               ->where('status',0)
               ->update(['status'=>1]);
    }
    
    public function GetStatus_1()
    {
        $data = $this->betlist->select()
        ->where('status', 1)
        ->get();
        return $data;
    }
    public function UpdateStatus_3($id)
    {
        $this->betlist->select('betlist')
                      ->where('id',$id)
                      ->update(['status'=>3]);
    }
    public function UpdateStatus_2($id)
    {
        $this->betlist->select('betlist')
                      ->where('id',$id)
                      ->update(['status'=>2]);
    }

    

}