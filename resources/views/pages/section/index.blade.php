@extends('layouts.default')

@section('content')
<div class="nav-align-top" bis_skin_checked="1">
    <ul class="nav nav-pills flex-column flex-md-row mb-6 gap-md-0 gap-2">
        <li class="nav-item">
            <a class="nav-link waves-effect waves-light" href="{{ route('station.index') }}"><i class="menu-icon icon-base ti tabler-current-location"></i> Station Management</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active waves-effect waves-light" href="{{ route('section.index') }}"><i class="icon-base ti tabler-lock icon-sm me-1_5"></i> Section Management</a>
        </li>

    </ul>
</div>

<x-card>

    <div class="row">
        <div class="col text-end">
            <x-button.new-modal modal_id="modal-create-section" />
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>

                            <th>Sort</th>
                            <th>Name</th>
                            <th>Name TH</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sections as $section)
                        <tr>
                            <td>{{ $section->sort }}</td>
                            <td>{{ $section->name }}</td>
                            <td>{{ $section->name_th }}</td>
                            <td>
                                <x-switch :isactive="$section->isactive" :action="route('section.changeStatus',['section'=>$section])" />

                            </td>
                            <td class="text-end">
                                <x-button.dropdown :editUrl="route('section.edit',['section'=>$section])" :deleteUrl="route('section.destroy',['section'=>$section->id])"></x-button.dropdown>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-card>

@stop


@section('modal')
<x-modal id="modal-create-section" title="Create New Section">
    <x-form type="modal" action="{{ route('section.store') }}">
        <div class="row">
            <div class="col-12">
                <x-form.float.input name="name" label="Section Name" />
            </div>
            <div class="col-12">
                <x-form.float.input name="name_th" label="Section Thai Name" />
            </div>
        </div>
    </x-form>
</x-modal>
@stop
