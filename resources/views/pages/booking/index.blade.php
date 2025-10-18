@extends('layouts.default')

@section('content')
<style>
    .booking-table td {
        padding: 5px;
    }

</style>
<x-card>
    <div class="row">
        <div class="col-12">
            @props(['method'=>'GET','action'=>''])
            <form novalidate class="bs-validate" id="frm" method="{{ $method }}" action="{{ $action }}">
                <div class="row">

                    <div class="col-12 col-md-4">
                        <x-station.selection name="depart_station_id" label="Station From" />
                    </div>
                    <div class="col-12 col-md-4">
                        <x-station.selection name="dest_station_id" label="Station To" />
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="trip_type" aria-label="" name="trip_type">
                                <option value="" selected>-- All --</option>
                                @foreach ($tripTypes as $key => $_title)
                                <option value="{{ $key }}" @selected($tripType==$key)>{{ $_title }}</option>
                                @endforeach
                            </select>
                            <label for="trip_type">Trip Type</label>
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="form-floating mb-3">
                            <input autocomplete="off" type="text" name="daterange" id="daterange" class="form-control form-control-sm rangepicker" data-bs-placement="left" data-ranges="false" data-date-start="{{ $startDate }}" data-date-end="{{ $endDate }}" data-date-format="DD/MM/YYYY" data-quick-locale='{
                            "lang_apply"	: "Apply",
                            "lang_cancel" : "Cancel",
                            "lang_crange" : "Custom Range",
                            "lang_months"	 : ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                            "lang_weekdays" : ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"]
                        }'>
                            <label for="departdate">Travel Date</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="ticketno" name="ticketno" value="{{ $ticketno }}">
                            <label for="ticketno">Ticket Number</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="bookingno" name="bookingno" value="{{ $bookingno }}">
                            <label for="bookingno">Invoice Number</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="status" aria-label="" name="status">
                                <option value="" selected>-- All --</option>
                                @foreach ($bookingStatus as $key => $status)
                                <option value="{{ $key }}">{{ $status['title'] }}</option>
                                @endforeach
                            </select>
                            <label for="status">Status</label>
                        </div>
                    </div>

                    <div class="col-12 col-md-3">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="customername" name="customername" value="{{ $customername }}">
                            <label for="customername">Customer</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="email" name="email" value="{{ $email }}">
                            <label for="email">Email</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="search_text" name="search_text" value="{{ $searchText }}">
                            <label for="email">Search Text</label>
                        </div>
                    </div>
                    <div class="col-6">
                        <x-button.new text="Create New Booking" :href="route('booking.flight')" />
                    </div>
                    <div class="col-6 text-end">
                        <a class="btn btn-secondary" href="{{ route('booking.index') }}"><i class="fa-solid fa-arrows-rotate"></i> Clear</a>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i>
                            Search</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <hr>
    <div class="row">

        <div class="col-12">
            <x-table.datatabble class="booking-table">
                <thead>
                    <tr>
                        <th class="">Booking Date</th>
                        <th>Travel Date</th>
                        <th>Invoice No</th>

                        <th>Ticket No</th>
                        <th>Type</th>
                        <th>Customer</th>
                        <th><i class="icon-base ti tabler-friends"></i></th>
                        <th class="text-end">standard price</th>
                        <th class="text-end">Total Price</th>
                        <th>Route</th>
                        <th>Status</th>

                        <th>Bank/Agent Ref.</th>
                        <th>Amend</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bookings as $booking)
                    <tr>
                        <td>
                            <small>
                                <x-label-date-time :datetime="$booking['created_at']" /></small>
                        </td>
                        <td>
                            <small>
                                <x-label-date :date="$booking['traveldate']" /></small>
                        </td>
                        <td><small>{{ $booking['bookingno'] }}</small></td>

                        <td><small>{{ $booking['ticketno'] }}</small></td>

                        <td class="text-center">
                            {{ $booking['trip_type'] }}
                        </td>
                        <td>
                            {{ $booking['customer_name'] }}
                            <div class="d-flex">
                                <a href=""><i class="icon-base ti tabler-mail"></i></a>
                            </div>
                        </td>
                        <td class="text-center">
                            {{ $booking['total_passenger'] }}
                        </td>
                        <td class="text-end">
                            <x-label-price :price="$booking['subtotal']" />
                        </td>
                        <td class="text-end">
                            <x-label-price :price="$booking['totalamt']" />
                        </td>
                        <td class="text-center">
                            {{ $booking['route'] }}
                            <div class="d-flex">
                                <span class="badge bg-label-primary">
                                    <x-label-time :time="$booking['depart_time']" />-
                                    <x-label-time :time="$booking['arrival_time']" />
                                </span>
                            </div>
                        </td>
                        <td class="text-center">
                            <small>
                                <x-label-booking-status :status="$booking['status']" /></small>
                        </td>

                        <td class="text-center"><small>{{ $booking['referenceno'] }}</small></td>
                        <td class="text-center">{{ $booking['amend'] }}</td>
                        <td class="text-center">
                            <x-button.dropdown editUrl="" :deleteUrl="route('booking.destroy',['booking'=>$booking['id']])">

                                <li>
                                    <a class="dropdown-item" href="{{ route('booking.show',['booking'=>$booking['id']]) }}"><i class="icon-base ti tabler-device-projector icon-22px"></i> View</a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('print.ticket',['bookingno'=>$booking['bookingno']]) }}" target="_blank"><i class="icon-base ti tabler-file-type-pdf icon-22px"></i> Print Ticket</a>
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('booking.payment',['invoiceno'=>$booking['bookingno']]) }}" target=""><i class="icon-base ti tabler-file-type-pdf icon-22px"></i> Payment</a>
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
