<?php

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\TelegramController;
use App\Telegram\RequestProcessor;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class TelegramControllerTest extends TestCase
{
    private TelegramController $controller;

    /**
     * @var RequestProcessor|MockObject
     */
    private RequestProcessor $processor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->processor = $this->createMock(RequestProcessor::class);
        $this->controller = new TelegramController($this->processor);
    }

    /**
     * @throws BindingResolutionException
     */
    public function testShouldCallRequestProcessorAndReturnSuccessfulResponse(): void
    {
        $request = $this->createMock(Request::class);
        $this->processor->expects(self::once())->method('process')->with(self::equalTo($request));
        self::assertEquals(new Response(), $this->controller->handleRequest($request));
    }
}
