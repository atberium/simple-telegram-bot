<?php

namespace App\Telegram;

use App\Exceptions\MalformedTelegramMessageException;
use Illuminate\Http\Request;
use JsonException;

class Message
{
    private int $chatId;

    private Answer $answer;

    private function __construct(int $chatId, Answer $answer)
    {
        $this->chatId = $chatId;
        $this->answer = $answer;
    }

    /**
     * @throws MalformedTelegramMessageException
     * @throws JsonException
     */
    public static function createFromHttpRequest(Request $request): self
    {
        $json = $request->getContent();
        $message = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        if (!isset($message['message']['chat']['id'])) {
            throw new MalformedTelegramMessageException($json, null);
        }

        $chatId = $message['message']['chat']['id'];

        if (!isset($message['message']['text'])) {
            throw new MalformedTelegramMessageException($json, $chatId);
        }

        $chatId = $message['message']['chat']['id'];

        return new self($chatId, AnswerParser::parse($message['message']['text']));
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
