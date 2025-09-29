@props([
'id'=>'modal','title'=>'','size'=>'modal-lg'
])
<!-- Modal -->
<div class="modal fade" id="{{ $id }}" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog {{ $size }}">
        <div class="modal-content">
            <div class="modal-header bg-body-secondary">
                <h5 class="modal-title mb-3" id="modal-title">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{ $slot }}
            </div>
        </div>

    </div>
</div>
