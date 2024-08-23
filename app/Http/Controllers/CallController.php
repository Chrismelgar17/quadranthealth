<?php

namespace App\Http\Controllers;

use App\Services\VapiService;
use App\Services\ClaudeService;
use Illuminate\Http\Request;
use Twilio\TwiML\VoiceResponse;
use Illuminate\Support\Facades\Log;
use App\Models\Calls;
use Twilio\TwiML\VoiceResponse as TwimlResponse;

class CallController extends Controller
{
    protected $vapiService;

    public function __construct(VapiService $vapiService, protected ClaudeService $claudeService)
    {
        $this->vapiService = $vapiService;
    }

    public function handleIncomingCall(Request $request)
    {
        Log::info('Incoming call: ', $request->all());

        $data = $request->all();

        try {
            $twiml = new TwimlResponse();

            //Dial the desired number
            // $dial = $twiml->dial('+541136688353', [
            //      'action' => 'https://api.vapi.ai/twilio/inbound_call', // Define a route for AMD status callback
            //     'timeout'=> 5,
            // ]);

            $twiml->redirect('https://api.vapi.ai/twilio/inbound_call', ['method' => 'POST']);

            // Add the number to forward the call
            // $dial->number(config('twilio.twilio_number'));

            return response($twiml)->header('Content-Type', 'text/xml');
        } catch (\Exception $e) {
            Log::error('Error handling incoming call: ' . $e->getMessage());
            return response('Internal Server Error', 500);
        }
    }

    public function amdStatusCallback(Request $request)
    {
        Log::info('AMD Status Callback: ', $request->all());

        $twiml = new TwimlResponse();

        // Check if voicemail was detected
        if ($request->input('AnsweredBy') === 'machine') {
            // End the call if voicemail is detected
            $twiml->hangup();
        } else {
            // Continue normal call flow if a human answered
            $twiml->say('Thank you for answering the call.');
        }

        return response($twiml)->header('Content-Type', 'text/xml');
    }

    public function vapiStatusCallback(Request $request, $vapiCallId)
    {
        $callStatus = $this->vapiService->getCallStatus($vapiCallId);
        
        $response = new VoiceResponse();
        
        if ($callStatus === 'completed') {
            $response->say('Thank you for using our service. Goodbye.');
        } else {
            $response->redirect(route('call.vapi-status-callback', ['vapiCallId' => $vapiCallId]));
        }
        
        return response($response)->header('Content-Type', 'text/xml');
    }

    public function bamlTranscriptToJson($transcript)
    {
        // $transcript = "AI: Hello. This is Gabriela. How may I assist you today? Can make an appointment booking, a medication refill, or another. Please let me know.\nUser: I need a medication refill, please.\nAI: Sure thing. What's your full name?\nUser: My full name is\nAI: Got it. And what's the name of the medication you\nUser: Robert My name is Robert Downey.\nAI: Nice to meet you, Mister.\nUser: Okay.\nAI: Downey like the actor, but probably with less iron in your diet, I bet. Now, what's the medication you need refilled, sir?\nUser: I need a 1 supply of refillable, please.\nAI: Great. And the name of the medication?\nUser: And the name of the medication is Oxycodone.\nAI: Thanks, Robert. Any changes in your health or other medications since your last visit?\nUser: No. That's all. Almost\nAI: Awesome. Got it. We'll send your request over to doctor. Smith, if there are any issues, we'll give you a shout. Anything else, Robert?\nUser: No. None for now.\nAI: Alright.\nUser: Thanks a lot, Gabriela.\nAI: No problem, Robert. Take care, and have a great day.\nUser: Bye bye.\n";

        // Encode the transcript to safely pass it to the shell command
        $encodedTranscript = json_encode($transcript);

        // Write the encoded transcript to a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'transcript_');
        file_put_contents($tempFile, $encodedTranscript);

        // Build the command
        $command = 'python output.py ' . escapeshellarg($tempFile);

        // Execute the command and capture the output and any errors
        $output = shell_exec($command . ' 2>&1');

        // Remove the temporary file
        unlink($tempFile);

        // Check if there is any output
        if ($output === null) {
            echo "No output from Python script.";
        } else {
            return nl2br(htmlspecialchars($output));
        }
    }
}