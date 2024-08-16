<?php

namespace App\Http\Controllers;

use App\Services\VapiService;
use Illuminate\Http\Request;
use Twilio\TwiML\VoiceResponse;
use Illuminate\Support\Facades\Log;
use App\Models\Calls;

class CallController extends Controller
{
    protected $vapiService;

    public function __construct(VapiService $vapiService)
    {
        $this->vapiService = $vapiService;
    }

    public function handleIncomingCall(Request $request)
    {
        
        Log::info('Incoming call: ' , $request->all());

        
        // try {
            $vapiCallId = $this->vapiService->initiateCall(config('twilio.medical_practice_number'));
            Calls::upsert([
                'call_sid' => $request->input('CallSid'),
                'vapi_call_id' => $vapiCallId,
            ], ['call_sid'], ['vapi_call_id']);


            // $response = new VoiceResponse();
            // $response->say('Please hold while we connect you to our assistant.');
            // $response->redirect(route('call.vapi-status-callback', ['vapiCallId' => $vapiCallId]));
            
            // return response($response)->header('Content-Type', 'text/xml');
        // } catch (\Exception $e) {
        //     \Log::error('Failed to initiate Vapi AI call: ' . $e->getMessage());
            
        //     $response = new VoiceResponse();
        //     $response->say('We apologize, but we are unable to connect you to our assistant at this time. Please try again later.');
        //     return response($response)->header('Content-Type', 'text/xml');
        // }
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
}