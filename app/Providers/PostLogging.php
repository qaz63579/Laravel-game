<?php

namespace App\Providers;

use App\Events\UserPostBetlist;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostLogging
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserPostBetlist  $event
     * @return void
     */
    public function handle(UserPostBetlist $event)
    {
        //
        $Request = $event->Request;
        Log::channel('single')->info("Success Post with follow value \r\n", [
            'game_type' => $Request->gmae_type,
            'million' => $Request->million,
            'thousand' => $Request->thousand,
            'hundred' => $Request->hundred,
            'ten' => $Request->ten,
            'one' => $Request->one,
            'money' => $Request->money
        ]);
        // info('do something' . $Request->getMessage());

    }
}
