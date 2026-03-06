@extends('layouts.default')

@section('content')

<x-card>
    <div class="row">
        <div class="col-12 mb-3 text-end">
            <x-button.new :href="route('broker.createUser', ['broker' => $broker])" />
        </div>
        <div class="col-12">

            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($broker->users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }} @if ($user->isdefault == 'Y') <span class="badge bg-primary">Default</span> @endif</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->mobile }}</td>
                        <td class="text-end">
                            @if ($user->isdefault == 'N')
                            <x-button.dropdown :editUrl="route('broker.editUser', ['user' => $user])" :deleteUrl="route('broker.destroyUser', ['user' => $user])" />
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-card>

@stop
