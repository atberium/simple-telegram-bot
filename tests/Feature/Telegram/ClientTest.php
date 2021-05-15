<?php

namespace Tests\Feature\Telegram;

use App\Telegram\Client;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ClientTest extends TestCase
{
    private const ENDPOINT = 'https://test.endpoint.com';

    private Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = new Client(self::ENDPOINT);

        Http::fake();
    }

    public function testSend(): void
    {
        $this->client->send(1, "foo bar");

        Http::assertSent(function ($request) {
            return $request->url() === self::ENDPOINT . '/sendMessage'
                && $request['chat_id'] === 1
                && $request['text'] === 'foo bar';
        });
    }
}
