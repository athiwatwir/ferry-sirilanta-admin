<div class="prow">
    <table class="w-100 ptable" style="padding-bottom: 10px;width: 100%;">
        <tr>
            <td colspan="2">
                <h3 style="color: #0580c4;margin-top:0px;margin-bottom: 0px;">YOUR BOOKING DETAILS</h3>
            </td>
            <td colspan="2" class="text-end">
                <h3 style="margin-top:0px;margin-bottom: 0px;">{{
                        ucwords(str_replace('-','
                        ',$booking['trip_type'])) }}
                    ticket
                    @if ($booking['trip_type'] != 'one-way')
                    <span>{{$i+1}}/{{sizeof($bookingRoutes)}}</span>
                    @endif
                </h3>
            </td>
        </tr>
        <tr class="bg-gray font-w-700">
            <td class="" style="width: 25%;">ISSUED DATE</td>
            <td class="" style="width: 25%;">INVOICE NO.</td>
            <td class="" style="width: 20%;">TICKET NO.</td>
            <td class="">{{ Str::upper('Number of Passenger:') }}
                {{($booking['adult_passenger']+$booking['child_passenger']+$booking['infant_passenger'])}}</td>
        </tr>
        <tr>
            <td><small>{{ date('l d M Y', strtotime($booking['created_at'])) }}</small></td>
            <td>{{ $booking['bookingno'] }}</td>
            <td class="">

                {{ $booking['ticketno'] }}

            </td>
            <td class="">
                Adult: {{$booking['adult_passenger']}} &nbsp;&nbsp;
                Child: {{$booking['child_passenger']}} &nbsp;&nbsp;
                Infant: {{$booking['infant_passenger']}}
            </td>
        </tr>
        <tr class="bg-gray">
            <td colspan="3" class="font-w-700">
                {{strtoupper('Contact Information')}}
                @if ($index==0 && sizeof($customers) >1)
                <span class="text-main">[Lead passenger]</span>
                @endif
            </td>
            <td colspan="1" class="font-w-700">
                {{strtoupper('Payment Information')}}
            </td>

        </tr>
        <tr>
            <td colspan="3">
                Name: <span class="">{{ $firstCustomer['title'] }}.{{ ucfirst($firstCustomer['fullname'])
                        }}</span><br>
                Passport No.: {{ $firstCustomer['passportno'] }}<br>
                Nationality: {{ $firstCustomer['country'] }}<br>
                Email: {{ $firstCustomer['email'] }}<br>
                Tel: {{ $firstCustomer['mobile_code'].$firstCustomer['mobile'] }} / Thai tel: {{
                    $firstCustomer['mobile_th'] }}



            </td>
            <td colspan="1">
                Total Amount: {{number_format($booking['totalamt']??0)}}THB<br>
                Payment Status: <span class="{{ $statusLabel[$booking['status']]['class']
                        }}">{{ $statusLabel[$booking['status']]['title']
                        }}</span><br>
                Method:{{ $booking['book_channel'] }}-{{
                    isset($booking['payment_method'])?$booking['payment_method']:'-' }}<br>
                Transection No.: {{ $booking['referenceno'] }}
                <br>
                Approved by: @if(isset($user->firstname)) {{$user->firstname}} @else RSVN @endif<br>



            </td>

        </tr>


    </table>
</div>
