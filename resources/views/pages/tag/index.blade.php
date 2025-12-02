@extends('layouts.default')

@section('content')

<x-card>
    <div class="row">
        <div class="col-12">
            <table class="table table-hover" id="myTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th></th>
                        <th>Name</th>
                        <th>Name TH</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tags as $tag)
                    <tr data-id="{{ $tag->id }}">
                        <td>
                            {{ $loop->iteration }}
                        </td>
                        <td>
                            <div class="avatar me-2">
                                <img src="{{ $tag->icon_1 }}" alt="Avatar" class="rounded-circle">
                            </div>
                        </td>
                        <td>{{ $tag->name }}</td>
                        <td>{{ $tag->name_th }}</td>
                        <td class="text-end">
                            <x-button.edit :url="route('tag.edit',['tag'=>$tag])" />

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


@stop
