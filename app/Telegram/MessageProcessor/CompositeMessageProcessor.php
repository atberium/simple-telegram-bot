<?php

namespace App\Telegram\MessageProcessor;
use App\Telegram\Message;

/**
 * Composition of processors {@link \App\Telegram\MessageProcessor\MessageProcessorInterface}
 * Delegates message processing to each processors. Stops delegating, when one of the processors succeeded
 */
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
