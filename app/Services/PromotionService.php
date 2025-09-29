<?php

namespace App\Services;

class PromotionService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getAll()
    {
        $data = app(ApiService::class)->get('/promotion');
        return ($data['data']);
    }

    public function get($id)
    {
        $data = app(ApiService::class)->get('/promotion/' . $id);
        return ($data['data']);
    }
}
