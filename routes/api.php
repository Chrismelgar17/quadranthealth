<?php

use App\Http\Controllers\CallController;
use App\Http\Controllers\VoiceMailController;

Route::post('/incoming-call', [CallController::class, 'handleIncomingCall']);
Route::post('/check-voicemail', [CallController::class, 'checkVoicemail'])->name('call.check-voicemail');
Route::post('/voice/appointment', [VoiceMailController::class, 'handleAppointment']);
Route::post('/voice/medication', [VoiceMailController::class, 'handleMedication']);
Route::post('/voice/other', [VoiceMailController::class, 'handleOther']);
Route::post('/process-transcript', [VoiceMailController::class, 'processTranscript']);