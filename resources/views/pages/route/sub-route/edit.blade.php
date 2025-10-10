@extends('layouts.default')

@section('content')
<x-card>
    <div class="row">
        <div class="col-12 col-lg-6">
            <h4><span class="text-primary">From:</span>
                <x-station.label-name :station="$subRoute->route->departStation" />
            </h4>
        </div>
        <div class="col-12 col-lg-6">
            <h4><span class="text-primary">To:</span>
                <x-station.label-name :station="$subRoute->route->destStation" />
            </h4>
        </div>
    </div>
    <x-form action="{{ route('subRoute.update',['subRoute'=>$subRoute]) }}">

        @method('put')
        <input type="hidden" name="route_id" id="route_id" value="{{ $subRoute->route_id }}">
        <div class="row">
            <div class="col-12 col-lg-6 border-end">
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <x-form.float.input-time name="depart_time" label="Depart Time" :time="$subRoute->depart_time" />

                        <x-selection.time-zone name="origin_timezone" label="Depart Timezone" />
                    </div>
                    <div class="col-12 col-lg-6">
                        <x-form.float.input-time name="arrival_time" label="Arrive Time" :time="$subRoute->arrival_time" />
                        <x-selection.time-zone name="destination_timezone" label="Arrival Timezone" />
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-6 col-lg-6">
                        <x-form.float.selection name="boat_type" label="Ferry Type" :options="$ferryTypes" :default="$subRoute->boat_type" />
                    </div>
                    <div class="col-6 col-lg-6">
                        <x-form.float.selection name="boat_type2" label="Ferry Type#2" :options="$ferryTypes" :isrequire="false" :isempty="true" :default="$subRoute->boat_type2" />
                    </div>
                    <div class="col-12 col-lg-6">
                        <x-form.float.input name="seatamt" label="Seat" value="{{ $subRoute->seatamt }}" />
                    </div>


                    <div class="col-12 col-lg-6">
                        <x-form.float.input-time name="_close_time" label="Cut-Off(Hrs.)" :isrequire="false" help="จำนวน ชม. ก่อน  Depart Time" :time="$subRoute->duration" />
                        <input type="hidden" name="close_time" id="close_time" value="{{ \Carbon\Carbon::parse($subRoute->close_time)->format('H:i') }}">
                    </div>
                    <div class="col-12 col-lg-6">
                        <strong class="text-danger" id="box-result-time">
                            @if (!empty($subRoute->close_time))
                            Cut-Off เวลา:
                            <x-label-time :time="$subRoute->close_time" />
                            @endif
                        </strong>
                    </div>


                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="row">
                    <div class="col-12">
                        <x-form.float.selection :options="['transport'=>'Transport Route','activity'=>'Activity Route']" name="type" label="Route Type" :default="$subRoute->type" />
                    </div>
                </div>
                <h5 class="">Price Section</h5>
                <div class="row">
                    <div class="col">
                        <x-form.float.input name="price" label="Regular Price" value="{{ $subRoute->price }}" />
                    </div>
                    <div class="col">
                        <x-form.float.input name="child_price" label="Child Price" value="{{ $subRoute->child_price }}" />
                    </div>
                    <div class="col">
                        <x-form.float.input name="infant_price" label="Infant Price" value="{{ $subRoute->infant_price }}" />
                    </div>
                </div>
                <h5>Icon Sets</h5>
                <div class="row">
                    <div class="col-12">
                        <x-route.icon-set :icon_set="$subRoute->icon_set" />
                    </div>
                </div>
            </div>

        </div>

        <hr>
        <div class="row">
            <div class="col-12 col-lg-6">
                <x-form.float.input name="text_1" label="Text 1" :isrequire="false" :value="$subRoute->text_1" />
            </div>
            <div class="col-12 col-lg-6">
                <x-form.float.input name="text_2" label="Text 2" :isrequire="false" :value="$subRoute->text_2" />
            </div>
            <div class="col-12 col-lg-6">
                <x-form.float.textarea name="master_from" :isrequire="false" label="Master From" height="150" :value="$subRoute->master_from" />
            </div>
            <div class="col-12 col-lg-6">
                <x-form.float.textarea name="master_to" :isrequire="false" label="Master To" height="150" :value="$subRoute->master_to" />
            </div>
            <div class="col-12 col-lg-6">
                <x-form.float.textarea name="info_from" :isrequire="false" label="Information From" height="150" :value="$subRoute->info_from" />
            </div>
            <div class="col-12 col-lg-6">
                <x-form.float.textarea name="info_to" :isrequire="false" label="Information To" height="150" :value="$subRoute->info_to" />
            </div>
        </div>
    </x-form>
</x-card>

<x-card>
    <div class="row">
        @if (!empty($priceStrategy))
        <div class="col-12 pt-2 pb-2 mb-3 ">
            <div class="row">
                <div class="col">
                    @if ($priceStrategy->isactive == 'N')
                    <h5 class="text-warning">ใช้ราคาขายจาก Price Section</h5>
                    @endif
                </div>

            </div>
        </div>
        @endif

        <div class="col-12 col-lg-5">
            <h4>Pricing Rule – กฎการคิดราคา</h4>
        </div>
        <div class="col">
            <div class="row">
                <div class="col-6">
                    <div class="d-grid gap-2 mx-auto">
                        @if (empty($subRoute->priceStrategy))
                        <button type="button" class="btn btn-label-warning" data-bs-toggle="modal" data-bs-target="#modal-create">เปิดใช้งาน</button>
                        @else
                        <button type="button" class="btn btn-label-dark" data-bs-toggle="modal" data-bs-target="#modal-create">Setting</button>
                        @endif
                    </div>
                </div>
                <div class="col-6 text-end">
                    @if (!empty($subRoute->priceStrategy))
                    <x-button.switch :isactive="$priceStrategy->isactive" url="{{ route('priceStrategy.changeStatus',['priceStrategy'=>$subRoute->priceStrategy]) }}" size="switch-lg" />
                    @endif

                    <div class="d-grid gap-2 mx-auto">
                        <button class="btn btn-label-success" type="button" data-bs-toggle="modal" data-bs-target="#modal-load" style="display: none;">
                            <i class="icon-base ti tabler-book-download me-1"></i>
                            <span class="align-middle">Load Setting from Master Pricing Rule</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr>
    @if (empty($subRoute->priceStrategy))
    <div class="row">
        <div class="col text-center">
            <h5 class="text-warning">ใช้ราคาขายจาก Price Section</h5>
        </div>
    </div>
    @else

    <div class="row">
        <div class="col text-center">
            <h5>Calculate By: {{ $priceStrategy->calculate_type }}</h5>
        </div>
        <div class="col text-center">
            <h5>Price By: {{ $priceStrategy->method }}</h5>
        </div>
        <div class="col text-end">
            <x-button.new-modal modal_id="modal-create-formular" text="Add Formula" />
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <strong><i class="icon-base ti tabler-calculator"></i>Formula List สูตรการคำนวณ</strong>
        </div>

        <div class="col-12">
            <x-table.price-strategy-line :priceStrategy="$priceStrategy" :subRoute="$subRoute" />
        </div>
    </div>
    @endif

</x-card>
@stop


@section('modal')
<x-modal id="modal-create" title="Pricing Rule – กฎการคิดราคา">
    <x-form type="modal" action="{{ route('subRoute.priceStrategy',['subRoute'=>$subRoute]) }}">
        @if (!empty($subRoute->priceStrategy))
        <input type="hidden" name="price_strategy_id" id="price_strategy_id" value="{{ $subRoute->price_strategy_id }}">
        @endif
        <div class="row">
            <div class="col-12">
                <x-form.float.selection name="calculate_type" label="Calculate Type By" :options="$calculateTypes" default="{{ empty($subRoute->priceStrategy)?'':$subRoute->priceStrategy->calculate_type }}" />
            </div>
            <div class="col-12">
                <x-form.float.selection name="method" label="Price By" :options="$calculateMethods" default="{{ empty($subRoute->priceStrategy)?'':$subRoute->priceStrategy->method }}" />
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col">
                <p class="text-danger">*** หากมีการเปลี่ยนแปลงวิธีการคำนวน ระบบจะ reset ตารางสูตรการคำนวณทั้งหมด</p>
            </div>
        </div>
    </x-form>
</x-modal>

<x-modal id="modal-load" title="Load Setting from Master Pricing Rule">
    <x-form type="modal" action="{{ route('subRoute.priceStrategy',['subRoute'=>$subRoute]) }}">
        @if (!empty($subRoute->priceStrategy))
        <input type="hidden" name="price_strategy_id" id="price_strategy_id" value="{{ $subRoute->price_strategy_id }}">
        @endif
        <div class="row">
            <div class="col">
                <x-form.selection class="form-control-lg" name="master_price_strategy_id" :options="$priceStrategies" />
            </div>
        </div>
    </x-form>
</x-modal>

@if (!empty($subRoute->priceStrategy))
<x-modal id="modal-create-formular" title="Create New Formula สูตรการคำนวณ">
    <x-form type="modal" action="{{ route('priceStrategyLine.store') }}">
        <input type="hidden" name="price_strategy_id" id="price_strategy_id" value="{{ $priceStrategy->id }}">
        <div class="row">
            <div class="col-12 col-lg-6">
                <x-form.float.selection name="condition" label="Condition" :options="['less-than'=>'Less Than']" />
            </div>
            <div class="col-12 col-lg-6">
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

@endif
@stop


@section('script')
<script src="{{ asset('assets/vendor/libs/cleave-zen/cleave-zen.js') }}"></script>

<script src="{{ asset('js/sub-route/time-calculate.js') }}"></script>

@stop
