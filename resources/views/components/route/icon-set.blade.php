@props([
'icon_set'=>[]
])
<div class="row">
    <div class="col-12 d-flex align-items-center flex-wrap" id="box-appear-selected">
        @if (!empty($icon_set))
        @foreach ($icon_set as $icon)
        <div class="avatar avatar-xl me-4 position-relative">
            <img src="{{ asset('images/icon-route/ico-'.$icon.'.png') }}" alt="Avatar">
            <small></small>
            <span class="position-absolute top-0 start-50 translate-middle-x bg-danger text-white rounded px-2 py-0 remove" style="font-size: 0.75rem; cursor:pointer;">Remove</span>
            <input type="hidden" name="icon_set[]" value="{{ $icon }}" />
        </div>
        @endforeach
        @endif
        <div class="avatar me-2" data-bs-toggle="modal" data-bs-target="#modal">
            <span class="avatar-initial rounded-circle bg-label-secondary">
                <i class="icon-base ti tabler-circle-dashed-plus"></i>
            </span>
        </div>
    </div>
    <div class="col-12" id="box-input">

    </div>
</div>

@section('modal')
@parent
<x-modal>
    <div class="row">
        <div class="col-12">
            <h6>Please select icon</h6>
            <div class="row">
                @foreach ($ferryTypes as $index => $name)
                <div class="col-2 border text-center hover select-icon" data-value="{{ $index }}" style="cursor: pointer;">
                    <img src="{{ asset('images/icon-route/ico-'.$index.'.png') }}" alt="Avatar">

                    <small>{{ $name }}</small>
                </div>
                @endforeach
            </div>

        </div>
    </div>
    <hr>
</x-modal>
@stop

@section('script')
@parent
<script>
    $(document).ready(function() {
        $('.select-icon').on('click', function() {
            let index = $(this).attr('data-value');
            let $img = $(this).find('img').clone();
            // สร้างปุ่ม Remove
            let $removeBtn = $('<span class="position-absolute top-0 start-50 translate-middle-x bg-danger text-white rounded px-2 py-0" style="font-size: 0.75rem; cursor:pointer;">Remove</span>');

            let $inputField = $('<input type="hidden" name="icon_set[]" value="' + index + '" />');
            // คลิก Remove = ลบ parent
            $removeBtn.on('click', function(e) {
                e.stopPropagation(); // กัน event ลูป
                $(this).closest('.avatar').remove();
            });

            let $wrapper = $('<div class="avatar avatar-xl me-4 position-relative"></div>').append($img).append($inputField).append($removeBtn).append('<small>' + index + '</small>');
            $('#box-appear-selected .avatar').last().before($wrapper);
            $('#modal').modal('hide');

        });

        $('.remove').on('click', function(e) {
            e.stopPropagation(); // กัน event ลูป
            $(this).closest('.avatar').remove();
        });
    });

</script>
@stop
