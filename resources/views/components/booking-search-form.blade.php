@props(['method'=>'GET','action'=>''])
<form novalidate class="bs-validate" id="frm" method="{{ $method }}" action="{{ $action }}">
    <div class="row">

        <div class="col-12 col-md-4">
            <div class="form-floating mb-3">
                <x-station.selection name="depart_station_id" label="Station From" />

            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="form-floating mb-3">
                <x-station.selection name="dest_station_id" label="Station To" />
            </div>
        </div>
        <div class="col-12 col-md-2">
            <div class="form-floating mb-3">
                <select class="form-select" id="trip_type" aria-label="" name="trip_type">
                    <option value="" selected>-- All --</option>
                    @foreach ($tripTypes as $key => $title)
                    <option value="{{ $key }}" @selected($tripType==$key)>{{ $title }}</option>
                    @endforeach
                </select>
                <label for="trip_type">Trip Type</label>
            </div>
        </div>
        <div class="col-12 col-md-2">
            <div class="form-floating mb-3">
                <select class="form-select" id="book_channel" aria-label="" name="book_channel">
                    <option value="" selected>-- All --</option>
                    @foreach ($bookChannels as $key => $title)
                    <option value="{{ $key }}" @selected($bookChannel==$key)>{{ $title }}</option>
                    @endforeach
                </select>
                <label for="book_channel">Salse Channel</label>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="form-floating mb-3">
                <input autocomplete="off" type="text" name="daterange" id="daterange" class="form-control form-control-sm rangepicker" data-bs-placement="left" data-ranges="false" data-date-start="{{ $startDate }}" data-date-end="{{ $endDate }}" data-date-format="DD/MM/YYYY" data-quick-locale='{
                            "lang_apply"	: "Apply",
                            "lang_cancel" : "Cancel",
                            "lang_crange" : "Custom Range",
                            "lang_months"	 : ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                            "lang_weekdays" : ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"]
                        }'>
                <label for="departdate">Date</label>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="ticketno" name="ticketno" value="{{ $ticketno }}">
                <label for="ticketno">Ticket Number</label>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="bookingno" name="bookingno" value="{{ $bookingno }}">
                <label for="bookingno">Invoice Number</label>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="form-floating mb-3">
                <select class="form-select" id="status" aria-label="" name="status">
                    <option value="" selected>-- All --</option>
                    @foreach ($bookingStatus as $key => $status)
                    <option value="{{ $key }}">{{ $status['title'] }}</option>
                    @endforeach
                </select>
                <label for="status">Status</label>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="paymentno" name="paymentno" value="{{ $paymentno }}">
                <label for="paymentno">Payment Number</label>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="customername" name="customername" value="{{ $customername }}">
                <label for="customername">Customer</label>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="email" name="email" value="{{ $email }}">
                <label for="email">Email</label>
            </div>
        </div>
        <div class="col-12 text-end">
            <a class="btn btn-secondary" href="{{ route('booking.index') }}"><i class="fa-solid fa-arrows-rotate"></i> Clear</a>
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i>
                Search</button>
        </div>
    </div>
</form>
