<?php

namespace App\Exceptions;

use Exception;

class MalformedTelegramMessageException extends Exception
{
    private ?int $chatId;

    public function __construct(string $message, ?int $chatId)
    {
        parent::__construct(substr($message, 0, 100));
        $this->chatId = $chatId;
    }

    public function getChatId(): ?int
    {
        return $this->chatId;
    }
}
