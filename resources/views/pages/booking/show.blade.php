@extends('layouts.default')

@section('title', 'Ferry Booking Details - ' . $booking['bookingno'])

@section('content')
<div class="row">
    <div class="col-12">
        {{-- Header Section --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="text-primary mb-1">
                    <i class="fas fa-ship me-2"></i>Booking Details
                </h2>
                <p class="text-muted mb-0">Booking No: <strong>{{ $booking['bookingno'] }}</strong></p>
            </div>
            <div class="text-end">
                @if($booking['status'] == 'CO')
                <span class="badge bg-success fs-6 px-3 py-2">
                    <i class="fas fa-check-circle me-1"></i>Confirmed
                </span>
                @elseif($booking['status'] == 'PD')
                <span class="badge bg-warning fs-6 px-3 py-2">
                    <i class="fas fa-clock me-1"></i>Pending Payment
                </span>
                @elseif($booking['status'] == 'CN')
                <span class="badge bg-danger fs-6 px-3 py-2">
                    <i class="fas fa-times-circle me-1"></i>Cancelled
                </span>
                @endif
            </div>
        </div>

        <div class="row">
            {{-- Left Column --}}
            <div class="col-lg-8">
                {{-- Journey Information --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-route me-2"></i>Journey Information
                        </h5>
                    </div>
                    <div class="card-body">
                        @php
                        $route = $booking['booking_sub_routes'][0];
                        $departDate = \Carbon\Carbon::parse($booking['departdate']);
                        $departTime = \Carbon\Carbon::parse($route['depart_time']);
                        $arrivalTime = \Carbon\Carbon::parse($route['arrival_time']);
                        @endphp

                        <div class="row align-items-center">
                            <div class="col-md-5">
                                <div class="text-center p-3 bg-light rounded">
                                    <h6 class="text-primary mb-1">Departure</h6>
                                    <h4 class="mb-1">{{ $route['route']['depart_station']['name_en'] }}</h4>
                                    <p class="text-muted mb-2">{{ $route['route']['depart_station']['piername_en'] }}</p>
                                    <div class="bg-white rounded p-2">
                                        <strong class="text-success">{{ $departTime->format('H:i') }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $departDate->format('d/m/Y') }}</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 text-center">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-ship text-primary fs-2 mb-2"></i>
                                    <small class="text-muted">
                                        <x-label-time-diff :fromTime="$departTime->format('H:i')" :toTime="$arrivalTime->format('H:i')" />
                                    </small>
                                    <div class="border-top border-primary w-100 mt-2"></div>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="text-center p-3 bg-light rounded">
                                    <h6 class="text-primary mb-1">Arrival</h6>
                                    <h4 class="mb-1">{{ $route['route']['dest_station']['name_en'] }}</h4>
                                    <p class="text-muted mb-2">{{ $route['route']['dest_station']['piername_en'] }}</p>
                                    <div class="bg-white rounded p-2">
                                        <strong class="text-danger">{{ $arrivalTime->format('H:i') }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $arrivalTime->format('d/m/Y') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Trip Type --}}
                        <div class="mt-4 p-3 bg-info bg-opacity-10 rounded">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Trip Type:</strong>
                                    <span class="badge bg-info ms-2">
                                        {{ $booking['trip_type'] == 'O' ? 'One Way' : 'Round Trip' }}
                                    </span>
                                </div>
                                <div class="col-md-6">
                                    <strong>Vessel Type:</strong>
                                    <span class="badge bg-secondary ms-2">{{ ucfirst($route['boat_type']) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Passenger Information --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-secondary">
                        <h5 class="mb-0">
                            <i class="fas fa-users me-2"></i>Passenger Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded">
                                    <i class="fas fa-user text-primary fs-3"></i>
                                    <h5 class="mt-2 mb-0">{{ $booking['adult_passenger'] }}</h5>
                                    <small class="text-muted">Adult</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded">
                                    <i class="fas fa-child text-warning fs-3"></i>
                                    <h5 class="mt-2 mb-0">{{ $booking['child_passenger'] }}</h5>
                                    <small class="text-muted">Child</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 border rounded">
                                    <i class="fas fa-baby text-info fs-3"></i>
                                    <h5 class="mt-2 mb-0">{{ $booking['infant_passenger'] }}</h5>
                                    <small class="text-muted">Infant</small>
                                </div>
                            </div>
                        </div>

                        {{-- Customer Details --}}
                        @foreach($booking['booking_customers'] as $index => $customer)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="text-primary mb-0">
                                    <i class="fas fa-user-circle me-2"></i>Passenger {{ $index + 1 }}
                                    @if($customer['pivot']['isdefault'] == 'Y')
                                    <span class="badge bg-primary ms-2">Primary Contact</span>
                                    @endif
                                </h6>
                                <span class="badge bg-outline-secondary">{{ $customer['type'] }}</span>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Full Name:</strong> {{ $customer['fullname'] }}<br>
                                    <strong>Passport No:</strong> {{ $customer['passportno'] }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Email:</strong> {{ $customer['email'] }}<br>
                                    <strong>Mobile:</strong> {{ $customer['mobile'] }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Right Column --}}
            <div class="col-lg-4">
                {{-- Booking Summary --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-secondary">
                        <h5 class="mb-0">
                            <i class="fas fa-receipt me-2"></i>Booking Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Booking No:</span>
                            <strong class="text-primary">{{ $booking['bookingno'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Ticket No:</span>
                            <strong class="text-success">{{ $booking['ticketno'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Booking Channel:</span>
                            <span class="badge bg-info">{{ $booking['book_channel'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Booking Date:</span>
                            <span>{{ \Carbon\Carbon::parse($booking['created_at'])->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($booking['complete_date'])
                        <div class="d-flex justify-content-between mb-2">
                            <span>Payment Date:</span>
                            <span>{{ \Carbon\Carbon::parse($booking['complete_date'])->format('d/m/Y H:i') }}</span>
                        </div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>฿{{ number_format($booking['subtotal'], 2) }}</span>
                        </div>
                        @if($booking['discount'] > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Discount:</span>
                            <span>-฿{{ number_format($booking['discount'], 2) }}</span>
                        </div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total Amount:</strong>
                            <strong class="text-danger fs-5">฿{{ number_format($booking['totalamt'], 2) }}</strong>
                        </div>

                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Payment Status:</span>
                                @if($booking['ispayment'] == 'Y')
                                <span class="badge bg-success">Paid</span>
                                @else
                                <span class="badge bg-warning">Unpaid</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Agent Information --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-secondary text-dark">
                        <h5 class="mb-0">
                            <i class="fas fa-building me-2"></i>Agent Information
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ asset($booking['agent']['logo']) }}" alt="{{ $booking['agent']['name'] }}" class="img-fluid mb-3" style="max-height: 80px;">
                        <h6 class="text-primary">{{ $booking['agent']['name'] }}</h6>
                        <p class="text-muted mb-2">Code: {{ $booking['agent']['code'] }}</p>
                        @if($booking['agent']['is_use_api'] == 'Y')
                        <span class="badge bg-success">API Integration</span>
                        @endif
                    </div>
                </div>

                {{-- Actions --}}
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <a href="{{ route('print.ticket',['bookingno'=>$booking['bookingno']]) }}" class="btn btn-primary btn-lg w-100 mb-2" target="_blank">
                            <i class="fas fa-print me-2"></i>Print Ticket
                        </a>
                        <button class="btn btn-outline-secondary w-100 mb-2">
                            <i class="fas fa-envelope me-2"></i>Send Email
                        </button>
                        @if($booking['status'] == 'CO' && $booking['amend'] == 0)
                        <button class="btn btn-outline-warning w-100">
                            <i class="fas fa-edit me-2"></i>Modify Booking
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Additional Information --}}
        @if($booking['note'])
        <div class="row mt-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-sticky-note me-2"></i>Notes
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $booking['note'] }}</p>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@push('styles')
<style>
    .card {
        border: none;
        border-radius: 10px;
    }

    .card-header {
        border-radius: 10px 10px 0 0 !important;
        border: none;
    }

    .badge {
        font-size: 0.8em;
    }

    .bg-light {
        background-color: #f8f9fa !important;
    }

    .border {
        border-color: #dee2e6 !important;
    }

    .text-primary {
        color: #0d6efd !important;
    }

    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1.1rem;
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 15px;
            padding-right: 15px;
        }

        .card-body {
            padding: 1rem;
        }
    }

</style>
@endpush
@endsection
