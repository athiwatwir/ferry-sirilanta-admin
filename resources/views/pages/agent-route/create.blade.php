@extends('layouts.default')

@section('content')

<x-card>
    <div class="row">
        <div class="col-12">
            <h3>Add route to this agent</h3>
        </div>
        <div class="col-12 col-lg-8">
            <form action="{{ route('agentRoute.create') }}" id="frm-search" method="GET">
                <input type="hidden" name="agent" id="agent" value="{{ $agentId }}">
                <div class="row">
                    <div class="col">
                        <x-station.selection name="depart_station_id" label="Station From" :isrequire="false" :selected="$depart_station_id" />
                    </div>
                    <div class="col">
                        <x-station.selection name="dest_station_id" label="Station To" :isrequire="false" :selected="$dest_station_id" />
                    </div>
                </div>
            </form>
        </div>
        <div class="col-12">
            <x-form :action="route('agentRoute.store')">
                <input type="hidden" name="agent_id" id="agent_id" value="{{ $agentId }}">
                <div class="row">

                    <div class="col-12">
                        <table class="table" id="table">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="form-check form-check-success mb-0">
                                            <input class="form-check-input" type="checkbox" id="select-all" />
                                            <label class="form-check-label" for="select-all">Select All</label>
                                        </div>
                                    </th>
                                    <th>Route</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($routes as $route)

                                @foreach ($route->subRoutes as $item)
                                <tr data-id="{{ $item->id }}" data-action="select-route" class="pointer">
                                    <td>
                                        <div class="form-check form-check-success mb-0">
                                            <input class="form-check-input" type="checkbox" name="sub_route_ids[]" value="{{ $item->id }}" id="{{ $item->id }}" />
                                            <label class="form-check-label" for="{{ $item->id }}"></label>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($loop->iteration ==1)
                                        <x-station.route-title-normal :departStation="$route->departStation" :destStation="$route->destStation" />
                                        @endif

                                    </td>
                                    <td>
                                        <x-label-time :time="$item->depart_time" /> /
                                        <x-label-time :time="$item->arrival_time" />
                                    </td>
                                    <td>

                                    </td>

                                </tr>
                                @endforeach

                                @endforeach
                            </tbody>
                        </table>
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
            $('#frm-search').submit();
        });

        $('#table tbody').on('click', 'tr', function(e) {
            // ถ้า user คลิกที่ checkbox โดยตรง ให้ไม่ toggle ซ้ำ
            if ($(e.target).is('input[type="checkbox"], label')) return;

            const checkbox = $(this).find('input[type="checkbox"]');

            const checked = !checkbox.prop('checked');
            checkbox.prop('checked', checked);

            // เปลี่ยนสีของแถวตามสถานะ checkbox
            if (checked) {
                $(this).addClass('row-selected');
            } else {
                $(this).removeClass('row-selected');
            }
        });

        $('#select-all').on('change', function() {
            const isChecked = $(this).is(':checked');

            $('input[name="sub_route_ids[]"]').each(function() {
                $(this).prop('checked', isChecked);
                $(this).closest('tr').toggleClass('row-selected', isChecked);
            });
        });


    });

</script>


@stop
