<?php

namespace Tests\Unit\Telegram\MessageProcessor;

use App\Models\Guess;
use App\Telegram\Answer;
use App\Telegram\Guesser;
use App\Telegram\MessageProcessor\GuesserMessageProcessor;
use App\Telegram\MessageProcessor\HelpMessageProcessor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use PHPUnit\Framework\MockObject\MockObject;

class GuesserMessageProcessorTest extends AbstractMessageProcessorTest
{
    private const VALUE = 500;

    private GuesserMessageProcessor $processor;

    /**
     * @var Guesser|MockObject
     */
    private Guesser $guesser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->guesser = $this->createMock(Guesser::class);
        $this->processor = new GuesserMessageProcessor($this->guesser, $this->client);
    }

    public function dataProviderShouldProcessAnswer(): array
    {
        return [
            [true, sprintf("Your number is %d", self::VALUE)],
            [false, sprintf("Is your number less than %d?", self::VALUE)],
        ];
    }

    /**
     * @dataProvider dataProviderShouldProcessAnswer
     */
    public function testShouldProcessAnswer(bool $guessed, string $message): void
    {
        $this->guesser->method('guess')->willReturn($this->createGuess($guessed));
        $this->mockClientMessage($message);

        self::assertTrue($this->processor->process($this->createMessage(Answer::LESS())));
    }

    public function dataProviderShouldNotProcessAnswer(): array
    {
        return array_map(
            static fn(Answer $answer) => [$answer],
            array_filter(Answer::values(), static fn(Answer $answer) => !$answer->isGuessing())
        );
    }

    /**
     * @dataProvider dataProviderShouldNotProcessAnswer
     */
    public function testShouldNotProcessAnswer(Answer $answer): void
    {
        $this->guesser->expects(self::never())->method('guess');
        $this->client->expects(self::never())->method('send');
        self::assertFalse($this->processor->process($this->createMessage($answer)));
    }

    public function testShouldAskToRestartIfNoActualGuessingFound(): void
    {
        $this->guesser->method('guess')->willThrowException(new ModelNotFoundException());
        $this->mockClientMessage(HelpMessageProcessor::MESSAGE);
        self::assertTrue($this->processor->process($this->createMessage(Answer::LESS())));
    }

    private function createGuess(bool $guessed): Guess
    {
        $guess = new Guess();
        $guess->guessed = $guessed;
        $guess->value = self::VALUE;

        return $guess;
    }
}
