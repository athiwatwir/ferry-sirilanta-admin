<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Withdraw Point Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #333; }
        h1 { font-size: 14px; margin: 0 0 4px; }
        .meta { font-size: 9px; color: #666; margin-bottom: 10px; }
        .summary { margin-bottom: 12px; }
        .summary td { padding: 3px 8px 3px 0; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data th, table.data td { border: 1px solid #ccc; padding: 4px 5px; }
        table.data th { background: #f3f4f6; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <h1>Withdraw Point Report — {{ $employee->name }}</h1>
    <div class="meta">
        Code: {{ $employee->code ?? '-' }}
        &nbsp;|&nbsp; {{ $date_type }}:
        @if (!empty($daterange))
            {{ $daterange }}
        @else
            {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}
        @endif
        &nbsp;|&nbsp; Generated: {{ now()->format('d/m/Y H:i') }}
    </div>

    <table class="summary">
        <tr>
            <td><strong>Total Point:</strong> {{ number_format($summary['total_point']) }}</td>
            <td><strong>Total Amount:</strong> {{ number_format($summary['total_amount'], 2) }}</td>
            <td><strong>Bookings:</strong> {{ number_format($summary['booking_count']) }}</td>
            <td><strong>Passengers:</strong> {{ number_format($summary['passenger_count']) }}</td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>Booking Date</th>
                <th>Travel Date</th>
                <th>Booking No</th>
                <th class="text-center">Type</th>
                <th class="text-center">Adult</th>
                <th class="text-center">Point</th>
                <th class="text-end">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($bookings as $booking)
                <tr>
                    <td>{{ $booking->created_at?->format('d/m/Y H:i') }}</td>
                    <td>{{ $booking->departdate?->format('d/m/Y') }}</td>
                    <td>{{ $booking->bookingno }}</td>
                    <td class="text-center">{{ ($tripTypes[$booking->trip_type] ?? null) ?: ($booking->trip_type ?: '-') }}</td>
                    <td class="text-center">{{ $booking->adult_passenger }}</td>
                    <td class="text-center">{{ $booking->point }}</td>
                    <td class="text-end">{{ number_format((float) $booking->totalamt, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No records</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
