<aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu flex-grow-0">

    <div class="container-xxl d-flex h-100">

        <ul class="menu-inner py-1">
            <!-- Page -->
            <li class="menu-item">
                <a href="/" class="menu-link">
                    <strong class="text-primary fs-5">SIRILANTA</strong>
                    @if (Auth::user()->role =='BK')
                    @php
                    $agent = session('agent');
                    @endphp
                    <strong class="text-warning fs-5"> :Broker {{$agent->name }}</strong>
                    @endif
                </a>
            </li>


            <li class="menu-item">
                <a href="{{ route('booking.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-category  "></i>
                    <div data-i18n="Page 1">Bookings</div>
                </a>
            </li>

            @if (Auth::user()->role =='ADMIN')




            <li class="menu-item">
                <a href="{{ route('route.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-speedboat"></i>
                    <div data-i18n="Page 2">Routes</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="{{ route('report.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-report"></i>
                    <div data-i18n="Page 2">Reports</div>
                </a>
            </li>


            <li class="menu-item" style="display: none;">
                <a href="{{ route('user.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-user-hexagon"></i>
                    <div data-i18n="Page 2">User</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ti tabler-adjustments-dollar"></i>
                    <div data-i18n="Page 2">Setting</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('settingFee.index') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-receipt-2"></i>
                            <div data-i18n="Analytics">Fee - ค่าธรรมเนียม</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('financial.fare') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-heart-dollar"></i>
                            <div data-i18n="Analytics">Fare</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="{{ route('promotion.index') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-filter-discount"></i>
                            <div data-i18n="Analytics">Promotion</div>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="{{ route('mapTable.index') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-map"></i>
                            <div data-i18n="Analytics">Route Map/Time Table</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('informationText.index') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-align-box-center-middle"></i>
                            <div data-i18n="Analytics">Information Text</div>
                        </a>
                    </li>


                </ul>
            </li>
            <li class="menu-item">
                <a href="{{ route('agent.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-users-group"></i>
                    <div data-i18n="Page 2">Broker User/Agent</div>
                </a>
            </li>
            @endif

            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ti tabler-user-circle"></i>
                    <div data-i18n="Page 2">Profile, {{ Auth::user()->name }}</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                <i class="icon-base ti tabler-power icon-md me-3"></i><span>Log Out</span>
                            </a>

                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</aside>
