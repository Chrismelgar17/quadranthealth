<?php

namespace App\Services;

use Twilio\TwiML\VoiceResponse;

class VoiceAIService
{
    public function handleCall($requestType)
    {
        $response = new VoiceResponse();

        switch ($requestType) {
            case 'appointment':
                $this->handleAppointment($response);
                break;
            case 'medication':
                $this->handleMedication($response);
                break;
            default:
                $this->handleOther($response);
        }

        return $response;
    }

    private function handleAppointment(VoiceResponse $response)
    {
        $response->say("Let's schedule your appointment.");
        $response->gather(['input' => 'speech', 'action' => route('voice.appointment')])
            ->say("Please state your full name.");
    }

    private function handleMedication(VoiceResponse $response)
    {
        $response->say("I can help you with medication refills.");
        $response->gather(['input' => 'speech', 'action' => route('voice.medication')])
            ->say("Please state your full name.");
    }

    private function handleOther(VoiceResponse $response)
    {
        $response->say("I'll be happy to assist you with your request.");
        $response->gather(['input' => 'speech', 'action' => route('voice.other')])
            ->say("Please state your full name.");
    }
}