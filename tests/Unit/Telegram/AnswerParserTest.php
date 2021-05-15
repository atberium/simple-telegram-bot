<?php

namespace Tests\Unit\Telegram;

use App\Telegram\Answer;
use App\Telegram\AnswerParser;
use PHPUnit\Framework\TestCase;

class AnswerParserTest extends TestCase
{
    private AnswerParser $parser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->parser = new AnswerParser();
    }

    public function dataProvider(): array
    {
        return [
            ['yes', Answer::LESS()],
            ['y', Answer::LESS()],
            ['да', Answer::LESS()],
            ['дд', Answer::LESS()],
            ['+', Answer::LESS()],
            ['yep', Answer::LESS()],
            ['да foo bar', Answer::LESS()],
            [' да foo bar', Answer::LESS()],
            ['     да      foo bar', Answer::LESS()],

            ['no', Answer::GREATER()],
            ['n', Answer::GREATER()],
            ['-', Answer::GREATER()],
            ['нет', Answer::GREATER()],
            ['н', Answer::GREATER()],
            ['not', Answer::GREATER()],

            ['/start', Answer::START()],
            ['/help', Answer::HELP()],

            ['', Answer::UNKNOWN()],
            ['foo', Answer::UNKNOWN()],
            ['foo bar', Answer::UNKNOWN()],
            ['foo да foo bar', Answer::UNKNOWN()],
        ];
    }

    /**
     * @dataProvider dataProvider
     */
    public function testParse(string $message, Answer $answer): void
    {
        self::assertEquals($answer, $this->parser->parse($message));
    }
}
