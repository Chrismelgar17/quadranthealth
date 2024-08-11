<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VAPIService;
use App\Services\DecodingService;

class VAPIController extends Controller
{
    
    public function __construct(protected VAPIService $vapiService,
    protected DecodingService $decodingService)
    {
    }


    public function gatherCalls(Request $request)
    {
        $response = $this->vapiService->callVAPIService();
        // Extract the transcript key
        $transcript = $this->decodingService->decodeResponse($response);

        return response()->json(['transcript' => $transcript]);
    }


}
