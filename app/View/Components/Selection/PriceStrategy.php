<?php

namespace App\View\Components\Selection;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class PriceStrategy extends Component
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
        $priceStrategies = \App\Models\PriceStrategy::where('isactive', 'Y')->orderBy('name')->get()->pluck('name', 'id');
        return view('components.selection.price-strategy', ['priceStrategies' => $priceStrategies]);
    }
}
