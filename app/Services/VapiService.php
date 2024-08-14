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
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl . '/call', [
            'assistantId' => config('vapi.assistant_id'),
            'phoneNumberId' => 'df29106b-87e0-4f30-ae2a-b84ba20a5474',
            'customer' => [
                'number' => $phoneNumber,
            ],
        ]);

        if ($response->successful()) {
            return $response->json()['call_id'];
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