<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Information;
use Illuminate\Http\Request;

class InformationTextApiController extends Controller
{
    public function index(Request $request)
    {
        $agent = $request->user();

        $informations = Information::where('agent_id', $agent->id)->orderBy('position')->get();

        return response()->json([
            'success' => true,
            'data'    => $informations,
        ], 200);
    }
}
