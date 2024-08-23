<?php

namespace App\Http\Controllers;

use App\Services\ClaudeService;
use Illuminate\Http\Request;

class VoiceMailController extends Controller
{

    public function __construct(protected ClaudeService $claudeService)
    {
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
        // $transcript = $request->input('transcript');
        $transcript = "AI: Hello. This is Gabriela. How may I assist you today? Can make an appointment booking, a medication refill, or another. Please let me know.\nUser: I need a medication refill, please.\nAI: Sure thing. What's your full name?\nUser: My full name is\nAI: Got it. And what's the name of the medication you\nUser: Robert My name is Robert Downey.\nAI: Nice to meet you, Mister.\nUser: Okay.\nAI: Downey like the actor, but probably with less iron in your diet, I bet. Now, what's the medication you need refilled, sir?\nUser: I need a 1 supply of refillable, please.\nAI: Great. And the name of the medication?\nUser: And the name of the medication is Oxycodone.\nAI: Thanks, Robert. Any changes in your health or other medications since your last visit?\nUser: No. That's all. Almost\nAI: Awesome. Got it. We'll send your request over to doctor. Smith, if there are any issues, we'll give you a shout. Anything else, Robert?\nUser: No. None for now.\nAI: Alright.\nUser: Thanks a lot, Gabriela.\nAI: No problem, Robert. Take care, and have a great day.\nUser: Bye bye.\n";
        // $requestType = $request->input('request_type');
        $requestType = 'Medication Refill';
        $summary = $this->claudeService->processTranscript($transcript, $requestType);

        return response()->json(['summary' => $summary]);
    }
}