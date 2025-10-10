<?php

namespace App\Services;

use App\Models\Booking;

class BookingService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }



    public function createBooking() {}

    public static function getBookingInfoByBookingNo($bookingno)
    {
        $booking = Booking::where(['bookingno' => $bookingno])
            ->with('bookingCustomers', 'user', 'bookingRoutes', 'bookingRoutesX.tickets', 'bookingRoutesX.bookingExtraAddons', 'bookingRoutes.station_from', 'bookingRoutes.station_to', 'payments')
            ->first();

        //dd($booking);

        return $booking;
    }

    public static function status()
    {
        $status = [
            'DR' => ['title' => 'Non Approved', 'icon' => '<i class="fi fi-circle-spin"></i>', 'class' => '', 'action' => ''],
            'UNP' => ['title' => 'On Hold', 'icon' => '<i class="fa-solid fa-clock-rotate-left"></i>', 'class' => 'text-warning', 'action' => 'Unpaid'],
            'CO' => ['title' => 'Approved', 'icon' => '<i class="fa-solid fa-check-double"></i>', 'class' => 'text-success', 'action' => 'Paid'],
            'VO' => ['title' => 'Cancelled', 'icon' => '<i class="fa-solid fa-xmark"></i>', 'class' => 'text-danger', 'action' => 'Cancel'],
            'amended' => ['title' => 'Amended', 'icon' => '<i class="fa-solid fa-list-check"></i>', 'class' => 'text-blue-900', 'action' => ''],
            'delete' => ['title' => 'Deleted', 'icon' => '<i class="fa-solid fa-trash"></i>', 'class' => 'text-danger', 'action' => 'Delete'],

        ];

        return $status;
    }

    public function getTripType()
    {
        return [
            'O' => 'One-Way',
            'R' => 'Return',
            'M' => 'Multiple'
        ];
    }

    public function getBookingChannel()
    {
        return [];
    }
}
