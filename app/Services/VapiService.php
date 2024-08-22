<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Repositories\VapiPhoneRepository;
use App\Repositories\VapiAssistantRepository;

class VapiService
{
    protected $apiKey;
    protected $apiUrl;

    public function __construct(protected VapiPhoneRepository $vapiPhoneRepository,
                                protected VapiAssistantRepository $vapiAssistantRepository)
    {
        $this->apiKey = config('vapi.api_key');
        $this->apiUrl = config('vapi.api_url');
    }

   

    public function getCallStatus($assistantId)
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
        ])->get($this->apiUrl . '/call?assistantId='.$assistantId.'&limit=1');

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('Failed to get Vapi AI call status: ' . $response->body());
    }

    public function setAssistant($clinicData)
    {
        return $this->vapiAssistantRepository->setAssistant($clinicData);
    }

    public function updateAssistant( $clinicData)
    {
        $assistantId = $this->vapiPhoneRepository->getAssistantId($clinicData->number);
        return $this->vapiAssistantRepository->updateAssistant($assistantId, $clinicData);
    }

    public function deleteAssistant($assistantId)
    {
        return $this->vapiAssistantRepository->deleteAssistant($assistantId);
    }

    public function createPhoneNumber(int $number)
    {
        return $this->vapiPhoneRepository->createPhoneNumber($number);
    }

    public function getNumberId($number)
    {
        return $this->vapiPhoneRepository->getNumberId($number);
    }

    public function updatePhoneNumber($number)
    {
        $assistantId = $this->vapiPhoneRepository->getAssistantId($number);
        return $this->vapiPhoneRepository->updatePhoneNumber($number, $assistantId);
    }

   

    public function deletePhoneNumber($number)
    {
        return $this->vapiPhoneRepository->deletePhoneNumber($number);
    }

    public function getPhoneAssistantId($number)
    {
        return $this->vapiPhoneRepository->getAssistantId($number);
    }

    public function setPhoneAssistantId($number, $assistantId)
    {
        return $this->vapiPhoneRepository->setAssistantId($number, $assistantId);
    }


    


   

    

}