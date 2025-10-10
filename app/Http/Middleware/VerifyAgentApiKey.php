<?php

namespace App\Http\Middleware;

use App\Models\Agent;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyAgentApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-KEY');

        if (!$apiKey) {
            return response()->json(['message' => 'API key is required'], 401);
        }

        $agent = Agent::where('api_key', $apiKey)
            ->where('is_use_api', 'Y')
            ->where('isactive', 'Y')
            ->first();

        if (!$agent) {
            return response()->json(['message' => 'Invalid or inactive API key'], 403);
        }

        // แชร์ข้อมูล agent ให้ controller ใช้
        // $request->merge(['agent' => $agent]);

        $request->setUserResolver(fn() => $agent);

        return $next($request);
    }
}
