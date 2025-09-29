@extends('layouts.default')

@section('content')

<x-card>

    <div class="row">
        <div class="col-12 col-lg-4">
            <x-station.depart-selection label="From Station" :isrequire="false" />
        </div>
        <div class="col-12 col-lg-4">
            <x-station.depart-selection label="To Station" :isrequire="false" />
        </div>
        <div class="col-12 col-lg-4 text-end">
            <x-button.new text="Add Route" :href="route('route.create')" />
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Depart Time</th>
                        <th>Arrive Time</th>
                        <th>Type</th>
                        <th>Seat</th>
                        <th>Regular Price</th>

                        <th>Child Price</th>
                        <th>Infant Price</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($routes as $route)

                    <tr class="">
                        <td colspan="9">
                            <x-route.route-title-row :departStation="$route['departure_station']" :destStation="$route['destination_station']" />
                        </td>


                    </tr>

                    @foreach ($route['sub_routes'] as $subRoute)
                    <tr @if ($subRoute['type']=='activity' ) class="table-active" @endif>
                        <td>
                            <x-form.switch label="" :value="$subRoute['isactive']" />
                        </td>
                        <td>
                            <strong>
                                <x-label-time :time="$subRoute['depart_time']" /></strong>
                            <br><small>{{ $subRoute['origin_timezone'] }}</small>
                        </td>
                        <td>
                            <x-label-time :time="$subRoute['arrival_time']" />
                            <br><small>{{ $subRoute['destination_timezone'] }}</small>
                        </td>
                        <td class="">
                            @if ($subRoute['type']=='activity')
                            <span class="badge bg-label-info">Activity route</span><br>
                            @endif
                            {{ $subRoute['boat_type'] }} <br />

                            <div class="col-12 d-flex align-items-center flex-wrap">

                                @if (!empty($subRoute['icons']))
                                @foreach ($subRoute['icons'] as $icon)
                                <div class="avatar avatar-sm me-4 position-relative">
                                    <img src="{{ $icon }}" alt="Avatar">
                                    <small></small>

                                </div>
                                @endforeach
                                @endif
                            </div>
                        </td>
                        <td>
                            <x-label-number :number="$subRoute['seatamt']" />
                        </td>
                        <td class="">
                            <small>
                                <x-label-price :price="$subRoute['cost_price']" /></small>
                            <input type="number" name="price" id="" class="form-control" value="{{ $subRoute['price'] }}">
                        </td>

                        <td class="">
                            <small>
                                <x-label-price :price="$subRoute['cost_child_price']" /></small>
                            <input type="number" name="child_price" id="" class="form-control" value="{{ $subRoute['child_price'] }}">
                        </td>

                        <td class="">
                            <small>
                                <x-label-price :price="$subRoute['cost_infant_price']" /></small>
                            <input type="number" name="infant_price" id="" class="form-control" value="{{ $subRoute['infant_price'] }}">
                        </td>
                        <td class="text-end">
                            <input type="hidden" name="agent_sub_route_id" id="agent_sub_route_id" value="{{ $subRoute['agent_sub_route_id'] }}">
                            <div class="d-flex align-items-center">
                                <button class="btn btn-success rounded-pill waves-effect btn-icon me-3 save-bt" disabled><i class="base-icon ti tabler-device-floppy"></i></button>



                            </div>

                        </td>
                    </tr>
                    @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-card>

@stop


@section('script')

<script>
    let isFormDirty = false;

    $(document).ready(function() {


        $(document).on('change', 'tr input[type="number"]', function() {
            const $row = $(this).closest('tr'); // หาแถว (row) ที่มี input เปลี่ยน
            const $saveBtn = $row.find('.save-bt'); // หา save button ในแถวเดียวกัน

            // Enable ปุ่ม ถ้ายัง disabled
            if ($saveBtn.prop('disabled')) {
                $saveBtn.prop('disabled', false);
                isFormDirty = true;
            }
        });

        $(document).on('change', 'tr input.switch-input', function() {
            const $row = $(this).closest('tr');
            const token = $('meta[name="csrf-token"]').attr('content');

            const subRouteId = $row.find('input[name="agent_sub_route_id"]').val();
            let isactive = $row.find('input[name="isactive"]').val();
            isactive = isactive == 'Y' ? 'N' : 'Y';
            $row.find('input[name="isactive"]').val(isactive)

            console.log({
                isactive: isactive
                , agent_sub_route_id: subRouteId
            });


            $.ajax({
                url: '/api/agent-route/save'
                , method: 'POST'
                , data: {
                    _token: token
                    , isactive: isactive
                    , agent_sub_route_id: subRouteId
                }
                , success: function(res) {
                    //console.log(res);
                    showSuccess();
                }
                , error: function() {
                    alert('เกิดข้อผิดพลาด');
                }
            });


        });

        $('.save-bt').on('click', function() {
            const $row = $(this).closest('tr');
            const $saveBtn = $(this);
            // ดึงข้อมูลจากแต่ละ input/select/checkbox
            const token = $('meta[name="csrf-token"]').attr('content');

            const price = $row.find('input[name="price"]').val();
            const childPrice = $row.find('input[name="infant_price"]').val();
            const infantPrice = $row.find('input[name="infant_price"]').val();
            const subRouteId = $row.find('input[name="agent_sub_route_id"]').val();

            console.log({
                regular_price: price
                , child_price: childPrice
                , infant_price: infantPrice
                , agent_sub_route_id: subRouteId
            });

            $.ajax({
                url: '/api/agent-route/save'
                , method: 'POST'
                , data: {
                    _token: token
                    , price: price
                    , child_price: childPrice
                    , infant_price: infantPrice
                    , agent_sub_route_id: subRouteId
                }
                , success: function(res) {
                    console.log(res.message);
                    isFormDirty = false;
                    $saveBtn.prop('disabled', true);

                    showSuccess();
                }
                , error: function() {
                    alert('เกิดข้อผิดพลาด');
                }
            });
        });

    });

    // เตือนก่อนออกจากหน้า ถ้ามีการแก้ไข
    window.addEventListener('beforeunload', function(e) {
        if (isFormDirty) {
            e.preventDefault(); // บาง browser ต้องการ
            e.returnValue = ''; // สำคัญ: ต้องมีค่านี้เพื่อให้ browser แสดง prompt
            return ''; // เผื่อ browser เก่า
        }
    });

</script>
@stop
