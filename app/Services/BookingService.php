<?php

namespace App\Services;

class BookingService
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
        $routes = app(ApiService::class)->get('/booking', ['start_date' => '2025-09-01', 'end_date' => '2025-09-30']);

        if (isset($routes['data'])) {
            return ($routes['data']);
        }
        return ($routes['data']);
    }
}
