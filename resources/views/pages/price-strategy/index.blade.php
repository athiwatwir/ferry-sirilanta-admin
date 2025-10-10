@extends('layouts.default')

@section('content')
<x-card>
    <div class="row">
        <div class="col text-end">
            <x-button.new-modal modal_id="modal-create" />
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Calculate Type By</th>
                        <th>Price By</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($priceStrategies as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $calculateTypes[$item->calculate_type] }}</td>
                        <td>{{ $calculateMethods[$item->method] }}</td>
                        <td>
                            <x-button.switch :isactive="$item->isactive" url="{{ route('priceStrategy.changeStatus',['priceStrategy'=>$item]) }}" />
                        </td>
                        <td class="text-end">
                            <x-button.edit url="{{ route('priceStrategy.edit',['priceStrategy'=>$item->id]) }}" />
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
<script src="{{ asset('js/change-switch-form.js') }}"></script>
<script src="{{ asset('js/destroy-form.js') }}"></script>
@stop


@section('modal')
<x-modal id="modal-create" title="Create New Price Strategy">
    <x-form type="modal" action="{{ route('priceStrategy.store') }}">
        <input type="hidden" name="ismaster" value="Y" id="">
        <div class="row">
            <div class="col-12">
                <x-form.float.input name="name" label="Name" />
            </div>
            <div class="col-12">
                <x-form.float.selection name="calculate_type" label="Calculate Type By" :options="$calculateTypes" />
            </div>
            <div class="col-12">
                <x-form.float.selection name="method" label="Price By" :options="$calculateMethods" />
            </div>
        </div>
    </x-form>
</x-modal>
@stop
