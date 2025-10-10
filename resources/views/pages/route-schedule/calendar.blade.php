@extends('layouts.default')

@section('content')
<x-card>
    <div class="row">
        <div class="col-12 col-lg-8">
            <x-station.route-title-small :departStation="$subRoute->route->departStation" :destStation="$subRoute->route->destStation" />
        </div>
        <div class="col-12 col-lg-4">
            <strong>
                <x-label-time :time="$subRoute->depart_time" /></strong>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-chevrons-right">
                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                <path d="M7 7l5 5l-5 5" />
                <path d="M13 7l5 5l-5 5" /></svg>

            <strong>
                <x-label-time :time="$subRoute->arrival_time" /></strong>

        </div>
    </div>
</x-card>

<div class="row">
    @foreach ($months as $month)
    <div class="col-12">
        <x-card>
            <x-calendar date="{{ $month }}" />
        </x-card>

    </div>
    @endforeach

</div>

@stop

@section('modal')
<x-modal id="modal-schedule" title="Close/Oprn Route">
    <x-form type="modal" :action="route('routeSchedule.dailyStore',['subRoute'=>$subRoute])">
        <input type="hidden" name="startdate" id="startdate">
        <input type="hidden" name="enddate" id="enddate">
        <div class="row">
            <div class="col-6 mb-2">
                <div class="form-check form-check-success custom-option custom-option-basic">
                    <label class="form-check-label custom-option-content" for="isopen-y">
                        <input class="form-check-input" type="radio" name="isopen" value="Y" id="isopen-y" checked />
                        <span class="custom-option-header pb-0">
                            <span class="h6 mb-0">Open Route</span>
                        </span>
                    </label>
                </div>
            </div>
            <div class="col-6">
                <div class="form-check form-check-danger custom-option custom-option-basic">
                    <label class="form-check-label custom-option-content" for="isopen-n">
                        <input class="form-check-input" type="radio" value="N" name="isopen" id="isopen-n" />
                        <span class="custom-option-header pb-0">
                            <span class="h6 mb-0">Close Route</span>
                        </span>
                    </label>
                </div>
            </div>
        </div>


    </x-form>
</x-modal>

@stop



@section('script')

<script>
    let daily_items = @json($dailyItems);
    $(document).ready(function() {
        daily_items.forEach(function(date) {

            let selector = '[data-id="day-' + date + '"]';
            $(selector).addClass('text-success');

            //box-agent-avatar-
            $('[data-id="box-agent-avatar-' + date + '"]').show();
        });
    });

</script>


<script>
    document.querySelectorAll('[data-action="modal"]').forEach(function(el) {
        el.addEventListener('click', function() {
            let date = this.dataset.date;
            document.getElementById('startdate').value = date;
            document.getElementById('enddate').value = date;


            var modal = new bootstrap.Modal(document.getElementById('modal-schedule'));
            modal.show();
        });
    });

</script>


@stop
