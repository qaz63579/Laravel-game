<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DayIssue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:dayissue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'New all day issue';

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
        $TimeTable = $GameRepository->GetTimeTable();
        $today = date('Ymd');
        for ($i = 1; $i < 43; $i++) {
            $x = $i - 1;
            if (intval($TimeTable[$x]['issue_num']) == $i) {
                $issue = $today . '-' . $TimeTable[$x]['issue_num'];
                $opentime = $TimeTable[$x]['opentime'];
                $closetime = $TimeTable[$x]['closetime'];
                try {
                    $GameRepository->InsertDayIssue($issue, $opentime, $closetime);
                } catch (\Throwable $th) {
                    //throw $th;
                }
                
            }
        }
        return;
    }
}
