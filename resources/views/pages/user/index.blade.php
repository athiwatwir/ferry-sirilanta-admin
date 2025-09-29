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
                        <th>Name</th>
                        <th>Email</th>
                        <th>Display</th>
                        <th>Responsible Station</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</x-card>
@stop
