<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AgentApiController extends Controller
{
    public function info(Request $request)
    {
        $agent = $request->user(); // 👈 สะอาดขึ้น

        return response()->json([
            'id' => $agent->id,
            'name' => $agent->name,
        ], 200);
    }
}
