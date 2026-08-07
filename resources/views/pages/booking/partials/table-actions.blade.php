@php
$paymentBaseUrl = rtrim((string) env('PAYMENT_URL', ''), '/');
$isPaid = ($booking['ispayment'] ?? 'N') === 'Y' || ($booking['ispaid'] ?? null) === 'Y';
$showDelete = $showDelete ?? false;
@endphp

<div class="d-inline-flex align-items-center gap-1">
    <a href="{{ route('booking.show', ['booking' => $booking['id']]) }}" class="btn btn-sm btn-label-primary btn-action-icon" title="View">
        <i class="icon-base ti tabler-eye"></i>
    </a>
    <a href="{{ route('print.ticket', ['bookingno' => $booking['bookingno']]) }}" class="btn btn-sm btn-label-danger btn-action-icon" target="_blank" title="Print A4 Ticket">
        <i class="icon-base ti tabler-ticket"></i> A4
    </a>
    <a href="{{ route('print.detail', ['bookingno' => $booking['bookingno']]) }}" class="btn btn-sm btn-label-secondary btn-action-icon" target="_blank" title="Print Detail">
        <i class="icon-base ti tabler-file-type-pdf"></i> Ticket
    </a>
    @if (!$isPaid && $paymentBaseUrl && !empty($booking['bookingno']))
    <a href="{{ $paymentBaseUrl }}/payment/{{ $booking['bookingno'] }}" class="btn btn-sm btn-label-success btn-action-icon" target="_blank" title="Payment">
        <i class="icon-base ti tabler-credit-card-pay"></i> Payment
    </a>
    @endif
    @if ($showDelete)
    <form action="{{ route('booking.destroy', ['booking' => $booking['id']]) }}" method="POST" class="d-inline" onsubmit="return confirm('ยืนยันลบรายการนี้?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-label-danger btn-action-icon" title="Delete">
            <i class="icon-base ti tabler-trash"></i>
        </button>
    </form>
    @endif
</div>
