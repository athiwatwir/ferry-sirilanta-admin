@extends('layouts.default')

@section('content')


<x-card>
    <div class="row">
        <div class="col-12 text-end mb-3">
            <a class="" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                <i class="icon-base ti tabler-selector icon-sm"></i> Search Form
            </a>
        </div>
        <div class="col-12">
            <form novalidate class="bs-validate" id="frm" method="GET" action="{{ route('booking.index') }}">
                <input type="hidden" name="ispdf" id="ispdf" value="N">
                <input type="hidden" name="export" id="export" value="">
                <div class="row collapse" id="collapseExample">
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
                                <option value="{{ $key }}" @selected($tripType==$key)>{{ $_title }}
                                </option>
                                @endforeach
                            </select>
                            <label for="trip_type">Trip Type</label>
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
                    <div class="col-12 col-md-3">
                        <div class="form-floating mb-3">
                            <select class="form-select" id="user_id" name="user_id">
                                <option value="">-- All --</option>
                                @foreach ($filterUsers as $filterUser)
                                <option value="{{ $filterUser->id }}" @selected((string) $user_id===(string) $filterUser->id)>
                                    {{ $filterUser->name }}@if ($filterUser->code) ({{ $filterUser->code }})@endif
                                </option>
                                @endforeach
                            </select>
                            <label for="user_id">User</label>
                        </div>
                    </div>
                </div>
                <div class="row">

                    <div class="col-12 col-md-3">

                        <x-form.float.selection name="date_type" label="By Date" :options="['booking_date'=>'By Booking Date','travel_date'=>'By Travel Date']" :default="$date_type" />
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="form-floating mb-3">

                            <input type="text" id="bs-rangepicker-range" name="daterange" class="form-control" />
                            <label for="bs-rangepicker-range" class="form-label">Date Range</label>
                        </div>
                    </div>
                    <div class="col-12 col-md-5">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="search_text" name="search_text" value="{{ $searchText }}">
                            <label for="email">Search Text</label>
                        </div>
                    </div>
                    <div class="col-4" style="display: none;">
                        <x-button.new text="Book Now!" :href="$bookNowUrl" target="_blank" />
                    </div>
                    <div class="col-12 text-end">
                        <a class="btn btn-secondary" href="{{ route('booking.index') }}"><i class="fa-solid fa-arrows-rotate"></i> Clear</a>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i>
                            Search</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
    <div class="row g-2 mb-3 py-3">
        <div class="col-12 col-md-4">
            <div class="d-flex align-items-center p-2 px-3 rounded border bg-label-primary h-100">
                <div class="row g-0 flex-grow-1 min-w-0 w-100">
                    <div class="col-6 pe-2">
                        <div class="d-flex align-items-center">
                            <span class="avatar avatar-sm me-2 flex-shrink-0">
                                <span class="avatar-initial rounded bg-primary">
                                    <i class="icon-base ti tabler-calendar-check icon-sm"></i>
                                </span>
                            </span>
                            <div class="min-w-0">
                                <div class="fw-semibold lh-1">{{ number_format($searchSummary['booking_count'] ?? 0) }}</div>
                                <small class="text-muted">Bookings</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 ps-2 border-start">
                        <div class="d-flex align-items-center">
                            <span class="avatar avatar-sm me-2 flex-shrink-0">
                                <span class="avatar-initial rounded bg-primary">
                                    <i class="icon-base ti tabler-ticket icon-sm"></i>
                                </span>
                            </span>
                            <div class="min-w-0">
                                <div class="fw-semibold lh-1">{{ number_format($searchSummary['ticket_count'] ?? 0) }}</div>
                                <small class="text-muted">Tickets</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="d-flex align-items-center p-2 px-3 rounded border bg-label-info h-100">
                <div class="row g-0 flex-grow-1 min-w-0 w-100">
                    <div class="col-6 pe-2">
                        <div class="d-flex align-items-center">
                            <span class="avatar avatar-sm me-2 flex-shrink-0">
                                <span class="avatar-initial rounded bg-info">
                                    <i class="icon-base ti tabler-users icon-sm"></i>
                                </span>
                            </span>
                            <div class="min-w-0">
                                <div class="fw-semibold lh-1">{{ number_format($searchSummary['passenger_count'] ?? 0) }}</div>
                                <small class="text-muted">Passengers</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 ps-2 border-start">
                        <div class="d-flex align-items-center">
                            <span class="avatar avatar-sm me-2 flex-shrink-0">
                                <span class="avatar-initial rounded bg-info">
                                    <i class="icon-base ti tabler-armchair icon-sm"></i>
                                </span>
                            </span>
                            <div class="min-w-0">
                                <div class="fw-semibold lh-1">{{ number_format($searchSummary['seat_count'] ?? 0) }}</div>
                                <small class="text-muted">Seats</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="d-flex align-items-center p-2 px-3 rounded border bg-label-success h-100">
                <span class="avatar avatar-sm me-2 flex-shrink-0">
                    <span class="avatar-initial rounded bg-success">
                        <i class="icon-base ti tabler-cash icon-sm"></i>
                    </span>
                </span>
                <div class="min-w-0">
                    <div class="fw-semibold lh-1">
                        <x-label-price :price="$searchSummary['total_amount'] ?? 0" />
                    </div>
                    <small class="text-muted">Total Amount</small>
                </div>
            </div>
        </div>
        <div class="col-12">
            <small class="text-muted">
                <i class="icon-base ti tabler-calendar-event icon-xs me-1"></i>
                @if (!empty($date_type) && $date_type === 'travel_date')
                Travel date
                @else
                Booking date
                @endif
                {{ $startDate instanceof \Carbon\Carbon ? $startDate->format('d/m/Y') : \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
                –
                {{ $endDate instanceof \Carbon\Carbon ? $endDate->format('d/m/Y') : \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}
                · Summary excludes Non Approved
            </small>
        </div>
    </div>

    <hr>
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <span class="text-muted small">{{ count($bookings) }} รายการ</span>
            <div class="btn-group">
                <button type="button" id="exportExcel" class="btn btn-outline-success btn-sm">
                    <i class="icon-base ti tabler-file-spreadsheet me-1"></i> Export Excel
                </button>
                <button type="button" id="exportPDF" class="btn btn-outline-danger btn-sm">
                    <i class="icon-base ti tabler-file-type-pdf me-1"></i> Export PDF
                </button>
            </div>
        </div>
    </div>
    <div class="row">

        <div class="col-12">
            @if (Auth::user()->role =='agent')
            @include('pages.booking.table.agent')
            @elseif(Auth::user()->role =='employee')
            @include('pages.booking.table.employee')
            @elseif(Auth::user()->role =='broker')
            @include('pages.booking.table.broker')
            @else
            @include('pages.booking.table.default')
            @endif

        </div>
    </div>
</x-card>
@stop


@section('script')
<script>
    function closeEmailModal() {
        // ปิด modal
        const modal = document.getElementById('emailModal');
        const bootstrapModal = bootstrap.Modal.getInstance(modal);
        bootstrapModal.hide();

        // ล้างค่าฟอร์ม
        document.querySelector('#emailModal form').reset();
    }


    $(document).ready(function() {
        let start = moment("{{ $startDate }}");
        let end = moment("{{ $endDate }}");

        $("#bs-rangepicker-range").daterangepicker({
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
            , "locale": {
                "format": "DD/MM/YYYY"
            , }
            , opens: isRtl ? 'left' : 'right'
        }, function(start, end) {
            console.log("Selected range:", start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
        });

        function submitBookingExport(type) {
            const form = $('#frm');
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
            submitBookingExport('excel');
        });

        $('#exportPDF').on('click', function() {
            submitBookingExport('pdf');
        });

        $('.iframe-modal').on('click', function() {
            let id = $(this).attr('modal-id');
            let url = $(this).attr('modal-url');
            console.log(url);
            $('#url').attr('src', url);
            //location.reload();
            $(id).modal('show');
        });


        $('#bt-send').on('click', function() {
            showLoading();

            //const $row = $(this).closest('tr');
            const $saveBtn = $(this);
            // ดึงข้อมูลจากแต่ละ input/select/checkbox
            const token = $('meta[name="csrf-token"]').attr('content');
            const booking_id = $('#booking_id').val();
            const email = $('#customer_email').val();
            const message = $('#message').val();
            console.log(message);

            $.ajax({
                url: '/api/email/send-custom-booking'
                , method: 'POST'
                , data: {
                    _token: token
                    , booking_id: booking_id
                    , email: email
                    , message: message

                }
                , success: function(res) {
                    closeEmailModal();
                    hideLoading();
                    console.log(res.message);

                }
                , error: function() {
                    alert('เกิดข้อผิดพลาด');
                }
            });
        });




    });

    document.addEventListener('DOMContentLoaded', function() {
        var editModal = document.getElementById('emailModal');
        editModal.addEventListener('show.bs.modal', function(event) {
            console.log('show');
            var button = event.relatedTarget;

            // ดึงค่าจาก data-attribute
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            var email = button.getAttribute('data-email');


            // ใส่ค่าลงใน input ของ modal
            document.getElementById('booking_id').value = id;
            document.getElementById('customer_name').textContent = name;
            document.getElementById('customer_email').value = email;
        });
    });

</script>

@stop
