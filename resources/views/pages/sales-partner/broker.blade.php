@extends('layouts.default')

@section('content')
<x-card>
    <div class="row">
        <div class="col-9 col-lg-6">

        </div>
        <div class="col-3 col-lg-6 text-end">
            <x-button.new :href="route('salesPartner.create', ['type' => 'broker'])" />
        </div>
    </div>
    <hr>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Point</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($brokers as $broker)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $broker->name }}</td>
                <td>{{ $broker->brokerPoint->balance }}</td>
                <td class="text-end">
                    <a href="{{ route('salesPartner.show', ['salesPartner' => $broker]) }}" class="btn btn-outline-secondary ">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</x-card>
@endsection
