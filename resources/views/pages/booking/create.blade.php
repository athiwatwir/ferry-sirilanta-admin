@extends('layouts.default')

@section('content')
<x-card>
    <x-form :action="route('booking.store')" :isshow_button="false">
        <input type="hidden" name="sub_route_id" id="" value="{{ $sub_route_id }}">
        <input type="hidden" name="departdate" id="" value="{{ $travel_date }}">
        <div class="row">
            <div class="col-12 col-lg-5 text-center">
                <h3 class="mb-0">{{$subRoute['departure_time'] }} ({{ $subRoute['departure_timezone'] }})</h3>
                <h4 class="mb-0 text-primary">[{{ $subRoute['departure_station']['nickname'] }}] {{ $subRoute['departure_station']['name'] }} </h4>
                @if (!empty($subRoute['departure_station']['piername']))
                <strong>({{ $subRoute['departure_station']['piername'] }})</strong>
                @endif
            </div>

            <div class="col-12 col-lg-2 text-center">
                <svg class="icon" xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24">
                    <defs>
                        <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="0%">
                            <stop offset="0%" stop-color="#00f260">
                                <animate attributeName="stop-color" values="#00f260;#0575e6;#00f260" dur="4s" repeatCount="indefinite" />
                            </stop>
                            <stop offset="100%" stop-color="#0575e6">
                                <animate attributeName="stop-color" values="#0575e6;#00f260;#0575e6" dur="4s" repeatCount="indefinite" />
                            </stop>
                        </linearGradient>
                    </defs>
                    <!-- เติม fill="url(#grad1)" -->
                    <path fill="url(#grad1)" d="M12.089 3.634a2 2 0 0 0 -1.089 1.78l-.001 2.585l-1.999 .001a1 1 0 0 0 -1 1v6l.007 .117a1 1 0 0 0 .993 .883l1.999 -.001l.001 2.587a2 2 0 0 0 3.414 1.414l6.586 -6.586a2 2 0 0 0 0 -2.828l-6.586 -6.586a2 2 0 0 0 -2.18 -.434l-.145 .068z" />
                    <path fill="url(#grad1)" d="M3 8a1 1 0 0 1 .993 .883l.007 .117v6a1 1 0 0 1 -1.993 .117l-.007 -.117v-6a1 1 0 0 1 1 -1z" />
                    <path fill="url(#grad1)" d="M6 8a1 1 0 0 1 .993 .883l.007 .117v6a1 1 0 0 1 -1.993 .117l-.007 -.117v-6a1 1 0 0 1 1 -1z" />
                </svg>

            </div>

            <div class="col-12 col-lg-5 text-center">
                <h3 class="mb-0">{{$subRoute['arrival_time'] }} ({{ $subRoute['departure_timezone'] }})</h3>
                <h4 class="mb-0 text-primary">[{{ $subRoute['departure_station']['nickname'] }}] {{ $subRoute['departure_station']['name'] }} </h4>
                @if (!empty($subRoute['departure_station']['piername']))
                <strong>({{ $subRoute['departure_station']['piername'] }})</strong>
                @endif
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-12 col-lg-2">
                <x-form.float.input label="Travel Date" value="{{ $travel_date }}" :isreadonly="true" />
            </div>
            <div class="col-12 col-lg-4">
                <x-form.float.input name="fullname" label="Full Name" :isrequire="false" />
            </div>
            <div class="col-12 col-lg-3">
                <x-form.float.input name="passportno" label="Passportno" :isrequire="false" />
            </div>
            <div class="col-12 col-lg-3">
                <x-form.float.input name="mobile" label="Mobile" :isrequire="false" />
            </div>

            <div class="col-12 col-lg-2">
                <x-form.float.input name="adult_passenger" label="Adult Passenger" value="1" />
            </div>
            <div class="col-12 col-lg-2">
                <x-form.float.input name="child_passenger" label="Child Passenger" value="0" />
            </div>
            <div class="col-12 col-lg-2">
                <x-form.float.input name="infant_passenger" label="Infant Passenger" value="0" />
            </div>
            <div class="col-12 col-lg-6">
                <x-form.float.textarea />
            </div>


        </div>
        <hr>
        <div class="row">
            <div class="col-12 col-lg-6 mb-2">
                <div class="row">
                    <div class="col-4">
                        <small>Regular</small>
                        <x-label-price :price="$subRoute['prices']['regular']" />
                    </div>
                    <div class="col-4">
                        <small>Child</small>
                        <x-label-price :price="$subRoute['prices']['child']" />
                    </div>
                    <div class="col-4">
                        <small>infant</small>
                        <x-label-price :price="$subRoute['prices']['infant']" />
                    </div>
                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-12 col-lg-3">
                <x-form.float.input name="price" label="Total Price" />
            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col-12 text-center">
                <button type="submit" class="btn btn-lg btn-success">Save and Next to Payment</button>
            </div>
        </div>

    </x-form>
</x-card>

@stop


@section('script')
<script>
    const regular_price = @json($subRoute['prices']['regular']);
    const child_price = @json($subRoute['prices']['child']);
    const infant_price = @json($subRoute['prices']['infant']);


    var bsRangePickerSingle = $('.bs-rangepicker-single');
    if (bsRangePickerSingle.length) {
        bsRangePickerSingle.daterangepicker({
            singleDatePicker: true
            , opens: isRtl ? 'left' : 'right'
            , "locale": {
                "format": "DD/MM/YYYY"
            , }
        });
    }

</script>

<script>
    $(document).ready(function() {
        function updateTotal() {
            const adult = parseInt($('#adult_passenger').val()) || 0;
            const child = parseInt($('#child_passenger').val()) || 0;
            const infant = parseInt($('#infant_passenger').val()) || 0;

            const total = (adult * regular_price) + (child * child_price) + (infant * infant_price);
            $('#price').val(total);
        }

        // ใช้ .on('input') เพื่ออัปเดตผลรวมทันทีเมื่อพิมพ์หรือเปลี่ยนค่า
        $('#adult_passenger, #child_passenger, #infant_passenger').on('input', updateTotal);
        updateTotal();
    });

</script>

@stop
