<?php

namespace App\View\Components;

use App\Services\BookingService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BookingSearchForm extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        private bookingService $bookingService
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $tripTypes = $this->bookingService->getTripType();
        $bookChannels  = $this->bookingService->getBookingChannel();

        return view('components.booking-search-form', compact('tripTypes', 'bookChannels'));
    }
}
