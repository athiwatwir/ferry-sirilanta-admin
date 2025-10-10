@extends('layouts.default')

@section('content')
<x-card>
    <div class="row">
        <div class="col-12 col-lg-6">
            <form action="{{ route('routeSchedule.index') }}" id="frm-search" method="GET">
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
        <div class="col-12 col-lg-6 text-end">
            <x-button.new :href="route('routeSchedule.create')" />
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-12">
            <x-table.datatabble>
                <thead>
                    <tr>
                        <th>Route</th>
                        <th>Time</th>
                        <th>Last Action</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($subRoutes as $item)
                    <tr>
                        <td>
                            <x-station.route-title-normal :departStation="$item->route->departStation" :destStation="$item->route->destStation" />

                        </td>
                        <td>
                            <x-label-time :time="$item->depart_time" /> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chevrons-right">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M7 7l5 5l-5 5" />
                                <path d="M13 7l5 5l-5 5" /></svg>
                            <x-label-time :time="$item->arrival_time" />
                        </td>

                        <td>
                            @foreach ($item->lastSchedules as $schedule)
                            <x-route-schedule.title :schedule="$schedule" />
                            @endforeach
                        </td>
                        <td class="text-end">
                            <div class="d-flex align-items-center">
                                <a href="{{ route('routeSchedule.calendar',['subRoute'=>$item->id]) }}" class="btn btn-secondary text-white me-2 btn-icon  waves-effect waves-light rounded-pill " data-bs-toggle="tooltip"><i class="base-icon ti tabler-calendar-clock"></i></a>

                            </div>

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

<script>
    $(document).ready(function() {
        $('#depart_station_id, #dest_station_id').on('change', function() {
            showLoading();
            $('#frm-search').submit();
        });

    });

</script>

@stop
