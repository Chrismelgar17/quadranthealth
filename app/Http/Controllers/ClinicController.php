<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Services\VapiService;
use Illuminate\Http\Request;

class ClinicController extends Controller
{

    public function __construct(protected VapiService $vapiService, protected TwilioWebhookController $twilioWebhookController)
    {
    }

    public function getClinics()
    {
        return response()->json(Clinic::getAll());
    }

    public function getClinicById($id)
    {
        $clinic = Clinic::getClinicById($id);
        if ($clinic) {
            return response()->json($clinic);
        }
        return response()->json(['message' => 'Clinic not found'], 404);
    }

    public function upsertClinic(Request $request)
    {
        $data = $request->all();
        $numberId = $this->getOrCreatePhoneNumberId($data);

        if (!$numberId) {
            return response()->json(['message' => 'Failed to create phone number, please add it to Twilio account.'], 500);
        }

        if(null !== $request->route('id')) {
            $data['id'] = $request->route('id');
        }

        
        $clinicId = Clinic::upsertClinic($data);
        $this->twilioWebhookController->setWebhooks($data['clinic_phone_number']);
        return response()->json(['id' => $clinicId]);
    }

    public function deleteClinic($id)
    {
        $deleted = Clinic::deleteClinic($id);
        if ($deleted) {
            return response()->json(['message' => 'Clinic deleted successfully']);
        }
        return response()->json(['message' => 'Clinic not found'], 404);
    }

    private function getOrCreatePhoneNumberId($clinicData)
    {
        $phoneNumber = $clinicData['clinic_phone_number'];
        $numberId = $this->vapiService->getNumberId($phoneNumber);
        if (!$numberId) {
            $numberId = $this->vapiService->createPhoneNumber($phoneNumber);
            $assistantId = $this->vapiService->setAssistant($clinicData);
            $this->vapiService->setPhoneAssistantId($phoneNumber, $assistantId);
        }else	{
            $assistantId = $this->vapiService->updateAssistant($clinicData);
            $this->vapiService->setPhoneAssistantId($phoneNumber, $assistantId);
        }
        return $numberId;
    }

   
}