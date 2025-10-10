@extends('layouts.iframe')

@section('content')

<div class="row">
    <div class="col-5 text-center">
        <h3 class="mb-0">
            <x-label-time :time="$subRoute->depart_time" />
        </h3>
        <p class="mb-0">{{ $subRoute->origin_timezone }}</p>
        <h5 class="mb-0">

            <x-station.label-name :station="$subRoute->route->departStation" />
        </h5>
    </div>

    <div class="col-2 d-flex justify-content-center align-items-center">
        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="currentColor" class="icon icon-tabler icons-tabler-filled icon-tabler-arrow-big-right-lines">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M12.089 3.634a2 2 0 0 0 -1.089 1.78l-.001 2.585l-1.999 .001a1 1 0 0 0 -1 1v6l.007 .117a1 1 0 0 0 .993 .883l1.999 -.001l.001 2.587a2 2 0 0 0 3.414 1.414l6.586 -6.586a2 2 0 0 0 0 -2.828l-6.586 -6.586a2 2 0 0 0 -2.18 -.434l-.145 .068z" />
            <path d="M3 8a1 1 0 0 1 .993 .883l.007 .117v6a1 1 0 0 1 -1.993 .117l-.007 -.117v-6a1 1 0 0 1 1 -1z" />
            <path d="M6 8a1 1 0 0 1 .993 .883l.007 .117v6a1 1 0 0 1 -1.993 .117l-.007 -.117v-6a1 1 0 0 1 1 -1z" />
        </svg>
    </div>

    <div class="col-5 text-center">
        <h3 class="mb-0">
            <x-label-time :time="$subRoute->arrival_time" />
        </h3>
        <p class="mb-0">{{ $subRoute->destination_timezone }}</p>
        <h5 class="mb-0">
            <x-station.label-name :station="$subRoute->route->destStation" />
        </h5>
    </div>

    <div class="col-12 d-flex justify-content-center align-items-center flex-wrap p-5 text-center">

        @if (!empty($subRoute->icon_set))
        @foreach ($subRoute->icon_set as $icon)
        <div class="avatar avatar-xl me-4 position-relative">
            <img src="{{ asset('images/icon-route/ico-'.$icon.'.png') }}" alt="Avatar">
            <small></small>

        </div>
        @endforeach
        @endif
    </div>
</div>
<hr>
<div class="row">
    <div class="col-6 mb-4">
        <span class="">Seat/{{ $subRoute->seatamt }}</span>

    </div>
    <div class="col-6 mb-4">

        <span class="">Ferry Type/{{ $subRoute->boat_type }}</span>
    </div>
</div>
<hr>
<div class="row">

    <div class="col-4">
        <div class="bg-lighter px-3 py-2 rounded me-auto mb-4" bis_skin_checked="1">
            <h5 class="mb-0">
                <x-label-price :price="$subRoute->price" />
            </h5>
            <span class="text-body">Regular Price</span>
        </div>
    </div>
    <div class="col-4">
        <div class="bg-lighter px-3 py-2 rounded me-auto mb-4" bis_skin_checked="1">
            <h5 class="mb-0">
                <x-label-price :price="$subRoute->child_price" />
            </h5>
            <span class="text-body">Child Price</span>
        </div>
    </div>
    <div class="col-4">
        <div class="bg-lighter px-3 py-2 rounded me-auto mb-4" bis_skin_checked="1">
            <h5 class="mb-0">
                <x-label-price :price="$subRoute->infant_price" />
            </h5>
            <span class="text-body">Infant Price</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <strong class="text-primary">Pricing Rule – กฎการคิดราคา</strong>
        @if (!empty($priceStrategy))
        <div class="table-responsive">
            <x-table.price-strategy-line :priceStrategy="$priceStrategy" :subRoute="$subRoute" :isshowtool="false" />
        </div>

        @else
        <strong class="text-danger">ไม่ได้เปิดใช้งาน</strong>
        @endif

    </div>
</div>




@stop
