<div class="prow">
    <table class="ptable w-100" style="width: 100%; margin-bottom: 20px;">
        <tr style="background-color: #f0f0f0;">
            <td colspan="7">
                <h3 style="margin-top:0px;margin-bottom: 0px;padding: 10px;">PASSENGER NAME LIST</h3>
            </td>
        </tr>
        @foreach ($customers as $i => $customer)
        @if ($i%2 ==0)
        <tr>
            @endif

            <td>{{ $i + 1 }}. {{ ucfirst(Str::of($customer['fullname'])->limit(20)) }}</td>

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

        @if ($booking['note'] != '')
        <tr>
            <td style="white-space:wrap;font-size: 9px;" colspan="7">
                <p style="margin-bottom: 0px;">Pickup/Dropoff Detail: {{ strip_tags($booking['note']) }}</p>
            </td>
        </tr>
        @endif
    </table>
</div>
