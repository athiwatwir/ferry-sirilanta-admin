@include('pages.booking.partials.table-compact-style')

@if (!empty($employeeDashboard))
<div class="row mb-3">
    <div class="col-12">
        <div class="card-body d-flex align-items-end px-0 pt-0">
            <div class="w-100">
                <div class="row gy-3">
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded bg-label-primary me-4 p-2">
                                <i class="icon-base ti tabler-ticket icon-lg"></i>
                            </div>
                            <div class="card-info">
                                <h5 class="mb-0">{{ number_format($employeeDashboard['ticket_sales_amount'] ?? 0) }} THB</h5>
                                <small>ยอดขายตั๋ว ({{ number_format($employeeDashboard['ticket_sales_count'] ?? 0) }} รายการ)</small>
                                <div class="text-muted" style="font-size: 0.7rem;">
                                    ตามช่วงค้นหา
                                    @isset($startDate, $endDate)
                                    {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}
                                    @endisset
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="{{ route('employee.point') }}" class="text-body">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded bg-label-warning me-4 p-2">
                                    <i class="icon-base ti tabler-coins icon-lg"></i>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">{{ number_format($employeeDashboard['pending_point'] ?? 0) }} Point</h5>
                                    <small>Point ที่ยังไม่ได้ถอน (ตามที่ค้นหา)</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded bg-label-info me-4 p-2">
                                <i class="icon-base ti tabler-credit-card icon-lg"></i>
                            </div>
                            <div class="card-info">
                                <h5 class="mb-0">{{ number_format($employeeDashboard['credit_amount'] ?? 0) }} THB</h5>
                                <small>รวมบัตรเครดิต ({{ number_format($employeeDashboard['credit_count'] ?? 0) }} รายการ)</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="d-flex align-items-center">
                            <div class="badge rounded bg-label-success me-4 p-2">
                                <i class="icon-base ti tabler-cash icon-lg"></i>
                            </div>
                            <div class="card-info">
                                <h5 class="mb-0">{{ number_format($employeeDashboard['cash_amount'] ?? 0) }} THB</h5>
                                <small>รวมเงินสด ({{ number_format($employeeDashboard['cash_count'] ?? 0) }} รายการ)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
    </div>
</div>
@endif

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
            <th class="text-end">Price</th>
            <th>Fee</th>
            <th class="text-end">Total</th>
            <th>Route</th>
            <th>Status</th>
            <th>Point</th>
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
            <td class="text-center">{{ $booking['trip_type'] }}</td>
            <td>{{ Str::limit($booking['customer_name'], 13, '...') }}</td>
            <td class="text-center">{{ $booking['total_passenger'] }}</td>
            <td class="text-end">
                <x-label-price :price="$booking['totalamt']" />
            </td>
            <td class="text-end">
                <x-label-price :price="$booking['feeamt']" />
            </td>
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
                <small>
                    <x-label-booking-status :status="$booking['status']" /></small>
                @if($booking['status'] === 'CO')

                @endif
            </td>
            <td class="text-center">
                @if(($booking['ispayment'] ?? 'N') === 'Y')
                <span class="text-success">+{{ $booking['point'] ?? 0 }}</span>
                @if($booking['isearned'] == 'Y')
                <div class="text-success" style="font-size: 0.7rem;">ถอนแล้ว</div>
                @else
                <div class="text-muted" style="font-size: 0.7rem;">ยังไม่ถอน</div>
                @endif
                @else
                <span class="text-muted">+0</span>
                <div class="text-muted" style="font-size: 0.7rem;">รอชำระเงิน</div>
                @endif
            </td>
            <td class="text-center">
                @include('pages.booking.partials.table-actions', ['booking' => $booking])
            </td>
        </tr>
        @endforeach
    </tbody>
</x-table.datatabble>
