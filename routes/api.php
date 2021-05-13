<?php

use App\Http\Controllers\TelegramController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->post('/handle', [TelegramController::class, 'handleRequest']);
