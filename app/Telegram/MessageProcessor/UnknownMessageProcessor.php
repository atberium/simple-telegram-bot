<?php

namespace App\Telegram\MessageProcessor;

use App\Telegram\Client;
use App\Telegram\Message;

/**
 * Final message processor, when we couldn't recognize, what user wanted to say
 */
class UnknownMessageProcessor implements MessageProcessorInterface
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function process(Message $message): bool
    {
        $this->client->send($message->getChatId(), "Sorry, I don't understand you");

        return true;
    }
}
