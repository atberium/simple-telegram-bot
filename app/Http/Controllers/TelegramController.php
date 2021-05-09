<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use JsonException;

class TelegramController extends Controller
{
    /**
     * @throws BindingResolutionException
     */
    public function handleRequest(Request $request): Response
    {
        $json = $request->getContent();
        try {
            $message = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
            $answer = [
                'chat_id' => $message['message']['chat']['id'],
                'text' => 'foo'
            ];
           Http::async()
               ->withBody(json_encode($answer, JSON_THROW_ON_ERROR), 'application/json')
               ->post(config('telegram.bot.endpoint') . '/sendMessage');
        } catch (JsonException $e) {
            Log::error($e->getMessage(), [
                'message' => $json,
            ]);
        }

        return response()->make();
    }
}
