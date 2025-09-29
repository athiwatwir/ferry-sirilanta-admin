<?php

namespace App\Services;

class InformationService
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
        $information = app(ApiService::class)->get('/information');
        return ($information['data']);
    }
}
