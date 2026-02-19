@extends('layouts.default')

@section('content')
<x-card>
    <div class="row">
        <div class="col-9 col-lg-6">

        </div>
        <div class="col-3 col-lg-6 text-end">
            <x-button.new :href="route('agent.create', ['type' => 'agent'])" />
        </div>
    </div>
    <hr>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Code</th>
                <th>Email</th>
                <th class="text-end">Wallet Balance</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($agents as $agent)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $agent->name }}</td>
                <td>{{ $agent->code }}</td>
                <td>{{ $agent->user->email }}</td>
                <td class="text-end">
                    <x-label-price :price="$agent->agentAccount->wallet_balance" />
                </td>
                <td class="text-end">
                    <a href="{{ route('agent.show', ['agent' => $agent]) }}" class="btn btn-outline-secondary ">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</x-card>
@endsection
