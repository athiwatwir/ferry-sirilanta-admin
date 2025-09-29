@extends('layouts.default')

@section('content')

<x-card>
    <div class="row">
        <div class="col-12 col-lg-6 mx-auto">
            <x-form>
                <div class="row">
                    <div class="col-12">
                        <table class="table table-borderless">
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td colspan="2" class="text-center">Standard</td>
                                    <td colspan="2" class="text-center">Fixed Price</td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td class="text-center">THB</td>
                                    <td class="text-center">%</td>
                                    <td class="text-center" colspan="2">THB</td>
                                </tr>
                                <tr>
                                    <td>Regular</td>
                                    <td>
                                        <x-input.default />
                                    </td>
                                    <td class="border-end">
                                        <x-input.default />
                                    </td>
                                    <td class="text-end ps-2 p-0 align-middle">
                                        <div class="form-check form-check-success mb-0">
                                            <input class="form-check-input" type="checkbox" value="" id="customCheckSuccess" checked />

                                        </div>
                                    </td>
                                    <td class="p-0">
                                        <x-input.default />
                                    </td>
                                </tr>
                                <tr>
                                    <td>Child</td>
                                    <td>
                                        <x-input.default />
                                    </td>
                                    <td class="border-end">
                                        <x-input.default />
                                    </td>
                                    <td class="text-end ps-2 p-0 align-middle">
                                        <div class="form-check form-check-success mb-0">
                                            <input class="form-check-input" type="checkbox" value="" id="customCheckSuccess" checked />

                                        </div>
                                    </td>
                                    <td class="p-0">
                                        <x-input.default />
                                    </td>
                                </tr>
                                <tr>
                                    <td>Infant</td>
                                    <td>
                                        <x-input.default />
                                    </td>
                                    <td class="border-end">
                                        <x-input.default />
                                    </td>
                                    <td class="text-end ps-2 p-0 align-middle">
                                        <div class="form-check form-check-success mb-0">
                                            <input class="form-check-input" type="checkbox" value="" id="customCheckSuccess" checked />

                                        </div>
                                    </td>
                                    <td class="p-0">
                                        <x-input.default />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </x-form>

        </div>
    </div>
</x-card>

@stop
