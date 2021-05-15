<?php

namespace App\Telegram\MessageProcessor;

use App\Telegram\Answer;
use App\Telegram\Client;
use App\Telegram\Message;

/**
 * Message processor, which sends to chat help info
 */
class HelpMessageProcessor implements MessageProcessorInterface
{
    public const MESSAGE = "Please, make a guess and print \"/start\"";

    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function process(Message $message): bool
    {
        if (!$message->getAnswer()->equals(Answer::HELP())) {
            return false;
        }

        self::help($message, $this->client);

        return true;
    }

    public static function help(Message $message, Client $client): void
    {
        $client->send($message->getChatId(), self::MESSAGE);
    }
}
