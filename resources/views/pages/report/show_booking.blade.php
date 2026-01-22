@extends('layouts.default')

@section('content')
<x-card>
    <div class="row">
        <div class="col-12">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>InvoiceNo.</th>
                        <th>TicketNo.</th>
                        <th>Route</th>
                        <th>Lead Name</th>
                        <th>Email</th>
                        <th>Passenger</th>
                        <th>API</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $booking)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $booking->invoiceno }}</td>
                        <td>{{ $booking->ticketno }}</td>
                        <td>
                            {{ $booking->route_name }}<br>
                            <x-label-time :time="$booking->depart_time" />/
                            <x-label-time :time="$booking->arrival_time" />
                        </td>
                        <td>{{ $booking->fullname }}</td>
                        <td>{{ $booking->email }}</td>
                        <td>{{ $booking->adult_passenger }}</td>
                        <td>{{ $booking->agent_name }}</td>
                        <td>
                            <x-label-booking-status :status="$booking->status" />
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-card>
@stop
