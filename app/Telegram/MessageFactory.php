<?php

namespace App\Telegram;

use App\Exceptions\MalformedTelegramMessageException;
use JsonException;

/**
 * Factory of messages {@link \App\Telegram\Message}
 */
class MessageFactory
{
    private AnswerParser $parser;

    public function __construct(AnswerParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Create message from json string
     * @throws MalformedTelegramMessageException
     * @throws JsonException
     */
    public function createFromJson(string $json): Message
    {
        $message = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        if (!isset($message['message']['chat']['id'])) {
            throw new MalformedTelegramMessageException($json, null);
        }

        $chatId = $message['message']['chat']['id'];

        if (!isset($message['message']['text'])) {
            throw new MalformedTelegramMessageException($json, $chatId);
        }

        $chatId = $message['message']['chat']['id'];

        return new Message($chatId, $this->parser->parse($message['message']['text']));
    }
}
