<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <style>
        @font-face {
            font-family: 'Inconsolata';
            src: url('{{ public_path('fonts/Inconsolata/static/Inconsolata-Regular.ttf') }}') format('truetype');
        }

        @page {
            margin: 0;
        }

        body {
            margin: 10px;
            font-size: 12pt;
            line-height: 1.2;
            font-family: "Inconsolata", sans-serif;
        }

        strong {
            font-family: "Inconsolata", sans-serif;
        }

        .banner {
            width: 3.85in;
            height: 0.7in;
            display: block;
            margin: 0 auto 15px auto;
        }

        .text-center {
            text-align: center;
        }

        .bold {
            font-family: "Inconsolata", sans-serif;
            font-weight: bold;
        }

        .mt-2 {
            margin-top: 10px;
        }

        .mt-3 {
            margin-top: 15px;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 10px 0;
        }

        .title {
            font-family: "Inconsolata", sans-serif;
            font-size: 18pt;
            font-weight: bold;
            text-align: center;
            margin-top: 5px;
        }

        .warning {
            color: red;
            text-align: center;
            font-weight: bold;
            border-bottom: 1px dashed red;
            margin-bottom: 20px;
            margin-top: 20px;
        }

        .content {
            padding: 0 10px;
        }

        .section {
            margin-bottom: 8px;
            padding-bottom: 8px;
        }

        .page-break {
            page-break-before: always;
        }

        .thai {
            font-family: "Courier", sans-serif;
            font-size: 16pt;
        }

    </style>
</head>
<body>

    @foreach ($booking->bookingSubRoutes as $bookingSubRoute)
    <img class="banner" src="{{ $bannerBase64 }}" alt="Banner">


    <div class="mt-2">
        Ticket No.:{{ $bookingSubRoute->pivot->ticketno }}<br>
        Boarding for: {{ $booking->adult_passenger+$booking->child_passenger+$booking->infant_passenger }} Passenger(S)
    </div>

    <div class="line"></div>

    <div class="content">

        <div class="section" style="width: 100%;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td colspan="2">Departure from:<br> {{ $bookingSubRoute->route->departStation->name_en ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="text-align: left;">
                        Date: {{ \Carbon\Carbon::parse($bookingSubRoute->traveldate)->format('d/m/Y') }}
                    </td>
                    <td style="text-align: right; white-space: nowrap;">
                        Time: {{ $bookingSubRoute->depart_time->format('H:i') ?? '-' }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="section" style="width: 100%;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td colspan="2">Destination to:<br> {{ $bookingSubRoute->route->destStation->name_en ?? '-' }}</td>
                </tr>
                <tr>
                    <td style="text-align: left;">
                        Date: {{ \Carbon\Carbon::parse($bookingSubRoute->traveldate)->format('d/m/Y') }}
                    </td>
                    <td style="text-align: right; white-space: nowrap;">
                        Time: {{ $bookingSubRoute->arrival_time->format('H:i') ?? '-' }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="line"></div>
        <div class="section">
            Passenger Name:<br><span class="thai">{{ $booking->defaultCustomer[0]->fullname ?? '-' }}</span><br>
            Phone No. {{ $booking->defaultCustomer[0]->mobile ?? '-' }}
        </div>
        <div class="line"></div>
        <div class="section">
            Branch Office:<br>
            Issue date: {{ \Carbon\Carbon::parse($booking->complete_date)->format('d/m/Y H:i') }}
        </div>
    </div>
    <div class="line"></div>
    <div class="" style="font-size: 14pt;font-weight: bold;text-align: center;">PAID {{ number_format($booking->totalamt ?? 0, 2) }} BAHT</div>
    <div class="" style="font-size: 14pt;font-weight: bold;text-align: center;">Non-Refundable Ticket</div>

    @if (!$loop->last)
    <div class="page-break"></div>
    @endif
    @endforeach


</body>
</html>
