<?php

namespace Tests\Unit\Telegram\MessageProcessor;

use App\Telegram\Answer;
use App\Telegram\Client;
use App\Telegram\Message;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

abstract class AbstractMessageProcessorTest extends TestCase
{
    private const CHAT_ID = 1;

    /**
     * @var Client|MockObject
     */
    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = $this->createMock(Client::class);
    }

    protected function mockClientMessage(string $message): void
    {
        $this->client->expects(self::once())
            ->method('send')
            ->with(
                self::equalTo(self::CHAT_ID),
                self::equalTo($message)
            );
    }

    protected function createMessage(Answer $answer): Message
    {
        return new Message(self::CHAT_ID, $answer);
    }
}
