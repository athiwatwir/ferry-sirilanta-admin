@extends('layouts.default')

@section('content')
<x-card>
    <div class="row">
        <div class="col-12">
            <x-form :action="route('report.account')" method="GET" :isshow_button="false">
                <div class="row">
                    <div class="col-12 col-lg-5">
                        <x-route.selection-depart-station :selected="$depart_station_id" :isrequire="false" />
                    </div>
                    <div class="col-12 col-lg-5">
                        <x-route.selection-dest-station :selected="$dest_station_id" :departStationId="$depart_station_id" />
                    </div>
                    <div class="col-12 col-lg-2">
                        <x-form.float.selection name="sub_route_id" :isempty="true" label="Time" :isrequire="false" :isempty="false" :options="$subRoutes" />
                    </div>
                    <div class="col-12 col-lg-5">
                        <x-form.float.date-rang-picker label="Booking Date" />
                    </div>
                    <div class="col-12 col-lg-5">
                        <x-form.float.selection name="agen_id" label="API" :isrequire="false" :options="$agents" />
                    </div>
                </div>

            </x-form>

            <x-form method="POST" id="search_form" :action="route('print.reportAccount')" :isshow_button="false">
                <div class="row">
                    <input type="hidden" name="depart_station_id" id="_depart_station_id" value="{{ $depart_station_id }}">
                    <input type="hidden" name="dest_station_id" id="_dest_station_id" value="{{ $dest_station_id }}">
                    <input type="hidden" name="sub_route_id" id="_sub_route_id" value="">
                    <input type="hidden" name="daterange" id="_daterange" value="">
                    <div class="col-12 text-center">
                        <button class="btn btn-success btn-lg" type="submit">Show Report</button>
                    </div>
                </div>
            </x-form>
        </div>
    </div>
</x-card>
@stop


@section('script')
<script>
    $(document).ready(function() {
        $('#depart_station_id, #dest_station_id').on('change', function() {
            showLoading();
            $('#frm').submit();
        });

        $('#sub_route_id').on('change', function() {

            $('#_sub_route_id').val($(this).val());
        });

        $('#search_form').on('submit', function() {
            showLoading();
            $('#_daterange').val($('#daterange').val());
        });


        var bsRangePickerSingle = $('.bs-rangepicker');
        if (bsRangePickerSingle.length) {
            bsRangePickerSingle.daterangepicker({
                singleDatePicker: false
                , autoUpdateInput: true
                , opens: isRtl ? 'left' : 'right'
                , locale: {
                    format: 'DD/MM/YYYY'
                }
            });

        }

    });

</script>

@stop
