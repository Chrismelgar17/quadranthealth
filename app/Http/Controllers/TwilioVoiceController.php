<?php
namespace App\Http\Controllers;

use App\Services\TwilioVoiceService;
use Illuminate\Http\Request;
use Twilio\TwiML\VoiceResponse;

class TwilioVoiceController extends Controller  
{
    protected $twilioVoiceService;

    public function __construct(TwilioVoiceService $twilioVoiceService)
    {
        $this->twilioVoiceService = $twilioVoiceService;
    }

    public function initiateCall(Request $request)
    {
        $request->validate([
            'to' => 'required|string',
        ]);

        try {
            $call = $this->twilioVoiceService->makeCall(
                $request->input('to'),
                route('voice.twiml')
            );

            return response()->json([
                'success' => true,
                'message' => 'Call initiated successfully',
                'sid' => $call->sid
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to initiate call: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateTwiML()
    {
        $response = new VoiceResponse();
        $response->say('Hello from Twilio and Laravel. This is a test call.');
        $response->play('https://api.twilio.com/cowbell.mp3');

        return response($response, 200)
            ->header('Content-Type', 'text/xml');
    }
}