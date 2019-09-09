<?php

namespace App\Providers;

use App\Events\UserPostBetlistException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class PostLoggingException
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
     * @param  UserPostBetlistException  $event
     * @return void
     */
    public function handle(UserPostBetlistException $event)
    {
        $e = $event->e;
        // Log::error("Data Base Error \n\r" . $e->getMessage());
        Log::channel('single')->error("Data Base Error \n\r" . $e->getMessage());
    }
}
