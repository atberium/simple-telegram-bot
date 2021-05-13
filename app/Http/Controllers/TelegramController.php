<?php

namespace App\Http\Controllers;

use App\Telegram\RequestProcessor;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TelegramController extends Controller
{
    private RequestProcessor $processor;

    public function __construct(RequestProcessor $processor)
    {
        $this->processor = $processor;
    }

    /**
     * @throws BindingResolutionException
     */
    public function handleRequest(Request $request): Response
    {
        $this->processor->process($request);

        return response()->make();
    }
}
