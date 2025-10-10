@extends('layouts.default')

@section('content')

<x-card>
    <div class="row">
        <div class="col-12 mb-3 text-end">
            
        </div>
        <div class="col-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Full name</th>
                        <th>email</th>
                       
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->fullname }}</td>
                        <td>{{ $user->email }}</td>
                        
                        <td class="text-end">
                            <x-button.dropdown :editUrl="route('user.edit',['user'=>$user])" :deleteUrl="route('user.destroy',['user'=>$user->id])">

                            </x-button.dropdown>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-card>
@stop
