<div>
    <label for="dest_station_id" class="form-label">Station To</label>
    <select id="dest_station_id" name="dest_station_id" class="form-select form-select-lg w-100" data-style="btn-default">
        <option value="">-- ALL --</option>
        @foreach ($destStations as $index => $stations)
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
