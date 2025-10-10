@extends('layouts.blank')

@section('style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.css') }}" />
@stop


@section('content')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css') }}" />
<div class="row mt-6">
    <div class="col-12 col-lg-10 mx-auto">
        <x-card>
            <x-form :action="route('demo.route')" :isshow_button="false" method="GET">

                <div class="row">
                    <div class="col-12 col-lg-5">
                        <label for="depart_station_id" class="form-label">Depart</label>
                        <select id="depart_station_id" name="depart" class="selectpicker w-100" data-style="btn-default">
                            <option value=""></option>
                            @foreach ($departStations as $index => $stations)
                            <optgroup label="{{ $index }}">
                                @foreach ($stations as $station)
                                <option value="{{ $station['id'] }}" @selected($station['id']==$departStationId)>
                                    <x-station.label-name :station="$station" />
                                </option>
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-lg-5">
                        <label for="dest_station_id" class="form-label">Destination</label>
                        <select id="dest_station_id" name="dest" class="selectpicker w-100" data-style="btn-default">
                            <option value=""></option>
                            @foreach ($destStations as $index => $stations)
                            <optgroup label="{{ $index }}">
                                @foreach ($stations as $station)
                                <option value="{{ $station['id'] }}" @selected($station['id']==$destStationId)>
                                    <x-station.label-name :station="$station" />
                                </option>
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 col-lg-2">
                        <label for="dest_station_id" class="form-label">Date</label>
                        <input type="text" name="travel_date" id="travel_date" class="form-control bs-rangepicker-single" value="{{ $travelDate }}" />
                    </div>
                </div>
            </x-form>
        </x-card>

        @if (!empty($routes))
        @foreach ($routes as $item)
        <x-card>
            <div class="row align-items-center">
                <div class="col-6 col-lg-3 text-center">
                    <h4 class="mb-0">{{ $item['depart_time'] }} <small>{{ $item['depart_time_zone'] }}</small></h4>
                    <h6>{{ $item['depart_station']['name_en'] }}</h6>
                </div>
                <div class="col-1 text-center d-flex justify-content-center align-items-center text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-arrow-big-right-lines">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M12.089 3.634a2 2 0 0 0 -1.089 1.78l-.001 2.585l-1.999 .001a1 1 0 0 0 -1 1v6l.007 .117a1 1 0 0 0 .993 .883l1.999 -.001l.001 2.587a2 2 0 0 0 3.414 1.414l6.586 -6.586a2 2 0 0 0 0 -2.828l-6.586 -6.586a2 2 0 0 0 -2.18 -.434l-.145 .068z" />
                        <path d="M3 8a1 1 0 0 1 .993 .883l.007 .117v6a1 1 0 0 1 -1.993 .117l-.007 -.117v-6a1 1 0 0 1 1 -1z" />
                        <path d="M6 8a1 1 0 0 1 .993 .883l.007 .117v6a1 1 0 0 1 -1.993 .117l-.007 -.117v-6a1 1 0 0 1 1 -1z" /></svg>
                </div>

                <div class="col-6 col-lg-3 text-center">
                    <h4 class="mb-0">{{ $item['arrival_time'] }} <small>{{ $item['arrival_time_zone'] }}</small></h4>
                    <h6>{{ $item['dest_station']['name_en'] }}</h6>
                </div>
                <div class="col-3">
                    <strong><i class="base-icon ti tabler-user"></i>
                        <x-label-price :price="$item['regular_price']" /></strong><br>
                    <strong><i class="base-icon ti tabler-woman"></i>
                        <x-label-price :price="$item['child_price']" /></strong><br>
                    <strong><i class="base-icon ti tabler-baby-carriage"></i>
                        <x-label-price :price="$item['infant_price']" /></strong>
                </div>
                <div class="col-2 text-center">
                    <button class="btn btn-primary btn-lg">BOOK</button>
                </div>

            </div>
            <hr>
            <div class="row">
                <div class="col-12">
                    <p><strong>Text 1:</strong>{{ $item['text_1'] }}</p>
                    <p><strong>Text 2:</strong>{{ $item['text_2'] }}</p>
                </div>

                <div class="col-12 col-lg-6">
                    <strong>Master From</strong>
                    <p>{{ $item['master_from'] }}</p>
                </div>
                <div class="col-12 col-lg-6">
                    <strong>Master To</strong>
                    <p>{{ $item['master_to'] }}</p>
                </div>
                <div class="col-12 col-lg-6">
                    <strong>Infomation From</strong>
                    <p>{{ $item['info_from'] }}</p>
                </div>
                <div class="col-12 col-lg-6">
                    <strong>Infomation To</strong>
                    <p>{{ $item['info_to'] }}</p>
                </div>
            </div>
        </x-card>
        @endforeach

        @else
        <x-card>
            <div class="row align-items-center text-center">
                <h5 class="text-danger">Route is not avaliable on {{ $travelDate }}.</h5>
            </div>
        </x-card>
        @endif
    </div>
</div>
@stop


@section('script')
<script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>



<script>
    $(document).ready(function() {
        $(".selectpicker").selectpicker();

        $('#depart_station_id, #dest_station_id,#travel_date').on('change', function() {
            showLoading();
            $('#frm').submit();
        });
    });

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
@stop
