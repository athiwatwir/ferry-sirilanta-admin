<!DOCTYPE html>
<html>
@php
    //$data = file_get_contents('https://andamanexpress.com/tiger-line-ferry_logo-header-3.jpg');
    //$base64 = 'data:image/jpg;base64,' . base64_encode($data);
    $colors = [
        'one-way' => '#0580c4',
        'round-trip' => '#00bf63',
        'multi-trip' => '#ff6100',
    ];
@endphp

<head>
    <title>Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


        @include('print.inc_ticket.style')
</head>

<body>
    @foreach ($bookings as $index => $booking)
        @php

            $ticket = $booking['tickets'][0];
            $user = $booking['user'];
            $extras = $booking['bookingRoutesX'];
            $bookingRoutes = $booking['bookingRoutes'];
            $bookingRoutesX = $booking['bookingRoutesX'];
            $customers = $booking['bookingCustomers'];
            $firstCustomer = $customers[0];
            foreach ($customers as $key => $customer) {
                if($customer->pivot->isdefault == 'Y'){
                    $firstCustomer = $customer;
                    break;
                }
            }

            $payment = sizeof($booking['payments']) > 0 ? $booking['payments'][0] : null;

            $paymentDetails = json_decode($payment['description']);
            $referenceNo = isset($paymentDetails->referenceNo)?$paymentDetails->referenceNo:'';
            //dd($approveCode);
            $approveCode = isset($paymentDetails->approvalCode)?$paymentDetails->approvalCode:'';

        @endphp

        <div class="{{ $booking['book_channel'] == 'ADMIN' ? 'bg-staff' : '' }}">

            @include('print.inc_ticket.head')

            @include('print.inc_ticket.bookdetail')

            @include('print.inc_ticket.travel')
            @include('print.inc_ticket.checkin')

            @include('print.inc_ticket.passenger')

            @include('print.inc_ticket.footer')
        </div>
        @if (sizeof($bookings) != ($index+1))
            <div style="page-break-before:always;"> </div>
        @endif
    @endforeach
</body>

</html>
