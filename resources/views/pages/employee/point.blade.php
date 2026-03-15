@extends('layouts.default')

@section('content')
<x-card>
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between">
                <h5 class="mb-0">คะแนนรวมที่ยังไม่ได้ถอน</h5>
                <span class="badge bg-primary fs-5 px-3 py-2">{{ number_format($totalPoint) }} Point</span>
            </div>

        </div>
    </div>
    <hr>

    {{-- รายการ Point ที่ยังไม่ได้ถอน --}}
    <h6 class="mb-3">รายการ Point ที่ยังไม่ได้ถอน</h6>
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
                <td colspan="4" class="text-center text-muted py-4">ยังไม่มีรายการ Point ที่ยังไม่ได้ถอน</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</x-card>

{{-- รายการ Transaction ที่ถอนไปแล้ว --}}
<x-card>
    <div class="row mb-4">
        <div class="col-12">
            <h6 class="mb-0">ประวัติการถอน Point</h6>
            <p class="text-muted small mb-0 mt-1">รายการ Point ที่ถอนไปแล้ว</p>
        </div>
    </div>
    <hr>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>วันที่ถอน</th>
                <th>รายละเอียด</th>
                <th class="text-center">Point</th>
                <th class="text-center">สถานะ</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $index => $transaction)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $transaction->created_at?->format('d/m/Y H:i') }}</td>
                <td>{{ $transaction->description ?? '-' }}</td>
                <td class="text-center fw-semibold">{{ number_format($transaction->amount) }}</td>
                <td class="text-center">
                    @if($transaction->isapproved == 'Y')
                    <span class="badge bg-success">อนุมัติแล้ว</span>
                    @else
                    <span class="badge bg-warning">รออนุมัติ</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center text-muted py-4">ยังไม่มีประวัติการถอน Point</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</x-card>
@endsection
