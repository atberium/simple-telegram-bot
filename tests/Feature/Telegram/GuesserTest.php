<?php

namespace Tests\Feature\Telegram;

use App\Telegram\Answer;
use App\Telegram\Guesser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuesserTest extends TestCase
{
    use RefreshDatabase;

    private Guesser $guesser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->guesser = new Guesser(1, 1000);
    }

    public function dataProvider(): array
    {
        return [
            [100],
            [101],
            [10],
            [5],
            [1],
            [1000],
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testShouldGuess(int $number): void
    {
        $guess = $this->guesser->guess(1, Answer::START());

        while(!$guess->guessed) {
            $guess = $this->guesser->guess(1, $guess->value > $number ? Answer::LESS() : Answer::GREATER());
        }

        self::assertEquals($number, $guess->value);
    }

    public function testUseTheSameGuessObjectWhileGuessing(): void
    {
        $id = $this->guesser->guess(1, Answer::START())->id;
        $this->guesser->guess(1, Answer::LESS());
        $guess = $this->guesser->guess(1, Answer::GREATER());

        self::assertEquals($guess->id, $id);
    }

    public function testShouldRestart(): void
    {
        $id = $this->guesser->guess(1, Answer::START())->id;
        $this->guesser->guess(1, Answer::LESS());
        $guess = $this->guesser->guess(1, Answer::START());

        self::assertNotEquals($guess->id, $id);
    }
}
