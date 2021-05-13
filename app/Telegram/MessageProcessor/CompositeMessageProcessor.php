<?php

namespace App\Telegram\MessageProcessor;
use App\Telegram\Message;

class CompositeMessageProcessor implements MessageProcessorInterface
{
    /**
     * @var MessageProcessorInterface[]
     */
    private array $processors;

    public function __construct(MessageProcessorInterface ...$processors)
    {
        $this->processors = $processors;
    }

    public function process(Message $message): bool
    {
        foreach ($this->processors as $processor) {
            if ($processor->process($message)) {
                return true;
            }
        }

        return false;
    }
}
