<?php

use App\Http\Controllers\CallController;

Route::post('/incoming-call', [CallController::class, 'handleIncomingCall']);
Route::post('/vapi-status-callback/{vapiCallId}', [CallController::class, 'vapiStatusCallback'])->name('call.vapi-status-callback');