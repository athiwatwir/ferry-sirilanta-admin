@extends('layouts.default')

@section('content')

<x-card>

    <div class="row">
        <div class="col-12 col-lg-4">
            <x-station.depart-selection label="From Station" :isrequire="false" />
        </div>
        <div class="col-12 col-lg-4">
            <x-station.depart-selection label="To Station" :isrequire="false" />
        </div>

    </div>
    <x-form :action="route('route.store')">
        <div class="row">

            <div class="col-12">
                <table class="table table-sm table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Depart Time</th>
                            <th>Arrive Time</th>
                            <th>Type</th>
                            <th>Seat</th>
                            <th>Regular Price</th>

                            <th>Child Price</th>
                            <th>Infant Price</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($routes as $route)

                        <tr class="">
                            <td colspan="9">
                                <x-route.route-title-row :departStation="$route['departure_station']" :destStation="$route['destination_station']" />
                            </td>


                        </tr>

                        @foreach ($route['sub_routes'] as $subRoute)
                        <tr class="@if ($subRoute['type']=='activity' ) table-active @endif pointer" data-id="{{ $subRoute['agent_sub_route_id'] }}" data-action="selected">
                            <td>
                                <div class="form-check form-check-success">
                                    <input class="form-check-input" type="checkbox" name="agent_sub_route_ids[]" value="{{ $subRoute['agent_sub_route_id'] }}" id="chk_{{ $subRoute['agent_sub_route_id'] }}" />

                                </div>
                            </td>
                            <td>
                                <strong>
                                    <x-label-time :time="$subRoute['depart_time']" /></strong>
                                <br><small>{{ $subRoute['origin_timezone'] }}</small>
                            </td>
                            <td>
                                <x-label-time :time="$subRoute['arrival_time']" />
                                <br><small>{{ $subRoute['destination_timezone'] }}</small>
                            </td>
                            <td class="">
                                @if ($subRoute['type']=='activity')
                                <span class="badge bg-label-info">Activity route</span><br>
                                @endif
                                {{ $subRoute['boat_type'] }} <br />

                                <div class="col-12 d-flex align-items-center flex-wrap">

                                    @if (!empty($subRoute['icons']))
                                    @foreach ($subRoute['icons'] as $icon)
                                    <div class="avatar avatar-sm me-4 position-relative">
                                        <img src="{{ $icon }}" alt="Avatar">
                                        <small></small>

                                    </div>
                                    @endforeach
                                    @endif
                                </div>
                            </td>
                            <td>
                                <x-label-number :number="$subRoute['seatamt']" />
                            </td>
                            <td class="">
                                <x-label-price :price="$subRoute['cost_price']" />
                            </td>

                            <td class="">
                                <x-label-price :price="$subRoute['cost_child_price']" />
                            </td>

                            <td class="">
                                <x-label-price :price="$subRoute['cost_infant_price']" />
                            </td>

                        </tr>
                        @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </x-form>
</x-card>

@stop


@section('script')

<script>
    $(document).ready(function() {

        // ถ้ามีการสร้างแถวแบบไดนามิก ให้ใช้ delegated event แบบนี้
        $(document).on('click', 'tr[data-action="selected"]', function(e) {
            // ถ้าคลิกที่ checkbox เอง ไม่ต้อง toggle ซ้ำ
            if ($(e.target).is('input[type="checkbox"], label, a, button')) return;

            const $chk = $(this).find('input[type="checkbox"]');
            const checked = !$chk.prop('checked');

            $chk.prop('checked', checked).trigger('change'); // เผื่อมี handler อื่นฟังอยู่
            $(this).toggleClass('table-active', checked); // ใส่/เอา class ไฮไลท์ตามต้องการ
        });

        // กัน event เด้งขึ้นไปถึง tr เวลาคลิก checkbox โดยตรง (ทางเลือก)
        $(document).on('click', 'tr[data-action="selected"] input[type="checkbox"]', function(e) {
            e.stopPropagation();
        });



    });

</script>
@stop
