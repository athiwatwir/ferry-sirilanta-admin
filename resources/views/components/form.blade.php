@props([
'action'=>'',
'method'=>'POST',
'id'=>'frm',
'type'=>'',
'backUrl'=>url()->previous(),
'isshow_button'=>true
])
<form id="{{ $id }}" class="mb-3 browser-default-validatio p-3" id="{{ $id }}" action="{{ $action }}" method="{{ $method }}" enctype="multipart/form-data">
    @csrf
    {{ $slot }}

    @if ($isshow_button)
    <hr>
    <div class="row">
        <div class="col text-center">
            <div class="">
                @if ($type=='modal')
                <button type="button" class="btn btn-label-secondary waves-effect me-2 btn-hover" data-bs-dismiss="modal">Close</button>
                @else
                <a href="{{ $backUrl }}" class="btn btn-label-secondary waves-effect me-2 btn-hover">Discard</a>
                @endif

                <button type="submit" class="btn btn-success waves-effect btn-hover"><i class="icon-base ti tabler-device-floppy"></i> Save</button>
            </div>
        </div>
    </div>
    @endif

</form>
