@extends('layouts.default')

@section('content')
<x-card>
<div class="row">
    <div class="col-12">
        <table class="table table-hover" id="myTable">
            <thead>
                <tr>

                    <th>Sort</th>
                    <th>Name en/th</th>
                    <th>Nickname</th>


                    <th>Pier</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($tag->stations as $station)
                <tr data-id="{{ $station->pivot->id }}">

                    <td>
                        <i class="drag-handle cursor-move icon-base ti tabler-menu-2 align-text-bottom me-2"></i>

                    </td>
                    <td>
                        {{ $station->name_en }} / {{ $station->name_th }}
                        <small>
                            <p>{{ $station->description }}</p>
                        </small>
                    </td>
                    <td>{{ $station->nickname }}</td>


                    <td>{{ $station->piername_en }}</td>
                </tr>


                @endforeach
            </tbody>
        </table>
    </div>
</div>
</x-card>

<form action="{{ route('tag.updateSort') }}" method="POST" id="frm-sort">
    @csrf
    <input type="hidden" name="tag_id" id="" value="{{ $tag->id }}">
</form>
@stop

@section('script')
<script src="{{ asset('assets/vendor/libs/sortablejs/sortable.js') }}"></script>


<script>
    const tbody = document.querySelector('#myTable tbody');

    new Sortable(tbody, {
        animation: 150
        , draggable: 'tr',

        onEnd: async function(evt) {
            showLoading();

            // 1️⃣ ดึงลำดับแถวใหม่ทั้งหมดหลังจาก drag
            const order = Array.from(tbody.querySelectorAll('tr')).map((row, index) => {
                return row.dataset.id;
            });

            console.log(order); // ตรวจสอบก่อนส่ง

            // 2️⃣ ส่งไปอัปเดตที่ backend (ตัวอย่างใช้ fetch)
            const form = document.getElementById('frm-sort');

            // ใส่ hidden input ตามจำนวน ids
            order.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'station_tag_ids[]';
                input.value = id;
                form.appendChild(input);
            });

            // submit form
            form.submit();
        }
    });

</script>


@stop
