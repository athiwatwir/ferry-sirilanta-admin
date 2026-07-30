{{--
  Shared booking search + export UI (Agent / Broker / Employee show)

  Required:
  - $formId, $formAction, $clearUrl, $collapseId
  - $bookings, $tripTypes, $bookingStatus
  - $ticketno, $bookingno, $customername, $email, $tripType, $status, $searchText
  - $date_type, $depart_station_id, $dest_station_id
  Optional:
  - $tabValue (hidden tab=...)
  - $tablePartial (default: pages.booking.table.agent)
--}}
@php
    $tablePartial = $tablePartial ?? 'pages.booking.table.agent';
    $tabValue = $tabValue ?? null;
@endphp

<style>
    .booking-table td { padding: 5px; }
</style>

<form novalidate class="bs-validate" id="{{ $formId }}" method="GET" action="{{ $formAction }}">
    @if ($tabValue)
    <input type="hidden" name="tab" value="{{ $tabValue }}">
    @endif
    <input type="hidden" name="ispdf" id="{{ $formId }}-ispdf" value="N">
    <input type="hidden" name="export" id="{{ $formId }}-export" value="">

    <div class="row">
        <div class="col-12 text-end mb-2">
            <a class="" data-bs-toggle="collapse" href="#{{ $collapseId }}" role="button" aria-expanded="false" aria-controls="{{ $collapseId }}">
                <i class="icon-base ti tabler-selector icon-sm"></i> Search Form
            </a>
        </div>
    </div>

    <div class="row collapse" id="{{ $collapseId }}">
        <div class="col-12 col-md-4">
            <x-station.selection name="depart_station_id" label="Station From" :selected="$depart_station_id" :isrequire="false" />
        </div>
        <div class="col-12 col-md-4">
            <x-station.selection name="dest_station_id" label="Station To" :selected="$dest_station_id" :isrequire="false" />
        </div>
        <div class="col-12 col-md-4">
            <div class="form-floating mb-3">
                <select class="form-select" id="{{ $formId }}-trip_type" name="trip_type">
                    <option value="">-- All --</option>
                    @foreach ($tripTypes as $key => $_title)
                    <option value="{{ $key }}" @selected($tripType == $key)>{{ $_title }}</option>
                    @endforeach
                </select>
                <label for="{{ $formId }}-trip_type">Trip Type</label>
            </div>
        </div>

        <div class="col-12 col-md-3">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="{{ $formId }}-ticketno" name="ticketno" value="{{ $ticketno }}">
                <label for="{{ $formId }}-ticketno">Ticket Number</label>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="{{ $formId }}-bookingno" name="bookingno" value="{{ $bookingno }}">
                <label for="{{ $formId }}-bookingno">Invoice Number</label>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="form-floating mb-3">
                <select class="form-select" id="{{ $formId }}-status" name="status">
                    <option value="">-- All --</option>
                    @foreach ($bookingStatus as $key => $statusItem)
                    <option value="{{ $key }}" @selected($status == $key)>{{ $statusItem['title'] }}</option>
                    @endforeach
                </select>
                <label for="{{ $formId }}-status">Status</label>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="{{ $formId }}-customername" name="customername" value="{{ $customername }}">
                <label for="{{ $formId }}-customername">Customer</label>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="{{ $formId }}-email" name="email" value="{{ $email }}">
                <label for="{{ $formId }}-email">Email</label>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-3">
            <x-form.float.selection name="date_type" label="By Date" :options="['booking_date'=>'By Booking Date','travel_date'=>'By Travel Date']" :default="$date_type" />
        </div>
        <div class="col-12 col-md-4">
            <div class="form-floating mb-3">
                <input type="text" id="{{ $formId }}-daterange" name="daterange" class="form-control partner-booking-daterange" />
                <label for="{{ $formId }}-daterange" class="form-label">Date Range</label>
            </div>
        </div>
        <div class="col-12 col-md-5">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="{{ $formId }}-search_text" name="search_text" value="{{ $searchText }}">
                <label for="{{ $formId }}-search_text">Search Text</label>
            </div>
        </div>
        <div class="col-12 text-end mb-3">
            <a class="btn btn-secondary" href="{{ $clearUrl }}">
                <i class="fa-solid fa-arrows-rotate"></i> Clear
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-magnifying-glass"></i> Search
            </button>
        </div>
    </div>
</form>

<hr>
<div class="row mb-3">
    <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <span class="text-muted small">{{ count($bookings) }} รายการ</span>
        <div class="btn-group">
            <button type="button" class="btn btn-outline-success btn-sm partner-booking-export-excel" data-form="{{ $formId }}">
                <i class="icon-base ti tabler-file-spreadsheet me-1"></i> Export Excel
            </button>
            <button type="button" class="btn btn-outline-danger btn-sm partner-booking-export-pdf" data-form="{{ $formId }}">
                <i class="icon-base ti tabler-file-type-pdf me-1"></i> Export PDF
            </button>
        </div>
    </div>
</div>

@include($tablePartial)
