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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


    @include('print.inc_ticket.style')
</head>


<body>
    @foreach ($bookings as $index => $booking)
    @php

    $user = isset($booking['user'])?[]:$booking['user'];
    $extras = $booking['bookingRoutesX']??[];
    $bookingRoutes = $booking['bookingSubRoutes'];
    //$bookingRoutesX = $booking['bookingRoutesX'];
    $customers = $booking['bookingCustomers'];
    $firstCustomer = $customers[0];
    $firstCustomer = $customers[0];
    foreach ($customers as $key => $customer) {
    if($customer->pivot->isdefault == 'Y'){
    $firstCustomer = $customer;
    break;
    }
    }

    //$payment = sizeof($booking['payments']) > 0 ? $booking['payments'][0] : null;


    //$paymentDetails = json_decode($payment['description']);
    $referenceNo = isset($paymentDetails->referenceNo) ? $paymentDetails->referenceNo : '';

    $approveCode = isset($paymentDetails->approvalCode) ? $paymentDetails->approvalCode : '';

    @endphp

    @foreach ($bookingRoutes as $i => $bookingRoute)

    <div class="{{ $booking['book_channel'] == 'ADMIN' ? 'bg-staff' : '' }}">

        @include('print.inc_ticket.head')

        @include('print.inc_ticket.bookdetail')

        @include('print.inc_ticket.travel')
        @include('print.inc_ticket.checkin')

        @include('print.inc_ticket.passenger')

        @include('print.inc_ticket.footer')
    </div>
    @if(($i+1) != sizeof($bookingRoutes))
    <div style="page-break-before:always;"> </div>
    @endif


    @endforeach


    @if (sizeof($bookings) != $index + 1)
    <div style="page-break-before:always;"> </div>
    @endif
    @endforeach
</body>

</html>
