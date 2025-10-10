<?php
namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CalendarHelper
{
    public static function getMonthCalendar($date = null)
    {
        $date = empty($date) ? Carbon::now() : Carbon::createFromDate($date);
        $startOfCalendar = $date->copy()->firstOfMonth()->startOfWeek(Carbon::SUNDAY);
        $endOfCalendar = $date->copy()->lastOfMonth()->endOfWeek(Carbon::SATURDAY);
        $month = $date->format('n');

        //Log::debug($startOfCalendar);
        //Log::debug($endOfCalendar);

        //Build data to array
        $data = [];
        while ($startOfCalendar <= $endOfCalendar) {
            array_push($data, [
                'day' => $startOfCalendar->format('j'),
                'day_name' => $startOfCalendar->format('l'),
                'day_of_week' => $startOfCalendar->format('N'),
                'current_month' => $startOfCalendar->format('n') == $month ? 'Y' : 'N',
                'date' => $startOfCalendar->format('Y-m-d'),
                'fulldate' => $startOfCalendar,
            ]);

            $startOfCalendar->addDay();
        }

        $rows = array_chunk($data, 7);

        return $rows;
    }

    public static function getYearCalendar($year = null)
    {
        $dt = Carbon::now();
        $year = empty($year) ? $dt->year : $year;
        $date = Carbon::createFromDate($year.'-01-01');

        $items = [];
        for ($i = 1; $i <= 12; $i++) {
            $startOfCalendar = $date->copy()->firstOfMonth()->startOfWeek(Carbon::SUNDAY);
            $endOfCalendar = $date->copy()->lastOfMonth()->endOfWeek(Carbon::SATURDAY);
            $month = $date->format('n');

            //Build data to array
            $data = [];
            while ($startOfCalendar <= $endOfCalendar) {
                array_push($data, [
                    'day' => $startOfCalendar->format('j'),
                    'day_name' => $startOfCalendar->format('l'),
                    'day_of_week' => $startOfCalendar->format('N'),
                    'current_month' => $startOfCalendar->format('n') == $month ? 'Y' : 'N',
                    'date' => $startOfCalendar->format('Y-m-d'),
                    'fulldate' => $startOfCalendar,
                ]);

                $startOfCalendar->addDay();
            }

            $rows = array_chunk($data, 7);

            array_push($items,[
                'calendar'=>$rows,
                'name'=>$date->format('F').' '.$year
            ]);
            $date->addMonthsNoOverflow(1);
        }

        //Log::debug($items);

        return $items;
    }

    public static function getYearRangCalendar($startYear,$endYear)
    {
        $dt = Carbon::now();
        //$year = empty($year) ? $dt->year : $year;
        $date = Carbon::createFromDate($startYear.'-01-01');
        $endDate = Carbon::createFromDate($startYear.'-01-01');

        $items = [];
        $monthAmt = (((int)$endYear-(int)$startYear)+1)*12;
        if($monthAmt <0){
            return $items;
        }

        for ($i = 1; $i <= $monthAmt; $i++) {
            $startOfCalendar = $date->copy()->firstOfMonth()->startOfWeek(Carbon::SUNDAY);
            $endOfCalendar = $date->copy()->lastOfMonth()->endOfWeek(Carbon::SATURDAY);
            $month = $date->format('n');
            $year = $date->format('Y');

            //Build data to array
            $data = [];
            while ($startOfCalendar <= $endOfCalendar) {
                array_push($data, [
                    'day' => $startOfCalendar->format('j'),
                    'day_name' => $startOfCalendar->format('l'),
                    'day_of_week' => $startOfCalendar->format('N'),
                    'current_month' => $startOfCalendar->format('n') == $month ? 'Y' : 'N',
                    'date' => $startOfCalendar->format('Y-m-d'),
                    'fulldate' => $startOfCalendar,
                ]);

                $startOfCalendar->addDay();
            }

            $rows = array_chunk($data, 7);

            array_push($items,[
                'calendar'=>$rows,
                'name'=>$date->format('F').' '.$year
            ]);
            $date->addMonthsNoOverflow(1);
        }

        //Log::debug($items);

        return $items;
    }
}
