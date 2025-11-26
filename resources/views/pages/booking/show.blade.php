@extends('layouts.default')

@section('title', 'Ferry Booking Details - ' . $booking['bookingno'])

@section('content')
<div class="row">
    <div class="col-12">

        <div class="row">
            {{-- Left Column --}}
            <div class="col-lg-8 order-2 order-md-1 d-none d-none d-lg-block">
                {{-- Journey Information --}}
                <div class="card shadow-sm mb-4 order-2 order-md-1">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-route me-2"></i>Journey Information
                        </h5>
                    </div>
                    <div class="card-body">
                        @foreach ($booking->bookingSubRoutes as $route)
                        <div class="row align-items-center">
                            <div class="col-md-5">
                                <div class="text-center p-3 bg-light rounded">
                                    <h6 class="text-primary mb-1">Departure</h6>
                                    <h4 class="mb-1">{{ $route['route']['departStation']['name_en'] }}</h4>
                                    <p class="text-muted mb-2">{{ $route['route']['departStation']['piername_en'] }}
                                    </p>
                                    <div class="bg-white rounded p-2">
                                        <strong class="text-success">{{ $route->depart_time->format('H:i') }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <x-label-date :date="$route->pivot->traveldate" />
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 text-center">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-ship text-primary fs-2 mb-2"></i>
                                    <small class="text-muted">
                                        <x-label-time-diff :fromTime="$route->depart_time->format('H:i')" :toTime="$route->arrival_time->format('H:i')" />
                                    </small>
                                    <div class="border-top border-primary w-100 mt-2"></div>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="text-center p-3 bg-light rounded">
                                    <h6 class="text-primary mb-1">Arrival</h6>
                                    <h4 class="mb-1">{{ $route['route']['destStation']['name_en'] }}</h4>
                                    <p class="text-muted mb-2">{{ $route['route']['destStation']['piername_en'] }}
                                    </p>
                                    <div class="bg-white rounded p-2">
                                        <strong class="text-danger">{{ $route->arrival_time->format('H:i') }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            <x-label-date :date="$route->pivot->traveldate" />
                                        </small>
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
                        @endforeach

                    </div>
                </div>

                {{-- Passenger Information --}}
                <div class="card shadow-sm mb-4 order-1 order-md-2">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-users me-2"></i>Passenger Information
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-4">
                                <div class="text-center p-3 border rounded">
                                    <i class="fas fa-user text-primary fs-3"></i>
                                    <h5 class="mt-2 mb-0">{{ $booking['adult_passenger'] }}</h5>
                                    <small class="text-muted">Adult</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-center p-3 border rounded">
                                    <i class="fas fa-child text-warning fs-3"></i>
                                    <h5 class="mt-2 mb-0">{{ $booking['child_passenger'] }}</h5>
                                    <small class="text-muted">Child</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-center p-3 border rounded">
                                    <i class="fas fa-baby text-info fs-3"></i>
                                    <h5 class="mt-2 mb-0">{{ $booking['infant_passenger'] }}</h5>
                                    <small class="text-muted">Infant</small>
                                </div>
                            </div>
                        </div>

                        {{-- Customer Details --}}
                        @foreach ($booking->bookingCustomers as $index => $customer)
                        <div class="border rounded p-3 mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="text-primary mb-0">
                                    <i class="fas fa-user-circle me-2"></i>Passenger {{ $index + 1 }}
                                    @if ($customer['pivot']['isdefault'] == 'Y')
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
            <div class="col-lg-4 order-1 order-md-2">
                {{-- Booking Summary --}}
                <div class="card shadow-sm mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-ticket">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M15 5l0 2" />
                                <path d="M15 11l0 2" />
                                <path d="M15 17l0 2" />
                                <path d="M5 5h14a2 2 0 0 1 2 2v3a2 2 0 0 0 0 4v3a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-3a2 2 0 0 0 0 -4v-3a2 2 0 0 1 2 -2" />
                            </svg>
                            Booking Summary
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row d-block d-sm-none">
                            <div class="col-12">
                                @foreach ($booking->bookingSubRoutes as $route)
                                <div class="row mb-3">
                                    <div class="col-12">
                                        <strong>Departure</strong>
                                    </div>
                                    <div class="col-4">
                                        <h4 class="mb-0 text-primary">{{ $route->depart_time->format('H:i') }}</h4>
                                        <x-label-date :date="$route->pivot->traveldate" />
                                    </div>
                                    <div class="col">
                                        <p class="mb-0">
                                            {{ $route['route']['departStation']['name_en'] }}</strong>
                                            <p class="text-muted mb-0">
                                                {{ $route['route']['departStation']['piername_en'] }}
                                            </p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <strong>Arrival</strong>
                                    </div>
                                    <div class="col-4">
                                        <h4 class="mb-0 text-primary">{{ $route->arrival_time->format('H:i') }}
                                        </h4>
                                        <x-label-date :date="$route->pivot->traveldate" />
                                    </div>
                                    <div class="col">
                                        <p class="mb-0">{{ $route['route']['destStation']['name_en'] }}</strong>
                                            <p class="text-muted mb-0">
                                                {{ $route['route']['destStation']['piername_en'] }}
                                            </p>
                                    </div>
                                </div>
                                @endforeach


                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Invoice No:</span>
                            <strong class="text-primary">{{ $booking['bookingno'] }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Ticket No:</span>
                            <strong class="text-success">
                                @foreach ($booking->bookingSubRoutes as $bookingSubRoute)
                                {{ $bookingSubRoute->pivot->ticketno }}
                                @endforeach
                            </strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Booking Channel:</span>
                            <span class="badge bg-info">{{ $booking['book_channel'] }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Booking Date:</span>
                            <span>{{ \Carbon\Carbon::parse($booking['created_at'])->format('d/m/Y H:i') }}</span>
                        </div>
                        @if ($booking['complete_date'])
                        <div class="d-flex justify-content-between mb-2">
                            <span>Payment Date:</span>
                            <span>{{ \Carbon\Carbon::parse($booking['complete_date'])->format('d/m/Y H:i') }}</span>
                        </div>
                        @endif
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>List Price:</span>
                            <span>฿{{ number_format($booking['totalamt'], 2) }}</span>
                        </div>

                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Nett Amount:</strong>
                            <strong class="text-danger fs-5">฿{{ number_format($booking['nettamt'], 2) }}</strong>
                        </div>

                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Payment Status:</span>
                                @if ($booking['ispayment'] == 'Y')
                                <span class="badge bg-success">Paid</span>
                                @else
                                <span class="badge bg-warning">Unpaid</span>
                                @endif
                            </div>
                        </div>
                        <hr>
                        <div class="row mb-1 mt-3 d-block d-sm-none">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="text-center p-3 border rounded">
                                            <i class="fas fa-user text-primary fs-3"></i>
                                            <h5 class="mt-2 mb-0">{{ $booking['adult_passenger'] }}</h5>
                                            <small class="text-muted">Adult</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-center p-3 border rounded">
                                            <i class="fas fa-child text-warning fs-3"></i>
                                            <h5 class="mt-2 mb-0">{{ $booking['child_passenger'] }}</h5>
                                            <small class="text-muted">Child</small>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-center p-3 border rounded">
                                            <i class="fas fa-baby text-info fs-3"></i>
                                            <h5 class="mt-2 mb-0">{{ $booking['infant_passenger'] }}</h5>
                                            <small class="text-muted">Infant</small>
                                        </div>
                                    </div>

                                    <div class="col-12 mt-3">
                                        @foreach ($booking->bookingCustomers as $index => $customer)
                                        <div class="border rounded p-3 mb-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="text-primary mb-0">
                                                    <i class="fas fa-user-circle me-2"></i>Passenger
                                                    {{ $index + 1 }}
                                                    @if ($customer['pivot']['isdefault'] == 'Y')
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
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-12">
                                <h5 class="mb-0">
                                    <i class="fas fa-building me-2"></i>Agent Information
                                </h5>
                            </div>
                            <div class="col-2">
                                <x-agent.profile :path="$booking['agent']['logo']" class="w-100" />
                            </div>
                            <div class="col">
                                <h6 class="text-primary mb-0">{{ $booking['agent']['name'] }}</h6>
                                <p class="text-muted">Code: {{ $booking['agent']['code'] }}</p>
                            </div>
                        </div>

                    </div>
                </div>



                {{-- Actions --}}
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <a href="{{ route('print.detail', ['bookingno' => $booking['bookingno']]) }}" class="btn btn-primary btn-lg w-100 mb-2" target="_blank">
                            <i class="fas fa-print me-2"></i>Print Detail
                        </a>
                        <button class="btn btn-outline-secondary w-100 mb-2">
                            <i class="fas fa-envelope me-2"></i>Send Email
                        </button>
                        @if ($booking['status'] == 'CO' && $booking['amend'] == 0)
                        <button class="btn btn-outline-warning w-100">
                            <i class="fas fa-edit me-2"></i>Edit Booking
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Additional Information --}}
        @if ($booking['note'])
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
