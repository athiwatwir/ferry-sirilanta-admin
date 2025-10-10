@extends('layouts.default')


@section('content')

<x-card>
    <div class="row">
        <div class="col-12">
            <x-table.datatabble>
                <thead>
                    <tr>
                        <th>time</th>
                        <th>Document No</th>
                        <th>Agent</th>
                        <th>Customer</th>
                        <th>Total Amt</th>
                        <th>Ref.</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>

                </tbody>
            </x-table.datatabble>
        </div>
    </div>
</x-card>
@stop
