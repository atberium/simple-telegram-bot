<?php

namespace Tests\Unit\Telegram;

use App\Telegram\Answer;
use App\Telegram\Message;
use App\Telegram\MessageFactory;
use App\Telegram\MessageProcessor\MessageProcessorInterface;
use App\Telegram\RequestProcessor;
use Illuminate\Http\Request;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class RequestProcessorTest extends TestCase
{
    private RequestProcessor $processor;

    /**
     * @var MessageProcessorInterface|MockObject
     */
    private MessageProcessorInterface $messageProcessor;

    /**
     * @var MessageFactory|MockObject
     */
    private MessageFactory $messageFactory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->messageProcessor = $this->createMock(MessageProcessorInterface::class);
        $this->messageFactory = $this->createMock(MessageFactory::class);

        $this->processor = new RequestProcessor($this->messageProcessor, $this->messageFactory);
    }

    public function testProcess(): void
    {
        $this->messageFactory->expects(self::once())
            ->method('createFromJson')
            ->with(self::equalTo('content'))
            ->willReturn($message = new Message(1, Answer::START()));

        $this->messageProcessor->expects(self::once())
            ->method('process')
            ->with(self::equalTo($message));

        $this->processor->process(new Request([], [], [], [], [], [], 'content'));
    }
}
