@if(Session::has('success'))
<script>
    window.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Success!'
            , text: @json(Session::get('success'))
            , icon: 'success'
            , confirmButtonText: 'OK'
            , customClass: {
                confirmButton: 'btn btn-primary'
            }
            , buttonsStyling: false
            , timer: 2000
            , showConfirmButton: false
        , });
    });

</script>
@endif


@if(Session::has('error'))
<script>
    Swal.fire({
        icon: "error"
        , title: 'Error!'
        , text: @json(Session::get('error'))
        , type: 'error'
        , customClass: {
            confirmButton: 'btn btn-primary'
        }
        , buttonsStyling: false
    })

</script>
@endif

@if(Session::has('warning'))
<div class="hide toast-on-load" data-toast-type="warning" data-toast-title="" data-toast-body="{{ Session::get('warning') }}" data-toast-pos="top-end" data-toast-delay="4000" data-toast-fill="true">
</div>
@endif
