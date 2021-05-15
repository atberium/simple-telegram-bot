<?php

namespace Tests\Unit\Telegram;

use App\Exceptions\MalformedTelegramMessageException;
use App\Telegram\Answer;
use App\Telegram\AnswerParser;
use App\Telegram\Message;
use App\Telegram\MessageFactory;
use Exception;
use JsonException;
use PHPUnit\Framework\TestCase;

class MessageFactoryTest extends TestCase
{
    private MessageFactory $factory;

    protected function setUp(): void
    {
        parent::setUp();

        $parser = $this->createMock(AnswerParser::class);
        $parser->method('parse')->willReturn(Answer::START());

        $this->factory = new MessageFactory($parser);
    }

    /**
     * @throws MalformedTelegramMessageException
     * @throws JsonException
     */
    public function testCreateFromJson(): void
    {
        self::assertEquals(
            new Message(50207204, Answer::START()),
            $this->factory->createFromJson('{"message": {"chat": {"id": 50207204}, "text": "yes"}}')
        );
    }

    public function dataProvider(): array
    {
        return [
            ['', new JsonException('Syntax error', 4)],
            ['{}', new MalformedTelegramMessageException('{}', null)],
            [
                '{"message": {"chat": {"id": null}}}',
                new MalformedTelegramMessageException('{"message": {"chat": {"id": null}}}', null),
            ],
            [
                '{"message": {"chat": {"id": 1}}}',
                new MalformedTelegramMessageException('{"message": {"chat": {"id": 1}}}', 1),
            ],
        ];
    }

    /**
     * @throws MalformedTelegramMessageException
     * @throws JsonException
     * @dataProvider dataProvider
     */
    public function testDoNotCreateDueToMalformedMessage(string $content, Exception $e): void
    {
        $this->expectExceptionObject($e);

        $this->factory->createFromJson($content);
    }
}
