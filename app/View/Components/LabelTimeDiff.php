<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Carbon\Carbon;

class LabelTimeDiff extends Component
{
    public $fromTime;
    public $fromTz;
    public $toTime;
    public $toTz;

    public $hours;
    public $minutes;
    /**
     * Create a new component instance.
     */
    public function __construct($fromTime, $toTime, $toTz = 'Asia/Bangkok', $fromTz = 'Asia/Bangkok')
    {
        $this->fromTime = $fromTime;
        $this->fromTz   = $fromTz;
        $this->toTime   = $toTime;
        $this->toTz     = $toTz;

        // สร้าง Carbon ตาม timezone
        $from = Carbon::createFromFormat('H:i', $fromTime, $fromTz);
        $to   = Carbon::createFromFormat('H:i', $toTime, $toTz);

        // คำนวณต่าง (เปลี่ยนให้เป็น timezone เดียวกันก่อน)
        $diffInMinutes = $from->diffInMinutes($to);

        $this->hours   = intdiv($diffInMinutes, 60);
        $this->minutes = $diffInMinutes % 60;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.label-time-diff');
    }
}
