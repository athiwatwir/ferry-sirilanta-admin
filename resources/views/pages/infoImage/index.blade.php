@extends('layouts.default')

@section('content')
<x-card>
    <div class="row">
        <div class="col-12 text-end">
            <x-button.new text="Upload New" :href="route('infoImage.create')" />
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($infoImages as $infoImage)
                    <tr>
                        <td>{{ $infoImage->type=='route_map'?'Route Map':'Time Table' }}</td>
                        <td>{{ $infoImage->name }}</td>
                        <td>
                            <div class="avatar me-2">

                                <img src="{{ $infoImage->image_path }}" alt="{{ $infoImage->name }}" class="rounded">
                              </div>
                             </td>
                        <td class="text-end">
                            <x-button.edit :url="route('infoImage.edit',['infoImage'=>$infoImage])" />
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-card>
@stop
