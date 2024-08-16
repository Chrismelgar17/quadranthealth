<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\CallLog; // Assuming you have a model for storing call logs
use App\Services\VapiService; // Assuming you have a service class for interacting with the Voice API
use App\Models\Calls; // Assuming you have a model for storing call data

class TwilioWebhookController extends Controller
{
    /**
     * Handle the incoming Twilio call status webhook.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function __construct(protected VapiService $vapiService)
    {
        // Add any necessary constructor logic here
    }

    public function handleCallStatus(Request $request)
    {
        // Log the incoming request data for debugging
        Log::info('Twilio Call Status Webhook received:', $request->all());

        // Extract data from the Twilio webhook request
        $callSid = $request->input('CallSid');
        $callStatus = $request->input('CallStatus');
        $from = $request->input('From');
        $to = $request->input('To');
        $duration = $request->input('CallDuration'); // This is available when the call ends

        // Save the data to the database (optional)
        CallLog::create([
            'call_sid' => $callSid,
            'call_status' => $callStatus,
            'from' => $from,
            'to' => $to,
            'duration' => $duration,
            'timestamp' => now(),
        ]);

        // Get the vapi_call_id from the Calls model based on the CallSid
        $vapiCallId = Calls::where('call_sid', $callSid)->value('vapi_call_id');

        // Delay execution for 60 seconds
        sleep(120);

        // Continue with the rest of the code
        $response = $this->vapiService->getCallStatus($vapiCallId);

        //parse response to string
        $response = json_encode($response);

        $file=fopen("response.txt","w");
        fwrite($file,$response);

       $callData = json_decode($response, true);

        $transcript = $callData['transcript'];
        $summary = $callData['summary'];


        // Update the Calls model with the extracted transcript and other relevant information
        Calls::upsert([
            'call_sid' => $callSid,
            'vapi_call_id' => $vapiCallId,
            'transcript' => $transcript,
            'summary' => $summary,
        ], ['call_sid'], ['transcript', 'summary']);

        // Return a 200 OK response to Twilio
        return response('Webhook received', 200);
    }
}
