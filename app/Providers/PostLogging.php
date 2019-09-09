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
        Log::channel('single')->info(
            "Success Post with follow value \r\n",
            $Request->all()
        );
    }
}
