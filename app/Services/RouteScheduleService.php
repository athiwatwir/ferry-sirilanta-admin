<?php

namespace App\Services;

use App\Models\RouteSchedule;
use App\Models\RouteScheduleDaily;
use Illuminate\Support\Str;
use Carbon\Carbon;

class RouteScheduleService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }


    public function dailyProcess($startDate, $endDate, $dayOfWeeks, $isopen, $subRouteIds)
    {

        if (empty($dayOfWeeks)) {
            $dayOfWeeks = ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'];
        }

        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        $dayMap = [
            'MON' => 1,
            'TUE' => 2,
            'WED' => 3,
            'THU' => 4,
            'FRI' => 5,
            'SAT' => 6,
            'SUN' => 7,
        ];

        $allowedDays = collect($dayOfWeeks)->map(fn($day) => $dayMap[$day])->toArray();

        $currentDate = $startDate->copy();
        $insertData = [];
        $insertDates = [];

        while ($currentDate <= $endDate) {
            if (in_array($currentDate->dayOfWeekIso, $allowedDays)) {
                foreach ($subRouteIds as $subRouteId) {
                    $insertData[] = [
                        'id' => (string) Str::uuid(),
                        'sub_route_id' => $subRouteId,
                        'date' => $currentDate->format('Y-m-d'),
                        'isopen' => $isopen,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $insertDates[] = $currentDate->format('Y-m-d');
                }
            }
            $currentDate->addDay();
        }

        //clear date
        RouteScheduleDaily::whereIn('date', $insertDates)->delete();
        // Bulk insert รวดเดียว
        RouteScheduleDaily::insert($insertData);
    }
}
