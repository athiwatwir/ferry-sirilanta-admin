@extends('layouts.default')

@section('content')
<x-card>

    <div class="row">
        <div class="col-12 col-lg-10">
            <x-station.route-title :departStation="$route['departure_station']" :destStation="$route['destination_station']" />

        </div>


    </div>
    <hr>

    <div class="row">
        <div class="col-12">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Depart Time</th>
                        <th>Arrive Time</th>
                        <th>Type</th>
                        <th>Seat</th>
                        <th>Price</th>

                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($route['sub_routes'] as $subRoute)
                    <tr @if ($subRoute['type']=='activity' ) class="table-active" @endif>
                        <td>{{ $loop->iteration }}</td>
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
                        <td>


                        </td>

                        <td class="text-center">



                        </td>

                        <td class="text-end">


                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</x-card>



@stop


@section('script')

<script src="{{ asset('assets/vendor/libs/cleave-zen/cleave-zen.js') }}"></script>
<script src="{{ asset('js/sub-route/time-calculate.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#bt-create').on('click', function() {
            $('#box-create').show();
        });
    });

</script>
@stop
