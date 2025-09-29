@extends('layouts.default')

@section('content')
<x-card>
    <x-form>
        <div class="row">
            <div class="col-12">
                <x-form.float.input label="Title" name="title" />
            </div>
            <div class="col-12 col-lg-3">
                <x-form.float.input name="code" label="Code" />
            </div>
            <div class="col-8 col-lg-2">
                <x-form.float.input name="discount" label="Discount" />
            </div>
            <div class="col-4 col-lg-2">
                <x-form.float.selection label="Discount Type" />
            </div>
            <div class="col-4 col-lg-2">
                <x-form.float.input name="discount" label="Max Use" />
            </div>
            <div class="col-12 col-lg-3">
                <x-form.float.date-picker label="Effective Date" />
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-12">
                <h5>Condition</h5>
            </div>
            <div class="col-12 col-lg-4">
                <x-form.float.selection label="Trip Type" />
            </div>
            <div class="col-12 col-lg-8">
                <div class="form-check form-check-success">
                    <input class="form-check-input" type="checkbox" value="" id="customCheckSuccess" />
                    <label class="form-check-label" for="customCheckSuccess">Free credit charge</label>
                </div>
                <div class="form-check form-check-success">
                    <input class="form-check-input" type="checkbox" value="" id="customCheckSuccess" />
                    <label class="form-check-label" for="customCheckSuccess">Free Longtail Boat</label>
                </div>
                <div class="form-check form-check-success">
                    <input class="form-check-input" type="checkbox" value="" id="customCheckSuccess" />
                    <label class="form-check-label" for="customCheckSuccess">Free premium flex</label>
                </div>
                <div class="form-check form-check-success">
                    <input class="form-check-input" type="checkbox" value="" id="customCheckSuccess" />
                    <label class="form-check-label" for="customCheckSuccess">Free Shuttle Bus</label>
                </div>
                <div class="form-check form-check-success">
                    <input class="form-check-input" type="checkbox" value="" id="customCheckSuccess" />
                    <label class="form-check-label" for="customCheckSuccess">Free Private Taxi</label>
                </div>
            </div>
        </div>
    </x-form>

</x-card>

@stop
