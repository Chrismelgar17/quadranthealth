<?php

use App\Http\Controllers\CallController;
use App\Http\Controllers\TwilioWebhookController;
use App\Services\VapiService;

Route::post('/incoming-call', [CallController::class, 'handleIncomingCall']);
Route::post('/vapi-status-callback/{vapiCallId}', [CallController::class, 'vapiStatusCallback'])->name('call.vapi-status-callback');
Route::post('/call-status', [TwilioWebhookController::class, 'handleCallStatus']);
Route::get('/transcription', [CallController::class, 'testTranscript']);
Route::get('/assistantId', [VapiService::class, 'getAssistantId']);
Route::get('/setWebhooks', [TwilioWebhookController::class, 'setWebhooks']);