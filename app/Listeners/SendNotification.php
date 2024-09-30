<?php

namespace App\Listeners;

use App\Events\CreateTask;
use App\Services\TelegramNotification\TelegramNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendNotification
{
    /**
     * Create the event listener.
     */
    public function __construct(public TelegramNotification $telegramNotificationService)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CreateTask $event): void
    {
        $chatId = env('TELEGRAM_CHANNEL');
        $this->telegramNotificationService->sendMessage(
            "Task id = " . $event->task->id . " created",
            $chatId
        );
    }
}
