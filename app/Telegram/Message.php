<?php

namespace App\Telegram;

/**
 * User message received from Telegram according to its API {@link https://core.telegram.org/bots/api}
 */
class Message
{
    private int $chatId;

    private Answer $answer;

    public function __construct(int $chatId, Answer $answer)
    {
        $this->chatId = $chatId;
        $this->answer = $answer;
    }

    /**
     * @return int
     */
    public function getChatId(): int
    {
        return $this->chatId;
    }

    public function getAnswer(): Answer
    {
        return $this->answer;
    }
}
