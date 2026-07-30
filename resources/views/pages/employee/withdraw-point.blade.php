@extends('layouts.default')

@section('content')
<div class="row">
    <div class="col-12 col-xl-11 mx-auto">
        <div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-4">
            <div class="d-flex align-items-start p-3 rounded bg-label-success flex-grow-1">
                <div class="avatar avatar-sm me-3 flex-shrink-0 mt-1">
                    <span class="avatar-initial rounded bg-success">
                        <i class="icon-base ti tabler-coin icon-sm"></i>
                    </span>
                </div>
                <div>
                    <h6 class="mb-1 fw-semibold text-success">ถอน Point — {{ $employee->name }}</h6>
                    <p class="mb-0 text-body small">
                        เลือกช่วงวันที่ แล้วเลือกรายการ booking ที่ต้องการถอน Point
                    </p>
                </div>
            </div>
            <a href="{{ route('employee.show', $employee) }}" class="btn btn-label-secondary align-self-center">
                <i class="icon-base ti tabler-arrow-left me-1"></i>กลับ
            </a>
        </div>

        <x-card class="mb-4">
            <form id="frm-withdraw-point" method="GET" action="{{ route('employee.withdrawPoint', $employee) }}">
                <input type="hidden" name="filtered" value="1">
                <input type="hidden" name="ispdf" id="ispdf" value="N">
                <input type="hidden" name="export" id="export" value="">

                <div class="row">
                    <div class="col-12 col-md-3">
                        <x-form.float.selection name="date_type" label="By Date" :options="['travel_date' => 'By Travel Date', 'booking_date' => 'By Booking Date']" :default="$date_type" />
                    </div>
                    <div class="col-12 col-md-5">
                        <div class="form-floating">
                            <input type="text" id="withdraw-daterange" name="daterange" class="form-control" value="{{ $daterange ?: ($startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y')) }}">
                            <label for="withdraw-daterange">Date Range</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-4 text-md-end">
                        <a href="{{ route('employee.withdrawPoint', $employee) }}" class="btn btn-label-secondary me-1">Clear</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="icon-base ti tabler-search me-1"></i>ค้นหา
                        </button>
                    </div>
                </div>
            </form>
        </x-card>

        @if ($filtered)
        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-none bg-label-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-primary">Point</span>
                            <i class="icon-base ti tabler-star text-primary"></i>
                        </div>
                        <h3 class="mb-1 fw-bold" id="sum-point">{{ number_format($summary['total_point']) }}</h3>
                        <small class="text-muted">รวม Point ที่เลือก</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-none bg-label-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-success">Amount</span>
                            <i class="icon-base ti tabler-currency-baht text-success"></i>
                        </div>
                        <h3 class="mb-1 fw-bold" id="sum-amount">{{ number_format($summary['total_amount'], 2) }}</h3>
                        <small class="text-muted">รวมราคา Booking (THB)</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-none bg-label-info">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-info">Bookings</span>
                            <i class="icon-base ti tabler-ticket text-info"></i>
                        </div>
                        <h3 class="mb-1 fw-bold" id="sum-bookings">{{ number_format($summary['booking_count']) }}</h3>
                        <small class="text-muted">จำนวน Booking ที่เลือก</small>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card h-100 border-0 shadow-none bg-label-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-warning text-dark">Passengers</span>
                            <i class="icon-base ti tabler-users text-warning"></i>
                        </div>
                        <h3 class="mb-1 fw-bold" id="sum-passengers">{{ number_format($summary['passenger_count']) }}</h3>
                        <small class="text-muted">
                            One-Way <span id="sum-trip-o">0</span>
                            · Round-Trip <span id="sum-trip-r">0</span>
                            · Multiple <span id="sum-trip-m">0</span>
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <x-card>
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div>
                    <h5 class="mb-0">รายการ Booking ที่จะถอน</h5>
                    <small class="text-muted">เลือกเฉพาะรายการที่ต้องการถอน Point</small>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" id="exportExcel" class="btn btn-outline-success btn-sm" @disabled($summary['booking_count'] < 1)>
                        <i class="icon-base ti tabler-file-spreadsheet me-1"></i>Export Excel
                    </button>
                    <button type="button" id="exportPDF" class="btn btn-outline-danger btn-sm" @disabled($summary['booking_count'] < 1)>
                        <i class="icon-base ti tabler-file-type-pdf me-1"></i>Export PDF
                    </button>
                </div>
            </div>

            <form id="frm-withdraw-confirm" method="POST" action="{{ route('employee.withdrawPointConfirm', $employee) }}">
                @csrf
                <input type="hidden" name="date_type" value="{{ $date_type }}">
                <input type="hidden" name="daterange" value="{{ $daterange ?: ($startDate->format('d/m/Y') . ' - ' . $endDate->format('d/m/Y')) }}">

                <div class="table-responsive mb-4">
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th style="width: 40px;">
                                    <input type="checkbox" class="form-check-input" id="withdrawSelectAll" title="เลือกทั้งหมด" @checked($summary['booking_count']> 0)>
                                </th>
                                <th>Booking Date</th>
                                <th>Travel Date</th>
                                <th>Booking No</th>
                                <th class="text-center">Type</th>
                                <th class="text-center">Passengers</th>
                                <th class="text-center">Point</th>
                                <th class="text-center">Payment Method</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($bookings as $booking)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input withdraw-booking-cb" name="booking_ids[]" value="{{ $booking->id }}" data-point="{{ $booking->point }}" data-amount="{{ $booking->totalamt }}" data-passenger="{{ $booking->adult_passenger }}" data-trip-type="{{ $booking->trip_type }}" checked>
                                </td>
                                <td>{{ $booking->created_at?->format('d/m/Y H:i') }}</td>
                                <td>{{ $booking->departdate?->format('d/m/Y') }}</td>
                                <td>{{ $booking->bookingno }}</td>
                                <td class="text-center">{{ $tripTypes[$booking->trip_type] ?? ($booking->trip_type ?: '-') }}</td>
                                <td class="text-center">{{ $booking->adult_passenger }}</td>
                                <td class="text-center fw-semibold">{{ $booking->point }}</td>
                                <td class="text-center">{{ $booking->payment_method }}</td>
                                <td class="text-end">
                                    <x-label-price :price="$booking->totalamt" />
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">ไม่พบรายการในช่วงวันที่ที่เลือก</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($summary['booking_count'] > 0)
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 p-3 rounded bg-label-success">
                    <div>
                        <h6 class="mb-1 text-success">พร้อมยืนยันการถอน</h6>
                        <p class="mb-0 small text-body">
                            จะถอน <strong id="confirm-point">{{ number_format($summary['total_point']) }}</strong> Point
                            จาก <strong id="confirm-bookings">{{ number_format($summary['booking_count']) }}</strong> booking
                            (มูลค่ารวม <span id="confirm-amount">{{ number_format($summary['total_amount'], 2) }}</span> THB)
                        </p>
                    </div>
                    <button type="submit" id="withdrawConfirmBtn" class="btn btn-success">
                        <i class="icon-base ti tabler-check me-1"></i>ยืนยันการถอน Point
                    </button>
                </div>
                @endif
            </form>
        </x-card>
        @else
        <x-card>
            <div class="text-center py-5">
                <div class="avatar avatar-lg mx-auto mb-3">
                    <span class="avatar-initial rounded bg-label-secondary">
                        <i class="icon-base ti tabler-calendar-search icon-lg"></i>
                    </span>
                </div>
                <h5 class="mb-1">เลือกช่วงวันที่เพื่อเริ่มต้น</h5>
                <p class="text-muted mb-0">ระบบจะแสดงสรุป Point / ราคา / จำนวน booking / ผู้โดยสาร ก่อนยืนยันถอน</p>
            </div>
        </x-card>
        @endif
    </div>
</div>
@endsection

@php
$startDateJs = $startDate->toDateTimeString();
$endDateJs = $endDate->toDateTimeString();
@endphp

@section('script')
<script>
    $(document).ready(function() {
        var start = moment(@json($startDateJs));
        var end = moment(@json($endDateJs));

        $('#withdraw-daterange').daterangepicker({
            startDate: start
            , endDate: end
            , ranges: {
                Today: [moment(), moment()]
                , Yesterday: [moment().subtract(1, 'days'), moment().subtract(1, 'days')]
                , 'Last 7 Days': [moment().subtract(6, 'days'), moment()]
                , 'Last 30 Days': [moment().subtract(29, 'days'), moment()]
                , 'This Month': [moment().startOf('month'), moment().endOf('month')]
                , 'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
            , locale: {
                format: 'DD/MM/YYYY'
            }
            , opens: (typeof window.isRtl !== 'undefined' && window.isRtl) ? 'left' : 'right'
        });

        function formatNumber(value, decimals) {
            return Number(value).toLocaleString('en-US', {
                minimumFractionDigits: decimals || 0
                , maximumFractionDigits: decimals || 0
            });
        }

        function updateSelectedSummary() {
            var checked = $('.withdraw-booking-cb:checked');
            var point = 0;
            var amount = 0;
            var passengers = 0;
            var tripO = 0;
            var tripR = 0;
            var tripM = 0;

            checked.each(function() {
                point += parseInt($(this).data('point') || 0, 10);
                amount += parseFloat($(this).data('amount') || 0);
                passengers += parseInt($(this).data('passenger') || 0, 10);
                var tripType = String($(this).data('trip-type') || '').toUpperCase();
                if (tripType === 'O') tripO++;
                else if (tripType === 'R') tripR++;
                else if (tripType === 'M') tripM++;
            });

            $('#sum-point, #confirm-point').text(formatNumber(point));
            $('#sum-amount, #confirm-amount').text(formatNumber(amount, 2));
            $('#sum-bookings, #confirm-bookings').text(formatNumber(checked.length));
            $('#sum-passengers').text(formatNumber(passengers));
            $('#sum-trip-o').text(formatNumber(tripO));
            $('#sum-trip-r').text(formatNumber(tripR));
            $('#sum-trip-m').text(formatNumber(tripM));
            $('#withdrawConfirmBtn').prop('disabled', checked.length === 0);

            var all = $('.withdraw-booking-cb');
            $('#withdrawSelectAll').prop('checked', all.length > 0 && checked.length === all.length);
        }

        $('#withdrawSelectAll').on('change', function() {
            $('.withdraw-booking-cb').prop('checked', this.checked);
            updateSelectedSummary();
        });

        $(document).on('change', '.withdraw-booking-cb', updateSelectedSummary);

        $('#frm-withdraw-confirm').on('submit', function(e) {
            if ($('.withdraw-booking-cb:checked').length === 0) {
                e.preventDefault();
                alert('กรุณาเลือก booking ที่ต้องการถอน Point');
                return false;
            }
            return confirm('ยืนยันถอน Point จากรายการที่เลือก?');
        });

        updateSelectedSummary();

        function submitExport(type) {
            var form = $('#frm-withdraw-point');
            $('#ispdf').val(type === 'pdf' ? 'Y' : 'N');
            $('#export').val(type === 'excel' ? 'excel' : '');
            form.attr('target', '_blank');
            form.submit();
            setTimeout(function() {
                form.removeAttr('target');
                $('#ispdf').val('N');
                $('#export').val('');
            }, 300);
        }

        $('#exportExcel').on('click', function() {
            submitExport('excel');
        });
        $('#exportPDF').on('click', function() {
            submitExport('pdf');
        });
    });

</script>
@endsection
