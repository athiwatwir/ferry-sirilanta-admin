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
                    <div class="col-12 text-end">
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

                        <td>
                            <small>{{ $booking['ticketno'] }}</small>
                            @if ($booking['agent_name'])
                            <span class="badge bg-label-dark">{{ $booking['agent_name'] }}</span>
                            @endif
                        </td>

                        <td class="text-center">
                            {{ $booking['trip_type'] }}
                        </td>
                        <td>
                            {{ Str::limit($booking['customer_name'], 15, '...')  }}
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

                        <td class="text-center">

                            <small>{{ $booking['referenceno'] }}</small>
                        </td>
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
