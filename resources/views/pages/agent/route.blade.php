@extends('layouts.default')


@section('content')
<x-agent.header :agent="$agent" active="route" />



<x-card>
    <div class="row mb-3">
        <div class="col-12 text-end">
            <a href="{{ route('agentRoute.create',['agent'=>$agent->id]) }}" class="btn btn-primary"><i class="base-icon ti tabler-route"></i> Add routes to this Agent</a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <x-table.datatabble class="table-sm table-hover">
                <thead>
                    <tr>
                        <th>open Status</th>
                        <th>Route</th>
                        <th>Time</th>
                        <th>discount type</th>
                        <th>Regular discount </th>
                        <th>Child discount </th>
                        <th>Infant discount </th>
                        <th></th>

                    </tr>
                </thead>
                <tbody>
                    @php
                    $_route = '';
                    @endphp
                    @foreach ($agent->agentSubRoutes as $item)
                    <tr>
                        <td>
                            <label class="switch switch-success switch-square">
                                <input type="checkbox" class="switch-input" name="isactive" id="isactive" @checked($item->isactive=='Y' ) data-action="" />
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="icon-base ti tabler-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="icon-base ti tabler-x"></i>
                                    </span>
                                </span>

                            </label>
                        </td>
                        <td>

                            <x-station.label-name :station="$item->subRoute->route->departStation" /> <i class="base-icon ti tabler-circle-chevron-right text-primary"></i>
                            <x-station.label-name :station="$item->subRoute->route->destStation" />


                        </td>
                        <td>
                            <span class="text-nowrap">
                                <x-label-time :time="$item->subRoute->depart_time" />/
                                <x-label-time :time="$item->subRoute->arrival_time" /></span>

                        </td>
                        <td>
                            <select class="form-control form-control-sm" name="discount_type" id="discount_type">
                                <option value="percent" @selected($item->discount_type=='percent')>%</option>
                                <option value="amount" @selected($item->discount_type=='amount')>THB</option>
                            </select>
                        </td>
                        <td>
                            <small>
                                <x-label-price :price="$item->subRoute->price" /></small>
                            <input type="number" name="discount_regular_price" id="discount_regular_price" class="form-control form-control-sm" value="{{ $item->discount_regular_price }}">
                        </td>
                        <td>
                            <small>
                                <x-label-price :price="$item->subRoute->child_price" /></small>
                            <input type="number" name="discount_child_price" id="discount_child_price" class="form-control form-control-sm" value="{{ $item->discount_child_price }}">
                        </td>
                        <td>
                            <small>
                                <x-label-price :price="$item->subRoute->infant_price" /></small>
                            <input type="number" name="discount_infant_price" id="discount_infant_price" class="form-control form-control-sm" value="{{ $item->discount_infant_price }}">
                        </td>
                        <td class="text-end">
                            <input type="hidden" name="agent_sub_route_id" id="agent_sub_route_id" value="{{ $item->id }}">
                            <div class="d-flex align-items-center">
                                <button class="btn btn-success rounded-pill waves-effect btn-icon me-3 save-bt" disabled><i class="base-icon ti tabler-device-floppy"></i></button>

                                <x-button.delete :url="route('agentRoute.destroy',['agentRoute'=>$item->id])" />

                            </div>

                        </td>

                    </tr>
                    @php
                    $_route = $item->subRoute->route->id;
                    @endphp
                    @endforeach
                </tbody>
            </x-table.datatabble>
        </div>
    </div>
</x-card>

@stop


@section('script')
<script>
    let isFormDirty = false;

    $(document).ready(function() {

        $(document).on('input change', 'tr input, tr select', function() {
            const $row = $(this).closest('tr'); // หาแถว (row) ที่มี input เปลี่ยน
            const $saveBtn = $row.find('.save-bt'); // หา save button ในแถวเดียวกัน

            // Enable ปุ่ม ถ้ายัง disabled
            if ($saveBtn.prop('disabled')) {
                $saveBtn.prop('disabled', false);
                isFormDirty = true;
            }
        });


        $('.save-bt').on('click', function() {
            const $row = $(this).closest('tr');
            const $saveBtn = $(this);
            // ดึงข้อมูลจากแต่ละ input/select/checkbox
            const token = $('meta[name="csrf-token"]').attr('content');
            const isActive = $row.find('input[name="isactive"]').is(':checked') ? 1 : 0;
            const discountType = $row.find('select[name="discount_type"]').val();
            const regularPrice = $row.find('input[name="discount_regular_price"]').val();
            const childPrice = $row.find('input[name="discount_child_price"]').val();
            const infantPrice = $row.find('input[name="discount_infant_price"]').val();
            const subRouteId = $row.find('input[name="agent_sub_route_id"]').val();

            $.ajax({
                url: '/api/agent-route/save'
                , method: 'POST'
                , data: {
                    _token: token
                    , is_active: isActive
                    , discount_type: discountType
                    , discount_regular_price: regularPrice
                    , discount_child_price: childPrice
                    , discount_infant_price: infantPrice
                    , agent_sub_route_id: subRouteId
                }
                , success: function(res) {
                    console.log(res.message);
                    //alert('บันทึกสำเร็จ');
                    isFormDirty = false;
                    $saveBtn.prop('disabled', true);
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
