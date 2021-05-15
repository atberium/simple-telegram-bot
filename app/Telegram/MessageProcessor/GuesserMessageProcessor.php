<?php

namespace App\Telegram\MessageProcessor;

use App\Telegram\Client;
use App\Telegram\Guesser;
use App\Telegram\Message;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * Processor that guesses which number the user guessed
 */
class GuesserMessageProcessor implements MessageProcessorInterface
{
    private Guesser $guesser;

    private Client $client;

    public function __construct(Guesser $guesser, Client $client)
    {
        $this->guesser = $guesser;
        $this->client = $client;
    }

    public function process(Message $message): bool
    {
        if (!$message->getAnswer()->isGuessing()) {
            return false;
        }

        try {
            $guess = $this->guesser->guess($message->getChatId(), $message->getAnswer());

            if ($guess->guessed) {
                $this->client->send($message->getChatId(), "Your number is {$guess->value}");
            } else {
                $this->client->send($message->getChatId(), "Is your number less than {$guess->value}?");
            }
        } catch (ModelNotFoundException $e) {
            HelpMessageProcessor::help($message, $this->client);
        }

        return true;
    }
}
