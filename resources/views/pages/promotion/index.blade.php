@extends('layouts.default')

@section('content')
<x-card>
    <div class="row">
        <div class="col-12 text-end mb-3">
            <x-button.new :href="route('promotion.create')" />
        </div>
        <div class="col-12">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Code</th>
                        <th>Date</th>
                        <th>Discount%</th>
                        <th>Max Use</th>
                        <th>Used</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($promotions as $item)
                    <tr>
                        <td>{{ $item['title'] }}</td>
                        <td>{{ $item['code'] }}</td>
                        <td>{{ $item['startdate'] }}/{{ $item['enddate'] }}</td>
                        <td>{{ $item['discount'] }}</td>
                        <td>{{ $item['max_use'] }}</td>
                        <td></td>
                        <td>
                            <x-label-active-status :isactive="$item['isactive']" />
                        </td>
                        <td>
                            <x-button.delete />
                            <x-button.edit :url="route('promotion.edit',['promotion'=>$item['id']])" />
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-card>

@stop
