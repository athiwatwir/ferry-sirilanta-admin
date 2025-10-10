@extends('layouts.default')


@section('content')
<x-card>
    <x-form :action="route('template.store')">
        <div class="row">
            <div class="col-12">
                <x-form.float.input label="Name" name="name" />
            </div>
        </div>

        <div class="row">
            <!-- Select Left -->
            <div class="col text-center">
                <h5>Available Routes</h5>
                <select multiple id="leftSelect" class="form-select" size="30">
                    @foreach ($routes as $route)
                    @foreach ($route->subRoutes as $subRoute)
                    <option value="{{ $subRoute->id }}">
                        <x-station.label-name :station="$route->departStation" /> >
                        <x-station.label-name :station="$route->destStation" /> |
                        <x-label-time :time="$subRoute->depart_time" />
                    </option>
                    @endforeach
                    @endforeach
                </select>
            </div>

            <!-- Buttons -->
            <div class="col-1 d-flex flex-column justify-content-center">
                <button id="toRight" type="button" class="btn btn-primary mb-2">&gt;&gt;</button>
                <button id="toLeft" type="button" class="btn btn-secondary">&lt;&lt;</button>
            </div>

            <!-- Select Right -->
            <div class="col text-center">
                <h5>Selected Routes</h5>
                <select multiple id="rightSelect" class="form-select" name="subroute_ids[]" size="30"></select>
            </div>
        </div>


    </x-form>
</x-card>
@stop


@section('script')
<script>
    $(document).ready(function() {
        $("form").submit(function() {
            $("#rightSelect option").prop("selected", true);
        });


        // เก็บลำดับต้นฉบับจากฝั่งซ้าย
        let originalOrder = [];
        $("#leftSelect option").each(function() {
            originalOrder.push($(this).text());
        });

        // ฟังก์ชันจัดเรียงตาม originalOrder
        function sortByOriginalOrder($select) {
            let options = $select.find("option").get();
            options.sort(function(a, b) {
                return originalOrder.indexOf($(a).text()) - originalOrder.indexOf($(b).text());
            });
            $select.empty().append(options);
        }

        // Move selected items to right
        $("#toRight").click(function() {
            $("#leftSelect option:selected").each(function() {
                $(this).remove().appendTo("#rightSelect");
            });
            sortByOriginalOrder($("#rightSelect")); // เรียงฝั่งขวา
        });

        // Move selected items to left
        $("#toLeft").click(function() {
            $("#rightSelect option:selected").each(function() {
                $(this).remove().appendTo("#leftSelect");
            });
            sortByOriginalOrder($("#leftSelect")); // เรียงฝั่งซ้าย
        });
    });

</script>

@stop
