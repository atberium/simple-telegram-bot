<?php

namespace App\Telegram;

use App\Exceptions\MalformedTelegramMessageException;
use App\Telegram\MessageProcessor\MessageProcessorInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use JsonException;

class RequestProcessor
{
    private MessageProcessorInterface $messageProcessor;

    public function __construct(MessageProcessorInterface $messageProcessor)
    {
        $this->messageProcessor = $messageProcessor;
    }

    public function process(Request $request): void
    {
        try {
            $message = Message::createString((string)$request->getContent());
            $this->messageProcessor->process($message);
        } catch (JsonException | MalformedTelegramMessageException $e) {
            Log::error($e, [
                'request' => substr($request->getContent(), 100),
                'chatId' => $e->getChatId(),
            ]);
        }
    }
}
