@extends('layouts.default')

@section('content')
<style>
    .booking-table td {
        padding: 5px;
    }

</style>
<x-card>
    <div class="row">
        <div class="col-12 text-end mb-3">
            <x-button.new :href="route('booking.routeFillter')" text="Create New Booking" />
        </div>
        <div class="col-12">
            <x-table.datatabble class="booking-table">
                <thead>
                    <tr>
                        <th>InvoiceNo.</th>
                        <th>Travel Date</th>
                        <th>Booking Date</th>
                        <th>Ticket No</th>
                        <th>Route</th>
                        <th>Passenger</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $booking)
                    <tr>
                        <td>{{ $booking['invoiceno'] }}</td>
                        <td>
                            <x-label-date :date="$booking['departdate'] " />
                        </td>
                        <td>
                            <x-label-date-time :datetime="$booking['created_at'] " />
                        </td>
                        <td>{{ $booking['ticketno'] }}</td>
                        <td></td>
                        <td>{{ $booking['adult_passenger']+$booking['child_passenger']+$booking['infant_passenger'] }}</td>
                        <td>
                            <x-label-price :price="$booking['totalamt']" />
                        </td>
                        <td> <small>
                                <x-label-booking-status :status="$booking['status']" /></small></td>
                        <td class="text-end">
                            <x-button.dropdown editUrl="" :deleteUrl="route('booking.destroy',['booking'=>$booking['id']])">
                                <li>
                                    <a class="dropdown-item" href="{{ env('PDF_API') }}/{{ $booking['invoiceno'] }}" target="_blank"><i class="icon-base ti tabler-file-type-pdf icon-22px"></i> Print Ticket</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('booking.show',['booking'=>$booking['id']]) }}"><i class="icon-base ti tabler-device-projector icon-22px"></i> View</a>
                                </li>
                            </x-button.dropdown>
                        </td>
                    </tr>

                    @endforeach
                </tbody>
            </x-table.datatabble>
        </div>
    </div>
</x-card>

@stop
