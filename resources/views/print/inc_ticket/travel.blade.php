<div class="prow">
    <table class="w-100 ptable" style="padding-bottom: 10px;width: 100%;">

        <tr class="bg-gray font-w-700">
            <td class="w-25" style="">
                DATE OF TRAVELING
            </td>
            <td class="">
                FROM:

            </td>
            <td class="">
                DESTINATION TO:
            </td>
            <td class="text-center">
                DEPARTURE
            </td>
            <td class="text-center">
                ARRIVAL
            </td>
        </tr>



        <tr class="border-gray-top">
            <td class="">
                {{ date('l d M Y', strtotime($bookingRoute['pivot']['traveldate'])) }}
            </td>
            <td class="" style="white-space:wrap;">
                <span class="font-bold-14">{{ $bookingRoute['route']['departStation']['name_en'] }}</span>
                @if ($bookingRoute['route']['departStation']['piername'] != '')
                <br>({{ $bookingRoute['route']['departStation']['piername'] }})
                @endif
            </td>
            <td class="" style="white-space:wrap;">
                <span class="font-bold-14">{{ $bookingRoute['route']['destStation']['name_en'] }}</span>
                @if ($bookingRoute['route']['destStation']['piername'] != '')
                <br>({{ $bookingRoute['route']['destStation']['piername'] }})
                @endif
            </td>

            <td class="text-center">
                <span class="font-bold-14">{{ date('H:i', strtotime($bookingRoute['depart_time'])) }}</span>
            </td>
            <td class="text-center">
                <span class="font-bold-14">{{ date('H:i', strtotime($bookingRoute['arrive_time'])) }}</span>
            </td>
        </tr>
    </table>



</div>
