<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Services\VapiService;
use Illuminate\Http\Request;

class ClinicController extends Controller
{

    public function __construct(protected VapiService $vapiService)
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
        $data['id'] = $request->route('id');
        $clinicId = Clinic::upsert(
            $data,
            ['id'],
            $data
        );
        $assistantId = $this->vapiService->setAssistant($data);
        $number = $data["clinic_phone_number"];
        $numberId = $this->vapiService->getNumberId($data["clinic_phone_number"]);
        if (!$numberId) {
            $numberId = $this->vapiService->createPhoneNumber($number);
        }
        $this->vapiService->setPhoneAssistantId($numberId, $assistantId);


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
}