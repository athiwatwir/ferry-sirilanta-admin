@extends('layouts.default')

@section('content')
<x-card>

    <div class="row">
        <div class="col-12 col-lg-10">
            <x-station.route-title :departStation="$route->departStation" :destStation="$route->destStation" />

        </div>
        <div class="col-12 col-lg-2 text-end">
            <label class="switch switch-success switch-square switch-lg">
                <input type="checkbox" class="switch-input switch-button" @checked($route->isactive=='Y') data-action="{{ route('route.changeStatus',['route'=>$route->id]) }}"/>
                <span class="switch-toggle-slider">
                    <span class="switch-on">
                        <i class="icon-base ti tabler-check"></i>
                    </span>
                    <span class="switch-off">
                        <i class="icon-base ti tabler-x"></i>
                    </span>
                </span>
                <span class="switch-label">On</span>
            </label>
        </div>

    </div>
    <hr>

    <div class="row">
        <div class="col-12">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Depart Time</th>
                        <th>Arrive Time</th>
                        <th>Type</th>
                        <th>Seat</th>
                        <th>Price</th>
                        <th>Pricing Rule</th>
                        <th>Cut-Off</th>
                        <th>Status</th>
                      
                    </tr>
                </thead>
                <tbody>
                    @foreach ($route->subRoutes as $subRoute)
                    <tr @if ($subRoute->type=='activity')
                        class="table-active"
                        @endif>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <x-label-time :time="$subRoute->depart_time" />
                            <br><small>{{ $subRoute->origin_timezone }}</small>
                        </td>
                        <td>
                            <x-label-time :time="$subRoute->arrival_time" />
                            <br><small>{{ $subRoute->destination_timezone }}</small>
                        </td>
                        <td class="">
                            @if ($subRoute->type=='activity')
                            <span class="badge bg-label-info">Activity route</span><br>
                            @endif
                            {{ $subRoute->boat_type }} <br />

                            <div class="col-12 d-flex align-items-center flex-wrap">

                                @if (!empty($subRoute->icon_set))
                                @foreach ($subRoute->icon_set as $icon)
                                <div class="avatar avatar-sm me-4 position-relative">
                                    <img src="{{ asset('images/icon-route/ico-'.$icon.'.png') }}" alt="Avatar">
                                    <small></small>

                                </div>
                                @endforeach
                                @endif
                            </div>
                        </td>
                        <td>
                            <x-label-number :number="$subRoute->seatamt" />
                        </td>
                        <td>
                            <p class="mb-0"><strong>R:</strong>
                                <x-label-price :price="$subRoute->price" />
                            </p>
                            <p class="mb-0"><strong>C:</strong>
                                <x-label-price :price="$subRoute->child_price" />
                            </p>
                            <p class="mb-0"><strong>I:</strong>
                                <x-label-price :price="$subRoute->infant_price" />
                            </p>

                        </td>

                        <td class="text-center">
                            @if (!empty($subRoute->price_strategy_id) && $subRoute->priceStrategy->isactive =='Y')
                            <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="top" title="เปิดใช้งาน Pricing Rule – กฎการคิดราคา"><i class="icon-base ti tabler-circle-percentage"></i></a>
                            @endif


                        </td>
                        <td>
                            @if (!empty($subRoute->close_time))
                            <x-label-time :time="$subRoute->close_time" />
                            @endif
                        </td>
                        <td>
                            <x-switch :action="route('subRoute.changeStatus',['subRoute'=>$subRoute->id])" :isactive="$subRoute->isactive" />
                        </td>
                       
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</x-card>

<div class="row" style="display: none;" id="box-create">

    <div class="col-12 mt-3">

        <x-card>
            <x-form action="{{ route('subRoute.store') }}">
                <input type="hidden" name="route_id" id="route_id" value="{{ $route->id }}">

                <div class="row">
                    <div class="col-12 text-center">
                        <h4>Create Route Time</h4>
                    </div>
                    <div class="col-12 col-lg-6 border-end">
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <x-form.float.input-time name="depart_time" label="Depart Time" />

                                <x-selection.time-zone name="origin_timezone" label="Depart Timezone" />
                            </div>
                            <div class="col-12 col-lg-6">

                                <x-form.float.input-time name="arrival_time" label="Arrive Time" />
                                <x-selection.time-zone name="destination_timezone" label="Arrival Timezone" />
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6 col-lg-6">
                                <x-form.float.selection name="boat_type" label="Ferry Type" :options="$ferryTypes" default="ferry" />
                            </div>
                            <div class="col-6 col-lg-6">
                                <x-form.float.selection name="boat_type2" label="Ferry Type#2" :options="$ferryTypes" :isrequire="false" :isempty="true" />
                            </div>
                            <div class="col-12 col-lg-6">
                                <x-form.float.input name="seatamt" label="Seat" />
                            </div>


                            <div class="col-12 col-lg-6">
                                <x-form.float.input-time name="_close_time" label="Cut-Off(Hrs.)" :isrequire="false" help="จำนวน ชม. ก่อน  Depart Time" />
                                <input type="hidden" name="close_time" id="close_time">
                            </div>
                            <div class="col-12 col-lg-6">
                                <strong class="text-danger" id="box-result-time"></strong>
                            </div>

                        </div>
                    </div>

                    <div class="col-12 col-lg-6">
                        <div class="row">
                            <div class="col-12">
                                <x-form.float.selection :options="['transport'=>'Transport Route','activity'=>'Activity Route']" name="type" label="Route Type" />
                            </div>
                        </div>
                        <h5 class="card-header">Price Section</h5>
                        <div class="row">
                            <div class="col">
                                <x-form.float.input name="price" label="Regular Price" />
                            </div>
                            <div class="col">
                                <x-form.float.input name="child_price" label="Child Price" />
                            </div>
                            <div class="col">
                                <x-form.float.input name="infant_price" label="Infant Price" />
                            </div>
                        </div>
                        <h5>Icon Sets</h5>
                        <div class="row">
                            <div class="col-12">
                                <x-route.icon-set />
                            </div>
                        </div>

                    </div>

                </div>
                <hr>
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <x-form.float.input name="text_1" label="Text 1" :isrequire="false" />
                    </div>
                    <div class="col-12 col-lg-6">
                        <x-form.float.input name="text_2" label="Text 2" :isrequire="false" />
                    </div>
                    <div class="col-12 col-lg-6">
                        <x-form.float.textarea name="master_from" :isrequire="false" label="Master From" height="150" :value="$route->departStation->master_from" />
                    </div>
                    <div class="col-12 col-lg-6">
                        <x-form.float.textarea name="master_to" :isrequire="false" label="Master To" height="150" :value="$route->destStation->master_to" />
                    </div>
                    <div class="col-12 col-lg-6">
                        <x-form.float.textarea name="info_from" :isrequire="false" label="Information From" height="150" />
                    </div>
                    <div class="col-12 col-lg-6">
                        <x-form.float.textarea name="info_to" :isrequire="false" label="Information To" height="150" />
                    </div>
                </div>
            </x-form>
        </x-card>
    </div>
</div>

@stop


@section('script')

<script src="{{ asset('assets/vendor/libs/cleave-zen/cleave-zen.js') }}"></script>
<script src="{{ asset('js/sub-route/time-calculate.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#bt-create').on('click', function() {
            $('#box-create').show();
        });
    });

</script>
@stop
