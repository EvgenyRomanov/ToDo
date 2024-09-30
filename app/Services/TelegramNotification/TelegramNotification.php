<?php

namespace App\Services\TelegramNotification;

interface TelegramNotification
{
    public function sendMessage(string $message, int|string $chatId): void;
}
