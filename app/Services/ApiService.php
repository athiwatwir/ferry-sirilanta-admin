<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.app_api.url');
        $this->apiKey = config('services.app_api.key');
    }

    public function get($endpoint, $params = [], $headers = [])
    {
        $response = Http::withHeaders(array_merge([
            'X-API-KEY' => $this->apiKey,
            'Accept'    => 'application/json',
        ], $headers))->get($this->baseUrl . $endpoint, $params);

        return $this->handleResponse($response);
    }

    public function post($endpoint, $data = [], $headers = [])
    {
        $response = Http::withHeaders(array_merge([
            'X-API-KEY' => $this->apiKey,
            'Accept'    => 'application/json',
        ], $headers))->post($this->baseUrl . $endpoint, $data);

        return $this->handleResponse($response);
    }

    protected function handleResponse($response)
    {
        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception('API Error: ' . $response->body(), $response->status());
    }
}
