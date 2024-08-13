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

    public function processTranscript($transcript, $requestType)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post($this->apiUrl, [
            'model' => 'claude-3-sonnet-20240229',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are an AI assistant processing call transcripts. Summarize the key points of the conversation.',
                ],
                [
                    'role' => 'user',
                    'content' => "Summarize this transcript for a {$requestType} request:\n\n{$transcript}",
                ],
            ],
        ]);

        return $response->json()['choices'][0]['message']['content'];
    }
}