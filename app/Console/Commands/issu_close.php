<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Datetime;

class issu_close extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:close';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'close issue when times up ';

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
        $BetRepo = new \App\Http\Repositories\BetRepository();

        $TimeTable = $GameRepository->GetTimeTable();
        $today = date('Ymd');
        $now = date('H:i:s');

        foreach ($TimeTable as $key => $value) {
            if (DateTime::createFromFormat('H:i:s', $now) > DateTime::createFromFormat('H:i:s', $value['closetime']))
                $issue = $today . '-' . $value['issue_num'];
                $BetRepo->UpdateBetlistColseByIssue($issue);
                $GameRepository->UpdateGamelistColseByIssue($issue);
        }
    }
}
