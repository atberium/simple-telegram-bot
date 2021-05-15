<?php

namespace App\Telegram\MessageProcessor;

use App\Telegram\Message;

/**
 * Process user message {@link \App\Telegram\Message} and sends request to the Telegram API bot
 */
interface MessageProcessorInterface
{
    /**
     * Processes message. If message could not processed, returns false, true otherwise
     */
    public function process(Message $message): bool;
}
