@props([
'isshowtitle'=>true,
'dailyTotals'=>[]
])
<div class="text-center">
    @if ($isshowtitle)
    <h5>{{ $title }}</h5>
    @endif

    <table class="table table-bordered table-align-middle calendar-table">
        <thead class="">
            <tr class="text-end">
                <th class="bg-primary text-white py-2">SUN</th>
                <th class="bg-primary text-white py-2">MON</th>
                <th class="bg-primary text-white py-2">TUE</th>
                <th class="bg-primary text-white py-2">WED</th>
                <th class="bg-primary text-white py-2">THU</th>
                <th class="bg-primary text-white py-2">FRI</th>
                <th class="bg-primary text-white py-2">SAT</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $row)
            <tr>
                @foreach ($row as $col)
                <td class="text-end pointer" data-action="modal" data-date="{{ $col['date'] }}">
                    <div class="d-flex justify-content-between">
                        <div class="flex-grow-1 text-start" id="box-{{ $col['date'] }}">
                            @if(isset($dailyTotals) && ($total = $dailyTotals[$col['date']] ?? null) !== null && $total != 0)
                            <span class="small text-primary">{{ number_format($total) }}</span>
                            @endif
                        </div>
                        <div class="text-nowrap">
                            <h5 class="@if ($col['current_month'] == 'N') text-secondary @endif" data-id="day-{{ $col['date'] }}">
                                {{ $col['day'] }}</h5>
                        </div>
                    </div>

                    <div class="align-items-center avatar-group" data-id="box-calendar-{{ $col['date'] }}">

                    </div>

                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
