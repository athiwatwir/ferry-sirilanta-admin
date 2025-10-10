@extends('layouts.default')

@section('content')
<x-card>
    <div class="row">
        <div class="col-12 text-end">
            <x-button.new :href="route('agent.create')" />
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-12">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Code</th>
                        <th class="text-center">API</th>
                        <th class="text-center">Wallet</th>
                        <th class="text-center">Active Route</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($agents as $item)
                    <tr>
                        <td data-href="{{ route('agent.route',['agent'=>$item]) }}" class="clickable-row pointer">
                            <x-avatar :url="$item->logo" />
                        </td>
                        <td data-href="{{ route('agent.route',['agent'=>$item]) }}" class="clickable-row pointer">{{ $item->name }}</td>
                        <td data-href="{{ route('agent.route',['agent'=>$item]) }}" class="clickable-row pointer">{{ $item->code }}</td>
                        <td class="text-center clickable-row pointer" data-href="{{ route('agent.route',['agent'=>$item]) }}">
                            <x-label-active-icon :isactive="$item->is_use_api" />
                        </td>
                        <td class="text-center clickable-row pointer" data-href="{{ route('agent.route',['agent'=>$item]) }}">
                            <x-label-active-icon :isactive="$item->is_use_wallet" />
                        </td>
                        <td class="text-center clickable-row pointer" data-href="{{ route('agent.route',['agent'=>$item]) }}">{{ sizeof($item->activeAgentSubRoutes) }}</td>
                        <td class="text-end">
                            <x-button.dropdown editUrl="" :deleteUrl="route('agent.destroy',['agent'=>$item->id])">

                                <li>
                                    <a class="dropdown-item" href="{{ route('agent.route',['agent'=>$item]) }}"><i class="icon-base ti tabler-device-projector icon-22px"></i> View</a>
                                </li>
                            </x-button.dropdown>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-card>
@stop

@section('script')

<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".clickable-row").forEach(function(row) {
            row.addEventListener("click", function() {
                window.location = row.dataset.href;
            });
        });
    });

</script>
@stop
