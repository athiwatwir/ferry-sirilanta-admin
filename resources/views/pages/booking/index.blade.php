@extends('layouts.default')

@section('content')
<style>
    .booking-table td {
        padding: 5px;
    }

</style>

@if ($salesPartner && Auth::user()->role =='broker')
@include('pages.booking.dashboard.broker')
@else
@endif
<x-card>
    <div class="row">
        <div class="col-12 text-end mb-3">
            <a class="" data-bs-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                <i class="icon-base ti tabler-selector icon-sm"></i> Search Form
            </a>
        </div>
        <div class="col-12">
            @props(['method'=>'GET','action'=>''])
            <form novalidate class="bs-validate" id="frm" method="{{ $method }}" action="{{ $action }}">
                <input type="hidden" name="ispdf" id="ispdf" value="N">
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
                    <div class="col-4">
                        <x-button.new text="Book Now!" href="{{ env('WEB_URL')}}?aff={{ Auth::user()->id }}" target="_blank" />
                    </div>
                    <div class="col-8 text-end">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tableexport/5.2.0/js/tableexport.min.js"></script>




<script>
    document.getElementById('exportExcel').addEventListener('click', function() {
        let table = document.getElementById("bookingTable");
        let wb = XLSX.utils.table_to_book(table);
        XLSX.writeFile(wb, "booking-report.xlsx");
    });

</script>

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

        $('#exportPDF').on('click', function() {
            $('#ispdf').val('Y');

            // เปลี่ยน target ชั่วคราว
            var form = $('#frm');
            form.attr('target', '_blank');

            form.submit();

            // คืนค่ากลับเป็นปกติ (optional)
            setTimeout(() => form.removeAttr('target'), 100);
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
