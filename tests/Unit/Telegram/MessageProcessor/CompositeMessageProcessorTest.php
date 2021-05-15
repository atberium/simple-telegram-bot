<?php

namespace Tests\Unit\Telegram\MessageProcessor;

use App\Telegram\Answer;
use App\Telegram\Message;
use App\Telegram\MessageProcessor\CompositeMessageProcessor;
use App\Telegram\MessageProcessor\MessageProcessorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CompositeMessageProcessorTest extends TestCase
{
    private CompositeMessageProcessor $composite;

    /**
     * @var MessageProcessorInterface|MockObject
     */
    private MessageProcessorInterface $processor1;

    /**
     * @var MessageProcessorInterface|MockObject
     */
    private MessageProcessorInterface $processor2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->processor1 = $this->createMock(MessageProcessorInterface::class);
        $this->processor2 = $this->createMock(MessageProcessorInterface::class);

        $this->composite = new CompositeMessageProcessor($this->processor1, $this->processor2);
    }

    public function testShouldDelegateProcessingToNestedProcessors(): void
    {
        $this->processor1->expects(self::once())->method('process')->willReturn(false);
        $this->processor2->expects(self::once())->method('process');

        $this->composite->process(new Message(1, Answer::START()));
    }

    public function testShouldStopFurtherDelegatingIfFirstNestedProcessorSucceeded(): void
    {
        $this->processor1->expects(self::once())->method('process')->willReturn(true);
        $this->processor2->expects(self::never())->method('process');

        $this->composite->process(new Message(1, Answer::START()));
    }
}
