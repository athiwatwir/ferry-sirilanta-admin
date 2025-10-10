<?php

namespace App\View\Components\Table;

use App\Http\Controllers\PriceStrategyController;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PriceStrategyLine extends Component
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

        return view('components.table.price-strategy-line', [
            'calculateMethods' => PriceStrategyController::$CalculateMethods,
            'calculateTypes' => PriceStrategyController::$CalculateTypes
        ]);
    }
}
