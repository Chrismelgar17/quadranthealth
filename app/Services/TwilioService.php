<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(
            config('twilio.account_sid'),
            config('twilio.auth_token')
        );
    }

    public function forwardCall($to)
    {
        return $this->client->calls->create(
            $to,
            config('twilio.twilio_number'),
            ['url' => route('voice.twiml')]
        );
    }

    public function detectVoicemail($callSid)
    {
        // Implement voicemail detection logic
        // This is a placeholder and needs to be implemented based on Twilio's capabilities
        $call = $this->client->calls($callSid)->fetch();
        return $call->status === 'no-answer';
    }
}