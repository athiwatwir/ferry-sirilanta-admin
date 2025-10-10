<?php

namespace App\Helpers;

use Carbon\Carbon;


class UtilHelper
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function dmYToYmd($date = '')
    {
        if (empty($date)) {
            return '';
        }
        $convertedDate = Carbon::createFromFormat('d/m/Y', $date)->format('Y-m-d');

        return  $convertedDate;
    }
}
