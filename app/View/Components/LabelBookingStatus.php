<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LabelBookingStatus extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $statuses = [
            'DR' => ['text' => 'Non Approved', 'class' => 'text-secondary', 'icon'],
            'CO' => ['text' => 'Approved', 'class' => 'text-success', 'icon'],
            'VO' => ['text' => 'Canceled', 'class' => 'text-danger', 'icon'],
        ];
        return view('components.label-booking-status', compact('statuses'));
    }
}
