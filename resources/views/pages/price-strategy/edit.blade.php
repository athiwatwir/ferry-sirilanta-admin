@extends('layouts.default')

@section('content')
<x-card>
    <x-form action="{{ route('priceStrategy.update',['priceStrategy'=>$priceStrategy->id]) }}">
        @method('put')
        <input type="hidden" name="ismaster" value="Y" id="">
        <div class="row">
            <div class="col-12 col-lg-6">
                <x-form.float.input name="name" label="Name" value="{{ $priceStrategy->name }}" />
            </div>
            <div class="col-12 col-lg-3">
                <x-form.float.selection name="calculate_type" label="Calculate Type By" :default="$priceStrategy->calculate_type" :options="$calculateTypes" help="If you change this data,System will clear Formula List data." />
            </div>
            <div class="col-12 col-lg-3">
                <x-form.float.selection name="method" label="Price By" :default="$priceStrategy->method" :options="$calculateMethods" help="If you change this data,System will clear Formula List data." />
            </div>
            <div class="col-12 col-lg-6">
                <x-form.float.textarea />
            </div>
        </div>
    </x-form>

</x-card>

<x-card>
    <div class="row">
        <div class="col">
            <h4><i class="icon-base ti tabler-calculator"></i>Formula List สูตรการคำนวณ</h4>
        </div>
        <div class="col text-end">
            <x-button.new-modal modal_id="modal-create" text="New Formula" />
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <x-table.price-strategy-line :priceStrategy="$priceStrategy" />
        </div>
    </div>
</x-card>


@stop

@section('script')

<script src="{{ asset('js/destroy-form.js') }}"></script>
<script src="{{ asset('js/change-active-form.js') }}"></script>

@stop


@section('modal')
<x-modal id="modal-create" title="Create New Price Strategy">
    <x-form type="modal" action="{{ route('priceStrategyLine.store') }}">
        <input type="hidden" name="price_strategy_id" id="price_strategy_id" value="{{ $priceStrategy->id }}">
        <div class="row">
            <div class="col-12">
                <x-form.float.selection name="condition" label="Condition" :options="['less-than'=>'Less Than']" />
            </div>
            <div class="col-12">
                <x-form.float.input name="unit" label="{{ $calculateTypes[$priceStrategy->calculate_type] }}" />
            </div>

            <div class="col-12 col-lg-4">
                <x-form.float.input name="price" label="Regular +{{ $calculateMethods[$priceStrategy->method] }}" />
            </div>
            <div class="col-12 col-lg-4">
                <x-form.float.input name="child_price" label="Child +{{ $calculateMethods[$priceStrategy->method] }}" />
            </div>
            <div class="col-12 col-lg-4">
                <x-form.float.input name="infant_price" label="Infant +{{ $calculateMethods[$priceStrategy->method] }}" />
            </div>
        </div>
    </x-form>
</x-modal>
@stop
