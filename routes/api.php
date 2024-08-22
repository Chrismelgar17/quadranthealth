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

//CLINICS
use App\Http\Controllers\ClinicController;

Route::get('/clinics', [ClinicController::class, 'getClinics']);
Route::get('/clinics/{id}', [ClinicController::class, 'getClinicById']);
Route::post('/clinics', [ClinicController::class, 'upsertClinic']);
Route::patch('/clinics/{id}', [ClinicController::class, 'upsertClinic']);
Route::delete('/clinics/{id}', [ClinicController::class, 'deleteClinic']);