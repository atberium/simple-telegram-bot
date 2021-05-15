<?php

namespace App\Telegram;

use App\Exceptions\MalformedTelegramMessageException;
use App\Telegram\MessageProcessor\MessageProcessorInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use JsonException;

/**
 * Process webhook request from telegram.
 * Retrieves user message information from received request and process it via message processor
 */
class RequestProcessor
{
    private MessageProcessorInterface $messageProcessor;

    private MessageFactory $messageFactory;

    public function __construct(MessageProcessorInterface $messageProcessor, MessageFactory $messageFactory)
    {
        $this->messageProcessor = $messageProcessor;
        $this->messageFactory = $messageFactory;
    }

    public function process(Request $request): void
    {
        try {
            $message = $this->messageFactory->createFromJson((string)$request->getContent());
            $this->messageProcessor->process($message);
        } catch (JsonException | MalformedTelegramMessageException $e) {
            Log::error($e, [
                'request' => substr($request->getContent(), 100),
                'chatId' => $e->getChatId(),
            ]);
        }
    }
}
