<div class="prow">

    <table class="w-100 ptable" style="margin-bottom: 5px;">
        <tr class="bg-gray font-w-700">
            <td>
                <h3 style="margin-top:0px;margin-bottom: 0px;">TRAVEL INFORMATION</h3>
            </td>
        </tr>
        <tr>

            <td style="white-space:wrap;font-size: 9px;">

                @if ($bookingRoute->master_from != '')
                <p style="margin-bottom: 0px;">&nbsp;&nbsp;&nbsp;&nbsp;{{ strip_tags($bookingRoute['master_from']) }}
                </p>
                @endif
                @if ($bookingRoute->information_from != '')
                <p style="margin-bottom: 0px;">&nbsp;&nbsp;&nbsp;&nbsp;{{ strip_tags($bookingRoute['information_from'])
                    }}</p>
                @endif
            </td>
        </tr>

        <tr class="border-gray-top">
            <td style="white-space:wrap;font-size: 9px;">

                @if ($bookingRoute->master_to != '')
                <p style="margin-bottom: 0px;">&nbsp;&nbsp;&nbsp;&nbsp;{{ strip_tags($bookingRoute['master_to']) }}</p>
                @endif
                @if ($bookingRoute->information_to != '')
                <p style="margin-bottom: 0px;">&nbsp;&nbsp;&nbsp;&nbsp;{{ strip_tags($bookingRoute['information_to']) }}
                </p>
                @endif
            </td>
        </tr>

    </table>

</div>