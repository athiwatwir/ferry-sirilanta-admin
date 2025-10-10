<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Information;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PrintController extends Controller
{
    //

    public function ticket($bookingno = null)
    {
        $booking = Booking::where('bookingno', $bookingno)->with(['agent', 'bookingSubRoutes', 'bookingCustomers'])->first();
        $term = Information::where('position', 'TERM_TICKET')->where('agent_id', $booking->agent_id)->first();
        $statusLabel = BookingService::status();
        $bookings[] = $booking;

        Pdf::setOption(['dpi' => 150,  'debugCss' => true]);
        $pdf = Pdf::loadView('print.ticket_v2', ['bookings' => $bookings, 'term' => $term, 'statusLabel' => $statusLabel]);

        /*
        if($booking->ispayment=='N'){
            //dd($booking);
            $pdf = Pdf::loadView('print.ticket_v2_nopayment', ['bookings' => $bookings, 'term' => $term]);
        }
            */

        return $pdf->stream();
    }
}
