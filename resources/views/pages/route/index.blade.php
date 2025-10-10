@extends('layouts.default')

@section('content')

<x-card>
    <div class="row">
        <div class="col">
   
            <button class="btn btn-lg btn-outline-danger mb-2" disabled id="deleteBtn"><i class="icon-base ti tabler-trash me-1"></i> Delete Selected (<span id="selectedCount">0</span>)</button>
        </div>
        <div class="col">
            <form action="{{ route('route.index') }}" id="frm-search" method="GET">
                <div class="row">
                    <div class="col">
                        <x-station.selection name="depart_station_id" label="Station From" :isrequire="false" :selected="$depart_station_id" />
                    </div>
                    <div class="col">
                        <x-station.selection name="dest_station_id" label="Station To" :isrequire="false" :selected="$dest_station_id" />
                    </div>
                </div>
            </form>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-12">
            <x-table.datatabble class="table-sm table-hover">
                <thead>
                    <tr>
                        <th>
                            <div class="form-check form-check-success">
                                <input class="form-check-input" type="checkbox" value="" id="selectAll" />
                                <label class="form-check-label" for="selectAll">Select All</label>
                            </div>
                        </th>
                        <th>Route</th>

                        <th>Time Table</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($routes as $route)
                    <tr>
                        <td>
                            <div class="form-check form-check-success">
                                <input class="form-check-input route-checkbox" type="checkbox" value="" name="route_ids[]" />

                            </div>
                        </td>
                        <td action-url="{{ route('route.edit',['route'=>$route]) }}">
                            <x-station.route-title-small :departStation="$route->departStation" :destStation="$route->destStation" />
                        </td>

                        <td>
                            @foreach ($route->subRoutes as $subRoute)
                            <span class="badge bg-label-secondary">
                                <a href="javascript:void(0);" class="iframe-modal" modal-id="#modal-iframe-time" modal-url="{{ route('subRoute.show',['subRoute'=>$subRoute]) }}">
                                    <x-label-time :time="$subRoute->depart_time" /></a>
                            </span>
                            @endforeach
                        </td>
                        <td>
                            <x-switch :action="route('route.changeStatus',['route'=>$route->id])" :isactive="$route->isactive" />
                        </td>
                        <td class="text-end">
                            <x-button.dropdown :editUrl="route('route.edit',['route'=>$route])" :deleteUrl="route('route.destroy',['route'=>$route->id])">

                            </x-button.dropdown>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </x-table.datatabble>
        </div>
    </div>
</x-card>

@stop


@section('script')

<script>
    $(document).ready(function() {
        $('#depart_station_id, #dest_station_id').on('change', function() {
            showLoading();
            $('#frm-search').submit();
        });

        $('.iframe-modal').on('click', function() {
            let id = $(this).attr('modal-id');
            let url = $(this).attr('modal-url');
            console.log(url);
            $('#url').attr('src', url);
            //location.reload();
            $(id).modal('show');
        });

        $('td[action-url]').addClass('pointer');
        $('td[action-url]').on('click', function() {
            let url = $(this).attr('action-url');
            window.location.href = url; // เปิดลิงก์ในแท็บใหม่
        });

    });

</script>

<script>
    const selectAllCheckbox = document.getElementById('selectAll');
    const userCheckboxes = document.querySelectorAll('.route-checkbox');
    const deleteBtn = document.getElementById('deleteBtn');
    const selectedCount = document.getElementById('selectedCount');
    const deleteForm = document.getElementById('deleteForm');

    // Select All functionality
    selectAllCheckbox.addEventListener('change', function() {
        userCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateDeleteButton();
    });

    // Individual checkbox change
    userCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectAllCheckbox();
            updateDeleteButton();
        });
    });

    function updateSelectAllCheckbox() {
        const totalCheckboxes = userCheckboxes.length;
        const checkedCheckboxes = document.querySelectorAll('.route-checkbox:checked').length;

        selectAllCheckbox.checked = totalCheckboxes === checkedCheckboxes && totalCheckboxes > 0;
        selectAllCheckbox.indeterminate = checkedCheckboxes > 0 && checkedCheckboxes < totalCheckboxes;
    }

    function updateDeleteButton() {
        const checkedCount = document.querySelectorAll('.route-checkbox:checked').length;
        selectedCount.textContent = checkedCount;
        deleteBtn.disabled = checkedCount === 0;
    }

    // Confirm before delete
    deleteForm.addEventListener('submit', function(e) {
        const checkedCount = document.querySelectorAll('.route-checkbox:checked').length;
        if (!confirm(`Are you sure you want to delete ${checkedCount} route(s)?`)) {
            e.preventDefault();
        }
    });

</script>

@stop

@section('modal')
<x-modal id="modal-iframe-time" title="">
    <iframe id="url" src="" width="100%" height="600" style="border: none;"></iframe>
</x-modal>
@stop
