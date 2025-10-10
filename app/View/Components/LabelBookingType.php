<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LabelBookingType extends Component
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
        $types = [
            'O' => 'One Way',
            'R' => 'Return',
            'M' => 'Multi-City',
            'M1' => 'Trip 1',
        ];
        return view('components.label-booking-type', [
            'types' => $types
        ]);
    }
}
