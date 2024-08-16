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


        $response = $this->vapiService->getCallStatus($vapiCallId);

Log::info('CallSid: ' . $response);

        // if ($response) {
           

        //     if (isset($response['transcript']) && isset($response['summary'])) {
        //         $transcript = $response['transcript'];
        //         $summary = $response['summary'];

        //         Calls::upsert([
        //             'transcript' => $transcript,
        //             'summary' => $summary
        //         ], ['call_sid'], ['transcript', 'summary']);
        //     }
        // }

        // // Log the values of the variables
        // Log::info('CallSid: ' . $callSid);
        // Log::info('vapi_call_id: ' . $vapiCallId);
        // Log::info('transcript: ' . $transcript);
        // Log::info('summary: ' . $summary);
        // Return a 200 OK response to Twilio
        return response('Webhook received', 200);
    }
}
