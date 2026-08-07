@extends('layouts.default')

@section('content')
@php
$tripLabel = $tripTypes[$booking->trip_type] ?? ($booking->trip_type ?: '-');
$statusMeta = $bookingStatus[$booking->status] ?? ['title' => $booking->status ?: '-', 'class' => 'text-secondary'];
$isPaid = ($booking->ispayment ?? 'N') === 'Y';
$paymentBaseUrl = rtrim((string) env('PAYMENT_URL', ''), '/');
$legCount = $booking->bookingSubRoutes->count();
$passengerCount = (int) $booking->adult_passenger;
$latestPayment = $booking->payments->first();
$paymentFee = (float) ($latestPayment->feeamt ?? 0);
$partnerFee = (float) ($latestPayment->p_feeamt ?? 0);
$systemFee = (float) ($latestPayment->s_feeamt ?? 0);
$totalFee = $paymentFee > 0 ? $paymentFee : ($partnerFee + $systemFee);
@endphp

{{-- Header --}}
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                            <h4 class="mb-0">{{ $booking->bookingno }}</h4>
                            <span class="badge {{ $isPaid ? 'bg-label-success' : 'bg-label-warning' }}">
                                {{ $isPaid ? 'Paid' : 'Unpaid' }}
                            </span>
                            <span class="badge bg-label-secondary {{ $statusMeta['class'] ?? '' }}">
                                {{ $statusMeta['title'] }}
                            </span>
                            <span class="badge bg-label-info">{{ $tripLabel }}@if (($booking->trip_type ?? '') === 'M') ({{ $legCount }})@endif</span>
                        </div>
                        <p class="mb-0 text-muted small">
                            Booked on {{ $booking->created_at?->format('d/m/Y H:i') ?? '-' }}
                            · Travel date {{ $booking->departdate?->format('d/m/Y') ?? '-' }}
                            · Channel {{ $booking->book_channel ?: '-' }}
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        @if ($booking->status === 'CO')
                        <a href="{{ route('print.ticket', ['bookingno' => $booking->bookingno]) }}" class="btn btn-label-danger" target="_blank">
                            <i class="icon-base ti tabler-ticket me-1"></i> Print A4
                        </a>
                        <a href="{{ route('print.detail', ['bookingno' => $booking->bookingno]) }}" class="btn btn-label-secondary" target="_blank">
                            <i class="icon-base ti tabler-printer me-1"></i> Print Ticket
                        </a>
                        @endif
                        @if (!$isPaid && $paymentBaseUrl)
                        <a href="{{ $paymentBaseUrl }}/payment/{{ $booking->bookingno }}" class="btn btn-success" target="_blank">
                            <i class="icon-base ti tabler-credit-card-pay me-1"></i> Payment
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Main --}}
    <div class="col-12 col-xl-8">
        {{-- Journey --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="icon-base ti tabler-route icon-sm"></i>
                        </span>
                    </span>
                    <div>
                        <h5 class="mb-0">Journey</h5>
                        <small class="text-muted">{{ $legCount }} leg{{ $legCount === 1 ? '' : 's' }} · {{ $tripLabel }}</small>
                    </div>
                </div>
                <hr class="mt-0">

                @forelse ($booking->bookingSubRoutes as $index => $route)
                @php
                $departStation = $route->route?->departStation;
                $destStation = $route->route?->destStation;
                $adultPrice = (float) ($route->pivot->price ?? 0);
                $childPrice = (float) ($route->pivot->child_price ?? 0);
                $infantPrice = (float) ($route->pivot->infant_price ?? 0);
                $legTotal = ($adultPrice * (int) $booking->adult_passenger)
                + ($childPrice * (int) $booking->child_passenger)
                + ($infantPrice * (int) $booking->infant_passenger);
                @endphp
                <div class="@if (!$loop->last) mb-4 pb-4 border-bottom @endif">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="badge bg-label-primary">Leg {{ $index + 1 }}/{{ $legCount }}</span>
                            @if ($route->pivot->ticketno)
                            <span class="badge bg-label-success">Ticket {{ $route->pivot->ticketno }}</span>
                            @endif
                            @if ($route->boat_type)
                            <span class="badge bg-label-secondary">{{ ucfirst($route->boat_type) }}</span>
                            @endif
                            @if (($route->pivot->ischange ?? 'N') === 'Y')
                            <span class="badge bg-label-warning">Changed</span>
                            @endif
                            @if ($route->pivot->type)
                            <span class="badge bg-label-info">{{ strtoupper($route->pivot->type) }}</span>
                            @endif
                        </div>
                        <small class="text-muted">
                            <x-label-date :date="$route->pivot->traveldate" />
                        </small>
                    </div>

                    <div class="row align-items-center g-3">
                        <div class="col-md-5">
                            <div class="p-3 rounded bg-label-primary h-100">
                                <small class="text-primary fw-semibold">Departure</small>
                                <h5 class="mb-1 mt-1">{{ $departStation?->name_en ?: ($departStation?->name ?? '-') }}</h5>
                                <p class="mb-2 small text-muted">{{ $departStation?->piername_en ?: ($departStation?->piername ?? '') }}</p>
                                <div class="d-flex align-items-baseline gap-2">
                                    <strong class="fs-4 text-success">{{ optional($route->depart_time)->format('H:i') ?? '-' }}</strong>
                                    <small class="text-muted">
                                        <x-label-date :date="$route->pivot->traveldate" /></small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 text-center">
                            <i class="icon-base ti tabler-ship fs-2 text-primary"></i>
                            <div class="small text-muted mt-1">
                                @if ($route->depart_time && $route->arrival_time)
                                <x-label-time-diff :fromTime="$route->depart_time->format('H:i')" :toTime="$route->arrival_time->format('H:i')" />
                                @else
                                -
                                @endif
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="p-3 rounded bg-label-danger h-100">
                                <small class="text-danger fw-semibold">Arrival</small>
                                <h5 class="mb-1 mt-1">{{ $destStation?->name_en ?: ($destStation?->name ?? '-') }}</h5>
                                <p class="mb-2 small text-muted">{{ $destStation?->piername_en ?: ($destStation?->piername ?? '') }}</p>
                                <div class="d-flex align-items-baseline gap-2">
                                    <strong class="fs-4 text-danger">{{ optional($route->arrival_time)->format('H:i') ?? '-' }}</strong>
                                    <small class="text-muted">
                                        <x-label-date :date="$route->pivot->traveldate" /></small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2 mt-3 small">
                        <div class="col-6 col-md-3">
                            <span class="text-muted">Adult price</span>
                            <div class="fw-semibold">
                                <x-label-price :price="$adultPrice" />
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <span class="text-muted">Child price</span>
                            <div class="fw-semibold">
                                <x-label-price :price="$childPrice" />
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <span class="text-muted">Infant price</span>
                            <div class="fw-semibold">
                                <x-label-price :price="$infantPrice" />
                            </div>
                        </div>
                        <div class="col-6 col-md-3">
                            <span class="text-muted">Leg total</span>
                            <div class="fw-semibold text-primary">
                                <x-label-price :price="$legTotal" />
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-muted mb-0">No journey data</p>
                @endforelse
            </div>
        </div>

        {{-- Passengers --}}
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="icon-base ti tabler-users icon-sm"></i>
                        </span>
                    </span>
                    <div>
                        <h5 class="mb-0">Passengers</h5>
                        <small class="text-muted">{{ $passengerCount }} passenger{{ $passengerCount === 1 ? '' : 's' }}</small>
                    </div>
                </div>
                <hr class="mt-0">

                @forelse ($booking->bookingCustomers as $index => $customer)
                <div class="border rounded p-3 @if (!$loop->last) mb-3 @endif">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                        <h6 class="mb-0">
                            <i class="icon-base ti tabler-user-circle me-1"></i>
                            Passenger {{ $index + 1 }}
                            @if (($customer->pivot->isdefault ?? '') === 'Y')
                            <span class="badge bg-primary ms-1">Lead / Contact</span>
                            @endif
                        </h6>
                        <span class="badge bg-label-secondary">{{ $customer->type ?: '-' }}</span>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Full name</small>
                            <strong>{{ trim(($customer->title ? $customer->title . '. ' : '') . ($customer->fullname ?? '')) ?: '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Passport / ID</small>
                            <strong>{{ $customer->passportno ?: '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Email</small>
                            <strong>{{ $customer->email ?: '-' }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block">Mobile</small>
                            <strong>{{ trim(($customer->mobile_code ?? '') . ($customer->mobile ?? '')) ?: '-' }}</strong>
                        </div>
                        @if ($customer->country)
                        <div class="col-md-6">
                            <small class="text-muted d-block">Country</small>
                            <strong>{{ $customer->country }}</strong>
                        </div>
                        @endif
                        @if ($customer->birth_day)
                        <div class="col-md-6">
                            <small class="text-muted d-block">Birth day</small>
                            <strong>{{ $customer->birth_day?->format('d/m/Y') }}</strong>
                        </div>
                        @endif
                        @if ($customer->fulladdress)
                        <div class="col-12">
                            <small class="text-muted d-block">Address</small>
                            <strong>{{ $customer->fulladdress }}</strong>
                        </div>
                        @endif
                        @if (!empty($customer->other_contact))
                        <div class="col-12">
                            <small class="text-muted d-block">Optional contact</small>
                            <strong>{{ $customer->other_contact }}</strong>
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <p class="text-muted mb-0">No passenger details</p>
                @endforelse
            </div>
        </div>

        {{-- Payments history --}}
        @if ($booking->payments->isNotEmpty())
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="icon-base ti tabler-receipt icon-sm"></i>
                        </span>
                    </span>
                    <div>
                        <h5 class="mb-0">Payment History</h5>
                        <small class="text-muted">{{ $booking->payments->count() }} record{{ $booking->payments->count() === 1 ? '' : 's' }}</small>
                    </div>
                </div>
                <hr class="mt-0">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Payment No</th>
                                <th>Method</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-end">Fee</th>
                                <th class="text-end">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($booking->payments as $payment)
                            @php
                            $rowFee = (float) ($payment->feeamt ?? 0);
                            if ($rowFee <= 0) { $rowFee=(float) ($payment->p_feeamt ?? 0) + (float) ($payment->s_feeamt ?? 0);
                                }
                                @endphp
                                <tr>
                                    <td>{{ $payment->paymentno ?: '-' }}</td>
                                    <td>{{ $payment->payment_method ?: '-' }}</td>
                                    <td>
                                        @if (($payment->ispaid ?? '') === 'Y' || ($payment->status ?? '') === 'CO')
                                        <span class="badge bg-label-success">Paid</span>
                                        @else
                                        <span class="badge bg-label-secondary">{{ $payment->status ?: '-' }}</span>
                                        @endif
                                    </td>
                                    <td>{{ optional($payment->payment_date ?? $payment->created_at)->format('d/m/Y H:i') ?? '-' }}</td>
                                    <td class="text-end">
                                        <x-label-price :price="$rowFee" />
                                    </td>
                                    <td class="text-end">
                                        <x-label-price :price="$payment->totalamt ?? $payment->amount ?? 0" />
                                    </td>
                                </tr>
                                @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

        @if ($booking->note || $booking->reason)
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-warning">
                            <i class="icon-base ti tabler-notes icon-sm"></i>
                        </span>
                    </span>
                    <div>
                        <h5 class="mb-0">Notes</h5>
                    </div>
                </div>
                <hr class="mt-0">
                @if ($booking->note)
                <div class="mb-3">
                    <small class="text-muted d-block">Note</small>
                    <p class="mb-0">{{ $booking->note }}</p>
                </div>
                @endif
                @if ($booking->reason)
                <div>
                    <small class="text-muted d-block">Reason</small>
                    <p class="mb-0">{{ $booking->reason }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- Sidebar --}}
    <div class="col-12 col-xl-4">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="icon-base ti tabler-cash icon-sm"></i>
                        </span>
                    </span>
                    <div>
                        <h5 class="mb-0">Payment Summary</h5>
                    </div>
                </div>
                <hr class="mt-0">

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Subtotal</span>
                    <span>
                        <x-label-price :price="$booking->subtotal ?? 0" /></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Discount</span>
                    <span>
                        <x-label-price :price="$booking->discount ?? 0" /></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">List price</span>
                    <span>
                        <x-label-price :price="$booking->totalamt ?? 0" /></span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Payment fee</span>
                    <span>
                        <x-label-price :price="$totalFee" /></span>
                </div>
                @if ($partnerFee > 0 || $systemFee > 0)
                <div class="d-flex justify-content-between mb-1 small">
                    <span class="text-muted ps-2">Partner fee</span>
                    <span>
                        <x-label-price :price="$partnerFee" /></span>
                </div>
                <div class="d-flex justify-content-between mb-2 small">
                    <span class="text-muted ps-2">System fee</span>
                    <span>
                        <x-label-price :price="$systemFee" /></span>
                </div>
                @endif
                <hr>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <strong>Nett amount</strong>
                    <strong class="fs-4 text-danger">
                        <x-label-price :price="$booking->nettamt ?? $booking->totalamt ?? 0" /></strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Payment status</span>
                    <span class="badge {{ $isPaid ? 'bg-success' : 'bg-warning text-dark' }}">{{ $isPaid ? 'Paid' : 'Unpaid' }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Method</span>
                    <strong>{{ $booking->payment_method ?: '-' }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Channel</span>
                    <strong>{{ $booking->book_channel ?: '-' }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Transaction No.</span>
                    <strong class="text-end">
                        {{ ($booking->payment_method ?? '') === 'cash' ? 'Non-Refundable' : ($booking->referenceno ?: '-') }}
                    </strong>
                </div>
                @if ($booking->complete_date)
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Paid at</span>
                    <strong>{{ $booking->complete_date->format('d/m/Y H:i') }}</strong>
                </div>
                @endif
                @if ($booking->cancel_date)
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Cancelled at</span>
                    <strong class="text-danger">{{ $booking->cancel_date->format('d/m/Y H:i') }}</strong>
                </div>
                @endif
                @if ($booking->expires_at)
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Expires</span>
                    <strong>{{ \Carbon\Carbon::parse($booking->expires_at)->format('d/m/Y H:i') }}</strong>
                </div>
                @endif
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="icon-base ti tabler-info-circle icon-sm"></i>
                        </span>
                    </span>
                    <div>
                        <h5 class="mb-0">Booking Details</h5>
                    </div>
                </div>
                <hr class="mt-0">

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Invoice No.</span>
                    <strong>{{ $booking->bookingno }}</strong>
                </div>
                <div class="mb-2">
                    <span class="text-muted d-block mb-1">Ticket No.</span>
                    <div class="d-flex flex-wrap gap-1 justify-content-end">
                        @forelse ($booking->bookingSubRoutes as $leg)
                        <span class="badge bg-label-success">{{ $leg->pivot->ticketno ?: '-' }}</span>
                        @empty
                        <strong>-</strong>
                        @endforelse
                    </div>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Passengers</span>
                    <strong>{{ $passengerCount }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Trip type</span>
                    <strong>{{ $tripLabel }}@if (($booking->trip_type ?? '') === 'M') ({{ $legCount }})@endif</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Booking status</span>
                    <strong class="{{ $statusMeta['class'] ?? '' }}">{{ $statusMeta['title'] }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Premium flex</span>
                    <strong>{{ ($booking->ispremiumflex ?? 'N') === 'Y' ? 'Yes' : 'No' }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Point earned</span>
                    <strong>{{ ($booking->isearned ?? 'N') === 'Y' ? 'Yes' : 'No' }}</strong>
                </div>
                @if ($booking->amend)
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Amend count</span>
                    <strong>{{ $booking->amend }}</strong>
                </div>
                @endif
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Updated</span>
                    <strong>{{ $booking->updated_at?->format('d/m/Y H:i') ?? '-' }}</strong>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <span class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-secondary">
                            <i class="icon-base ti tabler-building-store icon-sm"></i>
                        </span>
                    </span>
                    <div>
                        <h5 class="mb-0">Agent / Partner</h5>
                    </div>
                </div>
                <hr class="mt-0">

                @if ($booking->agent)
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div style="width: 48px;">
                        <x-agent.profile :path="$booking->agent->logo" class="w-100" />
                    </div>
                    <div>
                        <strong class="d-block">{{ $booking->agent->name }}</strong>
                        <small class="text-muted">Code: {{ $booking->agent->code ?: '-' }}</small>
                    </div>
                </div>
                @else
                <p class="text-muted mb-3">No agent data</p>
                @endif

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Sales partner</span>
                    <strong class="text-end">{{ $booking->salesPartner?->name ?: '-' }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Partner type</span>
                    <strong>{{ $booking->salesPartner?->type ? ucfirst($booking->salesPartner->type) : '-' }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Booked by</span>
                    <strong class="text-end">{{ $booking->user?->name ?: 'Online RSVN' }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
