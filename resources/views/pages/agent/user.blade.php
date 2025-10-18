@extends('layouts.default')


@section('content')
<x-agent.header :agent="$agent" active="user" />

<x-card>
    <div class="row">
        <div class="col-12 mb-3 text-end">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modal-create">Create User</button>
        </div>
        <div class="col-12">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <x-label-date-time :datetime="$user->created_at" />
                        </td>
                        <td>
                            <x-label-date-time :datetime="$user->updated_at" />
                        </td>
                        <td class="text-center">
                            <x-button.delete :url="route('agentUser.destroy',['agentUser'=>$user->id])" />
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</x-card>

@stop

@section('modal')

<x-modal id="modal-create" title="Add User">
    <x-form type="modal" :action="route('agentUser.store')">
        <input type="hidden" name="agent_id" id="" value="{{ $agent->id }}">
        <input type="hidden" name="role" id="" value="{{ $agent->type }}">
        <div class="row">
            <div class="col-12">
                <x-form.float.input name="name" label="full name" />
            </div>
            <div class="col-12">
                <x-form.float.input name="email" label="email" />
            </div>
            <div class="col-12">
                <x-form.float.input name="password" label="password" type="password" />
            </div>
        </div>
    </x-form>
</x-modal>
@stop
