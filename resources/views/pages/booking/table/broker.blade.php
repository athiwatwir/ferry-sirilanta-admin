@include('pages.booking.partials.table-compact-style')

<x-table.datatabble class="booking-table">
    <thead>
        <tr>
            <th>Booking Date</th>
            <th>Travel Date</th>
            <th>Invoice No</th>
            <th>Ticket No</th>
            <th>Type</th>
            <th>Customer</th>
            <th><i class="icon-base ti tabler-friends"></i></th>
            <th class="text-end">Use Credit</th>
            <th>Route</th>
            <th>Status</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bookings as $booking)
        <tr>
            <td>
                <small><x-label-date-time :datetime="$booking['created_at']" /></small>
            </td>
            <td>
                <small><x-label-date :date="$booking['traveldate']" /></small>
            </td>
            <td><small>{{ $booking['bookingno'] }}</small></td>
            <td><small>{{ $booking['ticketno'] }}</small></td>
            <td class="text-center">{{ $booking['trip_type'] }}</td>
            <td>{{ Str::limit($booking['customer_name'], 13, '...') }}</td>
            <td class="text-center">{{ $booking['total_passenger'] }}</td>
            <td class="text-end">
                <x-label-price :price="$booking['payment_totalamt']" />
            </td>
            <td class="text-center">
                {{ $booking['route'] }}
                <div>
                    <span class="badge bg-label-primary">
                        <x-label-time :time="$booking['depart_time']" />-
                        <x-label-time :time="$booking['arrival_time']" />
                    </span>
                </div>
            </td>
            <td class="text-center">
                <small><x-label-booking-status :status="$booking['status']" /></small>
            </td>
            <td class="text-center">
                @include('pages.booking.partials.table-actions', ['booking' => $booking])
            </td>
        </tr>
        @endforeach
    </tbody>
</x-table.datatabble>
