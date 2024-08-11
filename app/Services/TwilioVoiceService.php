<?php
namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\URL;

class TwilioVoiceService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client(
            config('twilio.sid'),
            config('twilio.auth_token')
        );
    }

    public function makeCall($to, $url)
    {
        $twimlUrl = URL::to(route('voice.twiml'));

        return $this->client->calls->create(
            $to,
            config('twilio.from_number'),
            [
                "url" => "http://demo.twilio.com/docs/voice.xml",
                'applicationSid' => config('twilio.twiml_app_sid')
            ]
        );
    }
}