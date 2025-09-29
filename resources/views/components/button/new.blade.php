@props(['text' => 'Create New','href'=>'javascript:void(0);'])

<div class="dt-buttons btn-group flex-wrap mb-2" bis_skin_checked="1">
    <a href="{{ $href }}" class="btn btn-lg btn-primary btn-hover" tabindex="0" aria-controls="DataTables_Table_0" type="button">
        <span><i class="icon-base  ti tabler-plus icon-16px me-md-2"></i>
            <span class="d-md-inline-block d-none">{{ $text }}
            </span>
        </span>
    </a>
</div>
