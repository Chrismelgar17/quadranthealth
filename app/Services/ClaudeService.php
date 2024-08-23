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
            'system' => 'You are an AI assistant processing call transcripts. Summarize the key points of the conversation. Gather transcript of call and run through claude sonnet API to generate the message, summary of the conversation and what the patient’s request was, with all the necessary information. Be consistent and summarize the conversation in a clear and concise manner. Return just the summary without introductions or conclusions like "summary:" or "here is the summary :".',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => "$transcript",
                ],
            ],
        ]);

        $data = $response->json();
        if (isset($data['content'][0]['text'])) {
            $summary = $data['content'][0]['text'];
        }
        return $summary;
        // return $data;
    }
}