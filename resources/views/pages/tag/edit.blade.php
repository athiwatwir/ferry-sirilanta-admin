@extends('layouts.default')

@section('content')

<x-card>
    <div class="row">
        <div class="col col-lg-6 mx-auto">
            <x-form :action="route('tag.update',['tag'=>$tag])">
                @method('put')
                <input type="hidden" name="icon_1" id="icon_1" value="{{ $tag->icon_1 }}">
                <div class="row">
                    <div class="col-12">
                        <x-form.float.input label="Name" :value="$tag->name" :isreadonly="true" />
                    </div>
                    <div class="col-12">
                        <x-form.float.input label="Name TH" :isrequire="false" :value="$tag->name_th" :isreadonly="true" />
                    </div>
                    <div class="col-12">
                        <div class="row">
                            @foreach ($icons as $icon)
                            <div class="col-3 col-lg-2">
                                <img src="{{ $icon }}" data-icon="{{ $icon }}" alt="" class="w-100 partner-gray pointer @if ($icon ==$tag->icon_1 )
                                    active-icon
                                @endif">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </x-form>
        </div>
    </div>
</x-card>
@stop


@section('script')

<script>
    $(document).ready(function() {
        $('.partner-gray').on('click', function() {

            // เอาค่า data-icon ไปใส่ใน input hidden
            let icon = $(this).data('icon');
            $('#icon_1').val(icon);

            // ลบ class partner-gray ออกจากทุกตัวก่อน
            $('.partner-gray').removeClass('active-icon');

            // เพิ่ม class เฉพาะตัวที่ถูกคลิก
            $(this).addClass('active-icon');
        });
    });

</script>


@stop
