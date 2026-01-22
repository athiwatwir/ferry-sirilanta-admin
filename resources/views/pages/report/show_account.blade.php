@extends('layouts.default')

@section('content')
<x-card>
    <div class="row">
        <div class="col-12">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Payment Date</th>
                        <th>InvoiceNo.</th>
                        <th>TicketNo.</th>
                        <th>Route</th>
                        <th>Customer</th>
                        <th>API</th>
                        <th>List Price</th>
                        <th>Nett Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $booking)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <x-label-date :date="$booking->docdate" />
                        </td>
                        <td>{{ $booking->invoiceno }}</td>
                        <td>{{ $booking->ticketno }}</td>
                        <td>
                            {{ $booking->route_name }}<br>
                            <x-label-time :time="$booking->depart_time" />/
                            <x-label-time :time="$booking->arrival_time" />
                        </td>
                        <td>{{ $booking->fullname }}</td>
                        <td>{{ $booking->agent_name }}</td>
                        <td>
                            <x-label-price :price="$booking->totalamt" />
                        </td>
                        <td>
                            <x-label-price :price="$booking->nettamt" />
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-card>
@stop
