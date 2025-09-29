<div>
    <label for="depart_station_id" class="form-label">Station From</label>
    <select id="depart_station_id" name="depart_station_id" class="form-select form-select-lg w-100" data-style="btn-default">
        <option value="">-- ALL --</option>
        @foreach ($departStations as $index => $stations)
        <optgroup label="{{ $index }}">
            @foreach ($stations as $station)
            <option value="{{ $station['id'] }}">
                <x-station.label-name :station="$station" />
            </option>
            @endforeach
        </optgroup>
        @endforeach
    </select>
</div>
