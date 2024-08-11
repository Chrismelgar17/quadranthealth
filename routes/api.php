<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TwilioVoiceController;
use App\Http\Controllers\VAPIController;

Route::post('/initiate-call', [TwilioVoiceController::class, 'initiateCall']);

Route::get('/gather-call', [VAPIController::class, 'gatherCalls']);