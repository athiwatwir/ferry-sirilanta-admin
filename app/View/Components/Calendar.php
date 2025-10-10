<?php

namespace App\View\Components;

use App\Helpers\CalendarHelper;
use App\Models\Agent;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\Cache;

class Calendar extends Component
{
    public $date;
    /**
     * Create a new component instance.
     */
    public function __construct($date = null)
    {
        $this->date = $date;
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

        //agent profile
        $agents = Cache::remember('active_api_agents', (60 * 60 * 24 * 30), function () {
            return Agent::select('id', 'name', 'logo')
                ->where('isactive', 'Y')
                ->where('is_use_api', 'Y')
                ->get();
        });

        return view('components.calendar', compact('data', 'title', 'agents'));
    }
}
