<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class VapiService
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('vapi.api_key');
        $this->apiUrl = config('vapi.api_url');
    }

    public function initiateCall($phoneNumber)
    {

        $clinic_name = "Saint Peter Clinic";
        $clinic_address = "123 Main Street, Toronto";
        $clinic_hours = "Monday to Friday, 9:00 AM to 5:00 PM";
        $aditional_clinic_goals = "See for available appointments.
        - Provide information about the clinic's services (e.g., general check-ups, vaccinations, etc.).
        - Provide information about the clinic's location and hours of operation.
        - Provide information about the clinic's COVID-19 safety measures.
        - Provide information about the clinic's payment options.";

        $content = "You are a voice assistant for $clinic_name, a medical office located at $clinic_address.
         The hours are $clinic_hours. 
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

        $aditional_clinic_goals

        3. Other Topics:
        - For any other topics or requests, inform the caller that the practice will be notified and follow up shortly.

        General Guidelines:
        - Be kind of funny and witty!
        - Keep all your responses short and simple. Use casual language, phrases like 'Umm...', 'Well...', and 'I mean' are preferred.
        - This is a voice conversation, so keep your responses short, like in a real conversation. Don't ramble for too long.
        - Always maintain a friendly and efficient manner when interacting with callers.
        " ;



        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl . '/call', [
            'assistantId' => config('vapi.assistant_id'),
            'phoneNumberId' => 'df29106b-87e0-4f30-ae2a-b84ba20a5474',
            'assistant' => [
                'model' => [
                        "provider" => "openai",
                        "model" => "gpt-4",
                        'messages' => [
                            [
                                'role' => 'system',
                                'content' => $content,
                            ],
                        ],
                    ],
                    'firstMessage' => "Hello, this is Gabriela. How may I assist you today? You can make an appointment booking, a medication refill or another. Please let me know.",
                    'endCallMessage' => 'Thanks for your time. Goodbye!',
                ],
                'customer' => [
                    'number' => $phoneNumber,
                ],
        ]);

        if ($response->successful()) {
            return $response->json()['id'];
        }

        throw new \Exception('Failed to initiate Vapi AI call: ' . $response->body());
    }

    public function getCallStatus($callId)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->get($this->apiUrl . '/call/' . $callId);

        if ($response->successful()) {
            return $response->json()['status'];
        }

        throw new \Exception('Failed to get Vapi AI call status: ' . $response->body());
    }
}