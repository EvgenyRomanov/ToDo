<?php

namespace App\Listeners;

use App\Events\CreateTask;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class WriteToLog
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CreateTask $event): void
    {
        Log::info("Task id = " . $event->task->id . " created");
    }
}
