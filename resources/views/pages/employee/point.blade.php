@extends('layouts.default')

@section('content')
<x-card>
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0">คะแนนรวมของคุณ</h5>
                <span class="badge bg-primary fs-5 px-3 py-2">{{ number_format($totalPoint) }} Point</span>
            </div>
            <p class="text-muted small mb-0 mt-1">นับจากจำนวนผู้โดยสาร (ผู้ใหญ่ + เด็ก + ทารก) ในแต่ละการจอง</p>
        </div>
    </div>
    <hr>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Invoice No</th>
                <th>Travel Date</th>

                <th class="text-center">Point</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $index => $booking)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $booking->bookingno }}</td>
                <td>{{ $booking->departdate?->format('d/m/Y') }}</td>
                <td class="text-center fw-semibold">{{ $booking->point }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted py-4">ยังไม่มีรายการจอง</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</x-card>
@endsection
