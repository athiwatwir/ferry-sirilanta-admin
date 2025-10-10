<?php

namespace App\View\Components\Route;

use App\Http\Controllers\RouteController;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class IconSet extends Component
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
        return view('components.route.icon-set', [
            'ferryTypes' => RouteController::ferryTypes()
        ]);
    }
}
