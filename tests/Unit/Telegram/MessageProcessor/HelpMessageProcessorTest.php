<?php

namespace Tests\Unit\Telegram\MessageProcessor;

use App\Telegram\Answer;
use App\Telegram\MessageProcessor\HelpMessageProcessor;

class HelpMessageProcessorTest extends AbstractMessageProcessorTest
{
    private HelpMessageProcessor $processor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->processor = new HelpMessageProcessor($this->client);
    }

    public function testProcessHelpAnswer(): void
    {
        $this->mockClientMessage("Please, make a guess and print \"/start\"");
        self::assertTrue($this->processor->process($this->createMessage(Answer::HELP())));
    }

    public function dataProvider(): array
    {
        return array_map(
            static fn (Answer $answer) => [$answer],
            array_filter(Answer::values(), static fn (Answer $answer) => !$answer->equals(Answer::HELP()))
        );
    }

    /**
     * @dataProvider dataProvider
     */
    public function testShouldNotProcessAnswer(Answer $answer): void
    {
        $this->client->expects(self::never())->method('send');
        self::assertFalse($this->processor->process($this->createMessage($answer)));
    }
}
