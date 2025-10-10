<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionApiController extends Controller
{
    public function index(Request $request)
    {
        $agent = $request->user();

        $promotions = Promotion::all();

        return response()->json([
            'success' => true,
            'data'    => $promotions,
        ], 200);
    }

    public function detail(Request $request, $id)
    {
        $agent = $request->user();

        $promotion = Promotion::whereId($id)->first();

        return response()->json([
            'success' => true,
            'data'    => $promotion,
        ], 200);
    }
}
