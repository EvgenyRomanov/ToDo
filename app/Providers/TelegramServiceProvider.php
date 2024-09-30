<?php

namespace App\Providers;

use App\Services\TelegramNotification\TelegramNotification;
use App\Services\TelegramNotification\TelegramNotificationService;
use Illuminate\Support\ServiceProvider;

class TelegramServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(TelegramNotification::class, TelegramNotificationService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

    }
}
