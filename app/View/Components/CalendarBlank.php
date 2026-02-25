<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Carbon\Carbon;
use App\Helpers\CalendarHelper;

class CalendarBlank extends Component
{
    public $date;

    /** @var \Illuminate\Support\Collection|array date => total */
    public $dailyTotals;

    /**
     * Create a new component instance.
     */
    public function __construct($date = null, $dailyTotals = [])
    {
        $this->date = $date;
        $this->dailyTotals = is_array($dailyTotals) ? collect($dailyTotals) : $dailyTotals;
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $date = $this->date;
        $title = '';

        if (empty($date)) {
            $date = Carbon::now()->format('Y-m-d');
            $title = Carbon::now()->format('F Y');
        } else {
            $title = Carbon::parse($date)->format('F Y');
        }
        $data = CalendarHelper::getMonthCalendar($this->date);

        return view('components.calendar-blank', [
            'data' => $data,
            'title' => $title,
            'dailyTotals' => $this->dailyTotals,
        ]);
    }
}
