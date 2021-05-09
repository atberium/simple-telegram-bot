<?php

use App\Http\Controllers\TelegramController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->get('/handle', [TelegramController::class, 'handleRequest']);
