<?php

namespace App\Services\TelegramNotification;

use Http\Client\Curl\Client;
use HttpSoft\Message\RequestFactory;
use HttpSoft\Message\ResponseFactory;
use HttpSoft\Message\StreamFactory;
use Vjik\TelegramBot\Api\Client\PsrTelegramClient;
use Vjik\TelegramBot\Api\TelegramBotApi;

class TelegramNotificationService implements TelegramNotification
{
    protected TelegramBotApi $api;

    public function __construct()
    {
        $streamFactory = new StreamFactory();
        $responseFactory = new ResponseFactory();
        $requestFactory = new RequestFactory();
        $client = new Client($responseFactory, $streamFactory);

        $this->api = new TelegramBotApi(
            new PsrTelegramClient(
                env('TELEGRAM_API_KEY'),
                $client,
                $requestFactory,
                $streamFactory,
            ),
        );
    }

    public function sendMessage(string $message, int|string $chatId): void
    {
        $this->api->sendMessage(
            chatId: $chatId,
            text: $message,
        );
    }
}
