<?php

namespace App\Telegram\MessageProcessor;

use App\Telegram\Message;

interface MessageProcessorInterface
{
    public function process(Message $message): bool;
}
