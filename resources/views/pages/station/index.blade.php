@extends('layouts.default')

@section('content')
<div class="nav-align-top" bis_skin_checked="1">
    <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-md-0 gap-2">
        <li class="nav-item">
            <a class="nav-link active waves-effect waves-light" href="{{ route('station.index') }}"><i class="menu-icon icon-base ti tabler-current-location"></i> Station Management</a>
        </li>
        <li class="nav-item">
            <a class="nav-link waves-effect waves-light" href="{{ route('section.index') }}"><i class="icon-base ti tabler-lock icon-sm me-1_5"></i> Section Management</a>
        </li>

    </ul>
</div>
<x-card>
    <div class="card-datatable table-responsive pt-0">
        <div class="row">
            <div class="col text-end">
                <x-button.new :href="route('station.create')" />
            </div>
        </div>
        <table class="table table-hover">
            <thead>
                <tr>

                    <th>Sort</th>
                    <th>Name en/th</th>
                    <th>Nickname</th>
                    <th>Type</th>
                    <th>Pier</th>
                    <th>Status</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sections as $section)
                <tr>
                    <td colspan="7" class="text-primary">{{ $section->name }}</td>
                </tr>
                @foreach ($section->stations as $station)
                <tr>

                    <td>{{ $station->sort }}</td>
                    <td>
                        {{ $station->name_en }} / {{ $station->name_th }}
                        <small>
                            <p>{{ $station->description }}</p>
                        </small>
                    </td>
                    <td>{{ $station->nickname }}</td>
                    <td>
                        <x-station.label-type :type="$station->type" />
                    </td>
                    <td>{{ $station->piername_en }}</td>
                    <td>
                        <x-switch :isactive="$station->isactive" :action="route('station.changeStatus',['station'=>$station])" />

                    </td>
                    <td class="text-end">
                        <x-button.dropdown :editUrl="route('station.edit',['station'=>$station])" :deleteUrl="route('station.destroy',['station'=>$station->id])">

                        </x-button.dropdown>
                    </td>
                </tr>
                @endforeach

                @endforeach
            </tbody>
        </table>
    </div>
</x-card>
@stop
