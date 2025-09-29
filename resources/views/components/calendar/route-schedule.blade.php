@props([
'isshowtitle'=>true
])
<div class="text-center">
    @if ($isshowtitle)
    <h5>{{ $title }}</h5>
    @endif

    <table class="table table-bordered table-align-middle calendar-table">
        <thead class="">
            <tr class="text-end">
                <th>SUN</th>
                <th>MON</th>
                <th>TUE</th>
                <th>WED</th>
                <th>THU</th>
                <th>FRI</th>
                <th>SAT</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
            <tr>
                @foreach ($row as $col)
                <td class="text-end">
                    <div class="d-flex justify-content-between">
                        <div class="flex-grow-1 text-start" id="box-{{ $col['date'] }}">

                        </div>
                        <div class="text-nowrap">
                            <h6 class="@if ($col['current_month'] == 'N') text-secondary @endif" data-id="day-{{ $col['date'] }}">
                                {{ $col['day'] }}</h6>
                        </div>
                    </div>




                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
