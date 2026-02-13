@extends('layouts.default')

@section('content')
<x-card>
    <div class="row">
        <div class="col-9 col-lg-6">

        </div>
        <div class="col-3 col-lg-6 text-end">
            <x-button.new :href="route('broker.create', ['type' => 'broker'])" />
        </div>
    </div>
    <hr>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($brokers as $broker)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $broker->name }}</td>
                <td></td>
                <td class="text-end">
                    <a href="{{ route('broker.show', ['broker' => $broker]) }}" class="btn btn-outline-secondary ">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</x-card>
@endsection
