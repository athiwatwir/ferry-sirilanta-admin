<div class="prow">
    <table class="w-100 ptable" style="padding-bottom: 10px;width: 100%;">
        <tr>
            <td colspan="2">
                <h3 style="color: #0580c4;margin-top:0px;margin-bottom: 0px;">YOUR BOOKING DETAILS</h3>
            </td>
            <td colspan="2" style="text-align: right;">
                <h3 style="margin-top:0px;margin-bottom: 0px;">{{ $tripTypes[$booking['trip_type']] }}
                    ticket
                    @if ($booking['trip_type'] != 'one-way')
                    <span>{{$i+1}}/{{sizeof($bookingRoutes)}}</span>
                    @endif
                </h3>
            </td>
        </tr>
        <tr style="background-color: #f0f0f0;font-weight: 700;">
            <td class="" style="width: 25%;padding: 5px;">ISSUED DATE</td>
            <td class="" style="width: 25%;">INVOICE NO.</td>
            <td class="" style="width: 20%;">TICKET NO.</td>
            <td class="">{{ Str::upper('Number of Passenger:') }}
                {{($booking['adult_passenger']+$booking['child_passenger']+$booking['infant_passenger'])}}</td>
        </tr>
        <tr style="line-height: 1.5;">
            <td><small>{{ date('l d M Y H:i:s', strtotime($booking['updated_at'])) }}</small></td>
            <td>{{ $booking['bookingno'] }}</td>
            <td class="">

                {{ $bookingRoute['pivot']['ticketno'] }}

            </td>
            <td class="">
                Adult: {{$booking['adult_passenger']}} &nbsp;&nbsp;

            </td>
        </tr>
        <tr style="background-color: #f0f0f0;font-weight: 700;padding: 10px;">
            <td colspan="3" class="font-w-700" style="padding: 5px;">
                {{strtoupper('Contact Person')}}
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

                Email: {{ $firstCustomer['email'] }}<br>
                Telephone number: {{ $firstCustomer['mobile_code'].$firstCustomer['mobile'] }} <br>
                Optional Contact:<br> {{ $firstCustomer['other_contact'] }}
            </td>
            <td colspan="1">
                Total Amount: {{number_format($booking['totalamt']??0)}}THB<br>
                Payment Status: <span class="{{ $statusLabel[$booking['status']]['class']
                        }}">{{ $statusLabel[$booking['status']]['title']
                        }}</span><br>
                Method:{{ $booking['book_channel'] }}-{{
                    isset($booking['payment_method'])?$booking['payment_method']:'-' }}<br>

                @if(isset($salesPartner['name']))
                Transection No.: {{ ($booking['payment_method'] ?? '') === 'cash' ? 'Non-Refundable' : ($booking['referenceno'] ?? '-') }}
                <br>
                Approved by: {{ $user['name'] }}<br>
                @else
                Transection No.: {{ ($booking['payment_method'] ?? '') === 'cash' ? 'Non-Refundable' : ($booking['referenceno'] ?? '-') }}
                <br>
                Approved by: Online RSVN<br>
                @endif




            </td>

        </tr>


    </table>
</div>
