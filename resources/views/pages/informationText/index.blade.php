@extends('layouts.default')

@section('content')
<x-card>
    <div class="row">
        <div class="col-12 text-end">
            <x-button.new />
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>Position</th>
                        <th>Title</th>
                        <th>Text/Message</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($informations as $item)
                    <tr>
                        <td>{{ $item['position'] }}</td>
                        <td>{{ $item['title'] }}</td>
                        <td>{!! $item['body'] !!}</td>
                        <td></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-card>
@stop
