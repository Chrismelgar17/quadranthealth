<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\CallLog; // Assuming you have a model for storing call logs
use App\Services\VapiService; // Assuming you have a service class for interacting with the Voice API
use App\Models\Calls; // Assuming you have a model for storing call data
use Twilio\Rest\Client; // Assuming you have the Twilio PHP SDK installed

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
        CallLog::upsert([
            'call_sid' => $callSid,
            'call_status' => $callStatus,
            'from' => $from,
            'to' => $to,
            'duration' => $duration,
            'timestamp' => now(),
        ], ['call_sid'], ['call_status', 'from', 'to', 'duration', 'timestamp']);

        // Get the vapi_call_id from the Calls model based on the CallSid
        // $vapiCallId = Calls::where('call_sid', $callSid)->value('');
        $vapiAssistantId = $this->vapiService->getAssistantId();
        Log::info('Vapi Assistant ID:'. $vapiAssistantId);

        // Delay execution for 60 seconds
        sleep(60);

        // Continue with the rest of the code
        $response = $this->vapiService->getCallStatus($vapiAssistantId);

        // //parse response to string
        $response = json_encode($response[0]);


       $callData = json_decode($response, true);

       $vapiCallId = $callData['id'];
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

    public function setWebhooks(Request $request)
    {
     
           

            $sid = config('twilio.account_sid');
            $token = config('twilio.auth_token');
            $client = new Client($sid, $token);

            // URLs to update
            $voiceUrl = 'https://5192-52-26-189-160.ngrok-free.app/api/incoming-call';
            $statusCallback = 'https://5192-52-26-189-160.ngrok-free.app/api/call-status';

            // Retrieve and update all phone numbers
            $phoneNumbers = $client->incomingPhoneNumbers->read();

            foreach ($phoneNumbers as $number) {
                $updatedNumber = $client->incomingPhoneNumbers($number->sid)
                                        ->update([
                                            'voiceUrl' => $voiceUrl,
                                            'statusCallback' => $statusCallback
                                        ]);
                echo "Updated number: " . $updatedNumber->phoneNumber . PHP_EOL;
            }

    }
}
