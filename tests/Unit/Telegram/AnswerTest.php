<?php

namespace Tests\Unit\Telegram;

use App\Telegram\Answer;
use PHPUnit\Framework\TestCase;

class AnswerTest extends TestCase
{
    public function dataProvider(): array
    {
        return [
            [Answer::GREATER(), true],
            [Answer::LESS(), true],
            [Answer::START(), true],
            [Answer::HELP(), false],
            [Answer::UNKNOWN(), false],
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testIsGuessing(Answer $answer, bool $result): void
    {
        self::assertEquals($result, $answer->isGuessing());
    }
}
