<?php

namespace App\Telegram;

use Illuminate\Support\Facades\Http;

/**
 * Sends requests to the Telegram Bot API
 */
class Client
{
    private string $endpoint;

    public function __construct(string $endpoint)
    {
        $this->endpoint = $endpoint;
    }

    public function send(int $chatId, string $message): void
    {
        $answer = [
            'chat_id' => $chatId,
            'text' => $message,
        ];

        Http::asJson()->post("{$this->endpoint}/sendMessage", $answer);
    }
}
