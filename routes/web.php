<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TwilioVoiceController;

Route::get('/voice-twiml', [TwilioVoiceController::class, 'generateTwiML'])->name('voice.twiml');