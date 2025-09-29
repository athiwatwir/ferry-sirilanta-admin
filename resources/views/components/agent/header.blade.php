@props(['agent','active'=>'dashboard'])

<div class="row">
    <div class="col-12">
        <div class="card mb-6">
            <div class="user-profile-header-banner">

            </div>
            <div class="user-profile-header d-flex flex-column flex-lg-row text-sm-start text-center mb-5">
                <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                    <x-agent.profile :path="$agent->logo" class="d-block h-auto ms-0 ms-sm-6 rounded user-profile-img" />
                </div>
                <div class="flex-grow-1 mt-3 mt-lg-5">
                    <div class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-5 flex-md-row flex-column gap-4">
                        <div class="user-profile-info">
                            <h3 class="mb-2 mt-lg-6">{{ $agent->name }}</h3>
                            <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-4 my-2">
                                <li class="list-inline-item d-flex gap-2 align-items-center">
                                    <i class="icon-base ti tabler-plug-connected icon-lg"></i><span class="fw-medium">API: @if ($agent->is_use_api == 'Y')
                                        <span class="text-success">Enable</span> @else Disable
                                        @endif</span>

                                    @if ($agent->is_use_api == 'Y')
                                    <span class="badge badge-outline-info">
                                        KEY: {{ $agent->api_key }}
                                    </span>
                                    @endif

                                </li>
                                <li class="list-inline-item d-flex gap-2 align-items-center">
                                    <i class="icon-base ti tabler-wallet icon-lg"></i><span class="fw-medium">Wallet: @if ($agent->is_use_wallet == 'Y')
                                        <span class="text-success">Enable</span> @else Disable
                                        @endif</span>
                                </li>
                                <li class="list-inline-item d-flex gap-2 align-items-center">
                                    <i class="icon-base ti tabler-calendar icon-lg"></i><span class="fw-medium"> Created
                                        <x-label-date-time :datetime="$agent->created_at" /></span>
                                </li>
                            </ul>
                        </div>
                        <a href="{{ route('agent.edit',['agent'=>$agent]) }}" class="btn btn-secondary mb-1 waves-effect waves-light">
                            <i class="icon-base ti tabler-edit icon-xs me-2"></i>Edit Info
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-md-12">
        <div class="nav-align-top">
            <ul class="nav nav-pills flex-column flex-sm-row mb-6 gap-sm-0 gap-2">
                <li class="nav-item">
                    <a class="nav-link @if ($active=='dashboard')
                        active
                    @endif  waves-effect waves-light" href="{{ route('agent.show',['agent'=>$agent]) }}"><i class="icon-base ti tabler-layout-dashboard icon-sm me-1_5"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if ($active=='route')
                        active
                    @endif  waves-effect waves-light" href="{{ route('agent.route',['agent'=>$agent]) }}"><i class="icon-base ti tabler-route icon-sm me-1_5"></i> Routes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if ($active=='wallet')
                        active
                    @endif  waves-effect waves-light" href="{{ route('agent.wallet',['agent'=>$agent]) }}"><i class="icon-base ti tabler-wallet icon-sm me-1_5"></i> Wallet</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if ($active=='user')
                        active
                    @endif  waves-effect waves-light" href="{{ route('agent.user',['agent'=>$agent]) }}"><i class="icon-base ti tabler-users-group icon-sm me-1_5"></i> User</a>
                </li>
            </ul>
        </div>
    </div>
</div>
