<?php

namespace App\Providers;

use App\Telegram\Client;
use App\Telegram\Guesser;
use App\Telegram\MessageProcessor\CompositeMessageProcessor;
use App\Telegram\MessageProcessor\GuesserMessageProcessor;
use App\Telegram\MessageProcessor\HelpMessageProcessor;
use App\Telegram\MessageProcessor\MessageProcessorInterface;
use App\Telegram\MessageProcessor\UnknownMessageProcessor;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Client::class, fn() => new Client(config('app.telegram_bot_endpoint')));
        $this->app->singleton(Guesser::class, fn() => new Guesser(
            config('app.value_min'),
            config('app.value_max'),
        ));
        $this->app->singleton(MessageProcessorInterface::class, fn(Container $app) => new CompositeMessageProcessor(
            new GuesserMessageProcessor($app->make(Guesser::class), $app->make(Client::class)),
            new HelpMessageProcessor($app->make(Client::class)),
            new UnknownMessageProcessor($app->make(Client::class)),
        ));
    }

    public function boot()
    {
        //
    }
}
