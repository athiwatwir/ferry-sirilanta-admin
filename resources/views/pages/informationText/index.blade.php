@extends('layouts.default')

@section('content')
<x-card>
    <div class="row">
        <div class="col-12 text-end">
            <x-button.new :href="route('informationText.create')" />
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
                        <td>{{ $positions[$item['position']] }}</td>
                        <td>{{ $item['title'] }}</td>
                        <td>{!! $item['body'] !!}</td>
                        <td>
                            <div class="d-inline-flex gap-2">
                                <x-button.edit :url="route('informationText.edit', $item['id'])" />
                                <x-button.delete :url="route('informationText.destroy', $item['id'])" />
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-card>
@stop
