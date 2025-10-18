@props([
'editUrl'=>'',
'deleteUrl'=>''
])
<div class="btn-group">
    <button type="button" class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow text-white" data-bs-toggle="dropdown">
        <i class="icon-base ti tabler-dots-vertical"></i>
    </button>
    <ul class="dropdown-menu">
        @if (!empty($editUrl))
        <li><a class="dropdown-item" href="{{ $editUrl }}"><i class="icon-base ti tabler-edit icon-22px"></i> Edit</a></li>
        @endif

        {{ $slot }}
        @if (!empty($deleteUrl))
        <li>
            <hr class="dropdown-divider">
        </li>
        <li class="">
            <a href="javascript:void(0);" class="dropdown-item text-danger delete-button d-flex align-items-center" data-action="{{ $deleteUrl }}"><i class="icon-base ti tabler-trash icon-22px"></i> Delete</a>
        </li>
        @endif
    </ul>
</div>
