<div class="prow">
    <table class="ptable w-100" style="width: 100%;">
        <tr class="bg-gray">
            <td colspan="8">
                <h3 style="margin-top:0px;margin-bottom: 0px;">PASSENGER NAME LIST</h3>
            </td>
        </tr>
        @foreach ($customers as $i => $customer)
        @if ($i%2 ==0)
        <tr>
            @endif

            <td class="text-end">{{ $i + 1 }}.</td>

            <td>{{ $customer['title'] }}.{{ ucfirst(Str::of($customer['fullname'])->limit(20)) }}</td>

            <td class="text-end">
                @if ($customer['type'] == 'ADULT')
                <div class="ico-adult"></div>
                @elseif ($customer['type'] == 'CHILD')
                <div class="ico-child"></div>
                @else
                <div class="ico-infant"></div>
                @endif

            </td>
            <td>{{ $customer['birth_day'] }}</td>
            @if ($i%2 ==1)
        </tr>
        @endif
        @endforeach
    </table>
</div>
