@extends('layouts.default')


@section('content')
<x-card>
    <div class="row">
        <div class="col-12">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Route</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($template->templateLines as $line)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <x-station.route-title-small :departStation="$line->subRoute->route->departStation" :destStation="$line->subRoute->route->destStation" />
                        </td>
                        <td>
                            <x-label-time :time="$line->subRoute->depart_time" />/
                            <x-label-time :time="$line->subRoute->arrival_time" />
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-card>

@stop
