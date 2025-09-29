@extends('layouts.default')

@section('content')
<x-card>
    <div class="row">
        <div class="col-12 text-end">
            <x-button.new text="Upload New" />
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Image</th>
                        <th>Display</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</x-card>
@stop
