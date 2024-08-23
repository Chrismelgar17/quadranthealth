<?php

namespace App\Repositories;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class VapiPhoneRepository
{
    private $apiKey;
    private $apiUrl;
    public function __construct()
    {
        $this->apiKey = config('vapi.api_key');
        $this->apiUrl = config('vapi.api_url');
    }

    public function createPhoneNumber(int $number)
    {
        $number = '+' . $number;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->post($this->apiUrl . '/phone-number', [
            'provider' => 'twilio',
            'number' => $number,
            'twilioAccountSid' => config('twilio.account_sid'),
            'twilioAuthToken' => config('twilio.auth_token'),
        ]);

        if ($response->successful()) {
            return $response->json();
        }
        else {
            Log::error('Failed to create phone number: ' . $response->body());
            return false;
        }

    }

    public function getNumberId($number)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->get($this->apiUrl . '/phone-number');

        if ($response->successful()) {
            $response = $response->json();
            foreach ($response as $item) {
                if ($item['number'] === $number) {
                    return $item['id'];
                }
            }
            return false;
        }

        throw new \Exception('Failed to get phone number ID: ' . $response->body());
    }

    public function updatePhoneNumber($number, $assistantId)
    {
        $numberId = $this->getNumberId($number);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->patch($this->apiUrl . '/phone-number/' . $numberId,  [
            'provider' => 'twilio',
            'number' => $number,
            'twilioAccountSid' => config('twilio.account_sid'),
            'twilioAuthToken' => config('twilio.auth_token'),
        ]);

        if ($response->successful()) {
            return $response->json();
        }
    }

    public function deletePhoneNumber($number)
    {
        $numberId = $this->getNumberId($number);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->delete($this->apiUrl . '/phone-number/' . $numberId);

        if ($response->successful()) {
            return $response->json();
        }
    }

    public function getAssistantId()
    {

        $assistantId = false;
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->get($this->apiUrl . '/phone-number');

        if ($response->successful()) {
            $twilioAccountSid = config('twilio.account_sid');
            $response = $response->json();

            foreach ($response as $item) {
            if ($item['twilioAccountSid'] === $twilioAccountSid && $assistantId == false) {
                $assistantId = $item['assistantId']??false;
            }
               return $assistantId;
            throw new \Exception('Twilio account SID mismatch');
        }
    }
}

    public function setAssistantId($numberId, $assistantId)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->patch($this->apiUrl . '/phone-number/' . $numberId,  [
            'assistantId' => $assistantId,
        ]);

        if ($response->successful()) {
            return $response->json();
        }
    }
    
        

}