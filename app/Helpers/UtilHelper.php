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

    public static function parseDateRange(?string $daterange): array
    {
        if (empty($daterange)) {
            return [Carbon::now()->format('Y-m-d'), Carbon::now()->format('Y-m-d')];
        }

        // แยกวันเริ่มและวันสิ้นสุด
        $dates = explode('-', $daterange);

        if (count($dates) !== 2) {
            return [Carbon::now()->format('Y-m-d'), Carbon::now()->format('Y-m-d')];
        }

        // ตัดช่องว่างออก
        $start = trim($dates[0]);
        $end = trim($dates[1]);

        // แปลงเป็น format Y-m-d
        try {
            $startDate = Carbon::createFromFormat('d/m/Y', $start)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('d/m/Y', $end)->format('Y-m-d');
        } catch (\Exception $e) {
            return [Carbon::now()->format('Y-m-d'), Carbon::now()->format('Y-m-d')];
        }

        return [$startDate, $endDate];
    }
}
