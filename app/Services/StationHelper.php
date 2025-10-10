<?php

namespace App\Services;

class StationHelper
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function getTypes()
    {
        return [
            'island' => 'Island',
            'pier' => 'Pier',
            'airport' => 'Airport',
            'hotel' => 'Hotel',
            'other' => 'Other'
        ];
    }
}
