<?php

namespace App\View\Components\Station;

use App\Services\StationHelper;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LabelType extends Component
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
        $types = StationHelper::getTypes();

        return view('components.station.label-type', [
            'types' => $types
        ]);
    }
}
