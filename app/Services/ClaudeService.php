<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ClaudeService
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->apiKey = config('claude.api_key');
        $this->apiUrl = config('claude.api_url');
    }

    public function processTranscript($transcript)
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-api-key' => $this->apiKey,
            'anthropic-version' => '2023-06-01',
        ])->post($this->apiUrl, [
            'model' => 'claude-3-sonnet-20240229',
            'max_tokens' => 1024,
            'system' => 'You are an AI assistant processing call transcripts. Summarize the key points of the conversation. Gather transcript of call and run through claude sonnet API to generate the message, following the format below. (Make sure all the AI API calls have error handling, backoff retry.) Line 1: “INBOUND PHONE CALL” \n Line 2: \n Line 3: “Request Type:” [Medication Refill / Scheduling / Other] \n Line 4: \n Line 5: Summary of the conversation and what the patient’s request was, with all the necessary information. Put data into a JSON. ',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => "$transcript",
                ],
            ],
        ]);

        return $response->json();
    }
}