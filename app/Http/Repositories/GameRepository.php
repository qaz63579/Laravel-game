<?php
namespace App\Http\Repositories;

use Illuminate\Support\Facades\DB;
use App\Record;
use Carbon\Carbon;

class GameRepository
{
    public $record;

    public function paymoney($number, $money){
        $dt = Carbon::now();
        $this->record = new Record;
        $this->record->insert(array('name' => "david", 'issue' => '20190816001', 'result' => "{$number}", 'time' => "{$dt}", 'pay' => "{$money}"));
    }
}