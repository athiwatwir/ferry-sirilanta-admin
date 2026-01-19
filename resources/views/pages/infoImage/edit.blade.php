@extends('layouts.default')

@section('content')
<x-card>
    <x-form :action="route('infoImage.update',['infoImage'=>$infoImage])">
        @method('put')
        <div class="row">
            <div class="col-12 col-lg-6">
                <x-form.float.input name="name" label="Name" :value="$infoImage->name" />
            </div>
            <div class="col-12 col-lg-3">
                <x-form.float.selection name="type" label="Type" :default="$infoImage->type" :options="['route_map'=>'Route Map','time_table'=>'Time Table']" />
            </div>
            <div class="col-12 col-lg-3">
                <x-form.float.input name="image" label="Image" type="file" :isrequire="false" accept="image/*" />
                @if($infoImage->image_path)
                    <div class="mt-2">
                        <small class="text-muted">Current Image:</small><br>
                        <img src="{{ asset($infoImage->image_path) }}" alt="{{ $infoImage->name }}" class="img-thumbnail mt-1" style="max-width: 200px; max-height: 200px;">
                    </div>
                @endif
            </div>
            <div class="col-12">
                <x-form.float.textarea name="description" label="Description" :value="$infoImage->description" />
            </div>


        </div>
    </x-form>
</x-card>

@stop
