<?php

namespace Tests\Unit\Telegram\MessageProcessor;

use App\Telegram\Answer;
use App\Telegram\MessageProcessor\UnknownMessageProcessor;

class UnknownMessageProcessorTest extends AbstractMessageProcessorTest
{
    private UnknownMessageProcessor $processor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->processor = new UnknownMessageProcessor($this->client);
    }

    public function dataProvider(): array
    {
        return array_map(static fn (Answer $answer) => [$answer], Answer::values());
    }

    /**
     * @dataProvider dataProvider
     */
    public function testProcessAnyAnswer(Answer $answer): void
    {
        $this->mockClientMessage("Sorry, I don't understand you");
        self::assertTrue($this->processor->process($this->createMessage($answer)));
    }

    protected function getApplicableAnswers(): array
    {
        return Answer::values();
    }
}
