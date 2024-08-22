<?php


namespace App\Repositories;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VapiAssistantRepository
{
    private $apiKey;
    private $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('vapi.api_key');
        $this->apiUrl = config('vapi.api_url');
    }

    private function buildContent($clinicData)
    {
        return "You are a voice assistant for {$clinicData['clinic_name']}, a medical office located at {$clinicData['clinic_address']}.
        The hours are {$clinicData['clinic_hours']}. 
        You are tasked with answering questions about the business, handling requests to appointments, and handling medication refill requests.
        Your main goals are:
        1. Booking Appointments:
        - Ask for their full name.
        - Ask for the purpose of their appointment.
        - Request their preferred date and time for the appointment.
        - Confirm all details with the caller, including the date and time of the appointment.
        2. Medication Refills:
        - Ask for the patient's full name.
        - Request the name of the medication they need refilled.
        - Ask if there have been any changes in their health or other medications since their last visit.
        - Inform them that their request will be reviewed by Dr. Smith and they will be contacted if there are any issues.
        {$clinicData['additional_clinic_goals']}
        3. Other Topics:
        - For any other topics or requests, inform the caller that the practice will be notified and follow up shortly.
        General Guidelines:
        - Be kind of funny and witty!
        - Keep all your responses short and simple. Use casual language, phrases like 'Umm...', 'Well...', and 'I mean' are preferred.
        - This is a voice conversation, so keep your responses short, like in a real conversation. Don't ramble for too long.
        - Always maintain a friendly and efficient manner when interacting with callers.";
    }

    private function buildRequestPayload($clinicData, $content)
    {
        return [
            'name' => $clinicData['clinic_name'],
            'transcriber' => [
                'provider' => 'deepgram',
                'model' => 'nova-2',
                'language' => 'multi',
            ],
            'model' => [
                'provider' => 'openai',
                'model' => 'gpt-4',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $content,
                    ],
                ],
            ],
            'firstMessage' => "Hello, this is Ava. How may I assist you today? You can make an appointment booking, a medication refill or another. Please let me know.",
            'endCallMessage' => 'Thanks for your time. Goodbye!',
            'endCallPhrases' => ['Goodbye'],
            'voice' => [
                'inputPreprocessingEnabled' => true,
                'inputReformattingEnabled' => true,
                'inputMinCharacters' => 30,
                'inputPunctuationBoundaries' => [
                    "。", "，", ".", "!", "?", ";", "،", "۔", "।", "॥", "|", "||", ",", ":"
                ],
                'fillerInjectionEnabled' => true,
                'provider' => 'cartesia',
                'voiceId' => 'f9836c6e-a0bd-460e-9d3c-f7299fa60f94',
            ],
            'silenceTimeoutSeconds' => 30,
            'backgroundSound' => 'office',
        ];
    }

    public function setAssistant($clinicData)
    {
        $content = $this->buildContent($clinicData);
        $payload = $this->buildRequestPayload($clinicData, $content);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl . '/assistant', $payload);

        if ($response->successful()) {
            return $response->json()['id'];
        }
    }

    public function updateAssistant($assistantId, $clinicData)
    {
        $content = $this->buildContent($clinicData);
        $payload = $this->buildRequestPayload($clinicData, $content);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->patch($this->apiUrl . '/assistant/' . $assistantId, $payload);

        if ($response->successful()) {
            return $response->json()['id'];
        }
    }

    public function deleteAssistant($assistantId)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->delete($this->apiUrl . '/assistant/' . $assistantId);

        if ($response->successful()) {
            return $response->json();
        }
    }
}
     
    
