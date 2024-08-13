<?php

namespace App\Http\Controllers;

use App\Services\VoiceAIService;
use App\Services\ClaudeService;
use Illuminate\Http\Request;

class VoiceMailController extends Controller
{
    protected $voiceAIService;
    protected $claudeService;

    public function __construct(VoiceAIService $voiceAIService, ClaudeService $claudeService)
    {
        $this->voiceAIService = $voiceAIService;
        $this->claudeService = $claudeService;
    }

    public function handleAppointment(Request $request)
    {
        // Handle appointment logic
    }

    public function handleMedication(Request $request)
    {
        // Handle medication logic
    }

    public function handleOther(Request $request)
    {
        // Handle other requests
    }

    public function processTranscript(Request $request)
    {
        $transcript = $request->input('transcript');
        $requestType = $request->input('request_type');
        $summary = $this->claudeService->processTranscript($transcript, $requestType);

        return response()->json([
            'Patient_phone_number' => $request->input('patient_phone'),
            'clinic_phone_number' => config('twilio.medical_practice_number'),
            'message' => "INBOUND PHONE CALL\n\nRequest Type: {$requestType}\n\n{$summary}"
        ]);
    }
}