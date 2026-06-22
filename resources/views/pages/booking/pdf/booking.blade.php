<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Booking Report</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #333;
        }

        h1 {
            font-size: 14px;
            margin: 0 0 4px;
        }

        .meta {
            font-size: 9px;
            color: #666;
            margin-bottom: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 4px 5px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f3f4f6;
            font-weight: bold;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Booking Report</h1>
    <div class="meta">
        @if (!empty($daterange))
            {{ $date_type }}: {{ $daterange }}
        @else
            Booking Date: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}
        @endif
        &nbsp;|&nbsp; Total: {{ count($rows) }} records
        &nbsp;|&nbsp; Generated: {{ now()->format('d/m/Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                @foreach ($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse ($rows as $row)
                <tr>
                    @foreach ($row as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headers) }}">No data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
