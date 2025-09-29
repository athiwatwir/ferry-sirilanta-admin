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
                <td class="text-end pointer" data-action="modal" data-date="{{ $col['date'] }}">
                    <div class="d-flex justify-content-between">
                        <div class="flex-grow-1 text-start" id="box-{{ $col['date'] }}">

                        </div>
                        <div class="text-nowrap">
                            <h5 class="@if ($col['current_month'] == 'N') text-secondary @endif" data-id="day-{{ $col['date'] }}">
                                {{ $col['day'] }}</h5>
                        </div>
                    </div>

                    <div class="align-items-center avatar-group" data-id="box-agent-avatar-{{ $col['date'] }}" style="display: none;">
                        <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                            @foreach ($agents as $item)
                            <li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="Agent: {{ $item->name }}" class="avatar pull-up avatar-xs" data-id="agent-avatar-{{ $item->id }}-{{ $col['date'] }}">
                                <img class="rounded-circle" src="{{ asset($item->logo) }}" alt="Avatar">
                            </li>
                            @endforeach


                        </ul>
                    </div>

                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
