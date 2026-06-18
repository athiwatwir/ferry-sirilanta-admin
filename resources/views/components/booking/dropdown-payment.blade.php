@props([
    'booking' => [],
])

@php
    $bookingno = $booking['bookingno'] ?? '';
    $ispayment = $booking['ispayment'] ?? 'N';
    $ispaid = $booking['ispaid'] ?? null;
    $isPaid = $ispayment === 'Y' || $ispaid === 'Y';
    $paymentUrl = rtrim((string) env('PAYMENT_URL', ''), '/');
@endphp

@if (!$isPaid && $paymentUrl && $bookingno)
<li>
    <a class="dropdown-item" href="{{ $paymentUrl }}/payment/{{ $bookingno }}" target="_blank">
        <i class="icon-base ti tabler-credit-card-pay icon-22px"></i> Payment
    </a>
</li>
@endif
