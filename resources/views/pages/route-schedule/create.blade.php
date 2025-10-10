@extends('layouts.default')

@section('style')
<style>
    .sticky-element {
        position: sticky;
        bottom: 1rem;
        left: auto;
        right: auto;
        z-index: 8;
        background-color: #f5f5f9;
        box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        padding: 1rem;
        width: calc(100% - 2rem);
        /* หรือกำหนด max-width ตรงนี้ */
        max-width: 1200px;
        margin: 0 auto;
        border-radius: 8px;
        height: 70px;
        margin-top: 20px;
    }

</style>
@stop

@section('content')

<x-card>
    <x-form :action="route('routeSchedule.store')" :isshow_button="false">
        <div class="row">

            <div class="col-12 col-lg-6">
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
            </div>

        </div>
        <div class="row">
            <div class="col-12">

                <div class="row">



                    <div class="col-12 col-lg-6" id="box-date">
                        <div class="row">
                            <div class="col-6">
                                <x-form.float.date-picker name="startdate" label="start date" />
                            </div>
                            <div class="col-6">
                                <x-form.float.date-picker name="enddate" label="end date" />
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6" id="box-day">
                        <div class="mb-2">
                            <div class="form-check form-check-inline form-check-success">
                                <input class="form-check-input" type="checkbox" id="monday" name="days[]" value="MON" checked>
                                <label class="form-check-label" for="monday">Monday</label>
                            </div>
                            <div class="form-check form-check-inline form-check-success">
                                <input class="form-check-input" type="checkbox" id="tuesday" name="days[]" value="TUE" checked>
                                <label class="form-check-label" for="tuesday">Tuesday</label>
                            </div>
                            <div class="form-check form-check-inline form-check-success">
                                <input class="form-check-input" type="checkbox" id="wednesday" name="days[]" value="WED" checked>
                                <label class="form-check-label" for="wednesday">Wednesday</label>
                            </div>
                            <div class="form-check form-check-inline form-check-success">
                                <input class="form-check-input" type="checkbox" id="thursday" name="days[]" value="THU" checked>
                                <label class="form-check-label" for="thursday">Thursday</label>
                            </div>
                            <div class="form-check form-check-inline form-check-success">
                                <input class="form-check-input" type="checkbox" id="friday" name="days[]" value="FRI" checked>
                                <label class="form-check-label" for="friday">Friday</label>
                            </div>
                            <div class="form-check form-check-inline form-check-success">
                                <input class="form-check-input" type="checkbox" id="saturday" name="days[]" value="SAT" checked>
                                <label class="form-check-label" for="saturday">Saturday</label>
                            </div>
                            <div class="form-check form-check-inline form-check-success">
                                <input class="form-check-input" type="checkbox" id="sunday" name="days[]" value="SUN" checked>
                                <label class="form-check-label" for="sunday">Sunday</label>
                            </div>
                        </div>


                    </div>
                </div>

            </div>
        </div>
        <hr>
        <div class="nav-align-top">
            <ul class="nav nav-pills mb-4" role="tablist">
                <li class="nav-item">
                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-station" aria-controls="navs-pills-top-home" aria-selected="true">
                        Select From Station
                    </button>
                </li>
                <li class="nav-item">
                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-template" aria-controls="navs-pills-top-profile" aria-selected="false">
                        Select From Template
                    </button>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="navs-station" role="tabpanel">
                    <div class="row mb-5">
                        <div class="col-12 mb-3">
                            <div class="row">
                                <div class="col-6">
                                    <x-route.selection-depart-station />
                                </div>
                                <div class="col-6">
                                    <x-route.selection-dest-station />
                                </div>
                            </div>
                        </div>

                        <div lang="col-12">
                            <table class="table table-sm table-hover" id="tb-route">
                                <thead>
                                    <tr class="bg-secondary-subtle">
                                        <th>
                                            <div class="form-check form-check-success mb-0">
                                                <input class="form-check-input" type="checkbox" id="select-all" />
                                                <label class="form-check-label" for="select-all">Select All</label>
                                            </div>
                                        </th>
                                        <th></th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="navs-template" role="tabpanel">
                    <div class="row">
                        <div class="col-12 text-end mb-3">
                            <x-button.new :href="route('template.create')" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-hover" id="template-table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Name</th>
                                        <th class="text-center">Total Route</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($templates as $template)
                                    <tr>
                                        <td>
                                            <div class="form-check form-check-inline form-check-success">
                                                <input class="form-check-input" type="checkbox" id="{{ $template->id }}" name="template_ids[]" value="{{ $template->id }}">

                                            </div>
                                        </td>
                                        <td>{{ $template->name }}</td>
                                        <td class="text-center">{{ count($template->templateLines) }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('template.show',['template'=>$template->id]) }}" target="_blank">
                                                <i class="icon-base ti tabler-list"></i>
                                            </a>
                                            <x-button.edit />
                                            <x-button.delete />

                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="sticky-element bg-label-secondary d-flex justify-content-sm-between align-items-sm-center flex-column flex-sm-row">
            <h5 class="card-title mb-sm-0 me-2"></h5>
            <div class="action-btns">
                <a href="{{ route('routeSchedule.index') }}" class="btn btn-label-primary me-4">
                    <span class="align-middle"> Back</span>
                </a>
                <button type="submit" class="btn btn-success btn-lg">SAVE</button>
            </div>
        </div>

    </x-form>

</x-card>
@stop


@section('script')

<script>
    function getRoutes() {
        showLoading();
        let depart_station_id = $('#depart_station_id').val();
        let dest_station_id = $('#dest_station_id').val();
        $.get(`/api/route?depart_station_id=${depart_station_id}&dest_station_id=${dest_station_id}`, function(data) {
            console.log(data);
            let tableBody = $('#tb-route tbody');
            tableBody.empty(); // ล้างข้อมูลเก่าในตารางก่อน

            // วนลูปข้อมูลจาก API
            $.each(data.data, function(index, item) {
                let row = `<tr>
                    <td colspan="2"><strong>${item.depart_station_name} <i class="base-icon ti tabler-circle-chevrons-right"></i> ${item.dest_station_name}</strong></td>
                  </tr>`;
                tableBody.append(row);

                $.each(item.sub_routes, function(index2, sub_route) {
                    let row = `<tr>
                    <td>
                        <div class="form-check form-check-success">
                            <input class="form-check-input" type="checkbox" name="sub_route_ids[]" value="${sub_route.sub_route_id}" id="${sub_route.sub_route_id}" />
                            <label class="form-check-label" for="${sub_route.sub_route_id}">${sub_route.depart_time} / ${sub_route.arrival_time}</label>
                        </div>
                    </td>

                    <td></td>
                  </tr>`;
                    tableBody.append(row);
                });

            });
            hideLoading();
        }).fail(function() {
            hideLoading();
            alert('ไม่สามารถโหลดข้อมูลจาก /route ได้');
        });
    }

    var bsRangePickerSingle = $('.bs-rangepicker-single');
    if (bsRangePickerSingle.length) {
        bsRangePickerSingle.daterangepicker({
            singleDatePicker: true
            , opens: isRtl ? 'left' : 'right'
            , "locale": {
                "format": "DD/MM/YYYY"
            , }
        });
    }

    $(document).ready(function() {
        getRoutes();
        $('#rule_type').on('change', function() {
            let value = $(this).val();
            if (value === 'RECURRING') {
                $('#box-day').show();
                $('#box-date').hide();
            } else {
                $('#box-day').hide();
                $('#box-date').show();
            }
        });

        $('#depart_station_id, #dest_station_id').on('change', function() {
            getRoutes();

        });

        $('#select-all').on('change', function() {
            const isChecked = $(this).is(':checked');
            $('input[name="sub_route_ids[]"]').prop('checked', isChecked);
        });


    });


    $(document).ready(function() {
        $("#template-table tbody tr").click(function(e) {
            if (!$(e.target).is('input[type="checkbox"]')) {
                let checkbox = $(this).find('input[type="checkbox"]');
                checkbox.prop('checked', !checkbox.prop('checked'));
            }
        });
    });

</script>
@stop
