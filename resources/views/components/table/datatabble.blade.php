@props([
'id'=>'datatable'
])
<div class="card-datatable pt-0">
    <table {{ $attributes->merge(['class' => 'table table-bordered table-hover']) }} id="{{ $id }}">
        {{ $slot }}
    </table>
</div>


@section('script')
@parent
<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            responsive: true
            , pageLength: 50
            , ordering: false
            , lengthMenu: [
                [10, 25, 50, 100, -1], // -1 คือ "All"
                [10, 25, 50, 100, "All"]
            ]
            , language: {
                search: "SEARCH:"
                , lengthMenu: "Show _MENU_ items"
                , info: "Show _START_ to _END_ from _TOTAL_ items"
                , paginate: {
                    first: "First"
                    , last: "หน้าสุดท้าย"
                    , next: "Next"
                    , previous: "ก่อนหน้า"
                }
            }
        });
    });

</script>
@stop
