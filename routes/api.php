<?php

use App\Http\Controllers\TelegramController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->match(['get', 'post'], '/handle', [TelegramController::class, 'handleRequest']);
