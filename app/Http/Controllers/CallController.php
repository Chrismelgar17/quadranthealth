<?php

namespace App\Http\Controllers;

use App\Services\TwilioService;
use App\Services\VoiceAIService;
use Illuminate\Http\Request;

class CallController extends Controller
{
    protected $twilioService;
    protected $voiceAIService;

    public function __construct(TwilioService $twilioService, VoiceAIService $voiceAIService)
    {
        $this->twilioService = $twilioService;
        $this->voiceAIService = $voiceAIService;
    }

    public function handleIncomingCall(Request $request)
    {
        $call = $this->twilioService->forwardCall(config('twilio.medical_practice_number'));

        if ($this->twilioService->detectVoicemail($call->sid)) {
            return $this->voiceAIService->handleCall('default');
        }

        return response('Call forwarded', 200);
    }
}