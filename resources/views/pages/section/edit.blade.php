@extends('layouts.default')

@section('content')

<div class="row">
    <div class="col-12 col-lg-4">
        <x-card>
            <x-form action="{{ route('section.update',['section'=>$section]) }}">
                @method('put')
                <div class="row">
                    <div class="col-12">
                        <x-form.float.input name="name" label="Section Name" value="{{ $section->name }}" />
                    </div>
                    <div class="col-12">
                        <x-form.float.input name="name_th" label="Section Thai Name" value="{{ $section->name_th }}" />
                    </div>
                    <div class="col-12">
                        <x-form.float.selection :options="$sortOptions" name="sort" label="Sorting" default="{{ $section->sort }}" />
                    </div>
                </div>
            </x-form>
        </x-card>
    </div>
</div>


@stop
