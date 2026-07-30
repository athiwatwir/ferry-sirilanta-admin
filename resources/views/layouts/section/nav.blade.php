<aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu flex-grow-0">

    <div class="container-xxl d-flex h-100">

        <ul class="menu-inner py-1">
            <!-- Page -->
            <li class="menu-item ">
                <a href="/" class="menu-link ps-0">
                    <strong class="text-primary fs-5 f-default-bold">SIRILANTA</strong>
                    @if (Auth::user()->role =='ADMIN')

                    <strong class="text-dark fs-5 f-default-bold">ADMIN</strong>

                    @else
                    <strong class="text-dark fs-5 ms-2 f-default-bold"> {{ strtoupper(Auth::user()->role) }} {{ Auth::user()->name }}</strong>
                    @endif
                </a>
            </li>

            @if (Auth::user()->role !='ADMIN')
            <li class="menu-item">
                <a href="{{ route('dashboard.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-home"></i>
                    <div data-i18n="Page 1">Dashboard</div>
                </a>
            </li>
            @endif

            <li class="menu-item">
                <a href="{{ route('booking.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-category  "></i>
                    <div data-i18n="Page 1">Bookings</div>
                </a>
            </li>

            @if (Auth::user()->role =='agent')
            <li class="menu-item">
                <x-wallet-menu />
            </li>
            @endif

            @if (Auth::user()->role =='employee')
            <li class="menu-item ">
                <a href="{{ route('employee.point') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-clock-bitcoin"></i>
                    <div data-i18n="Page 1">Your Point</div>
                </a>
            </li>
            @endif

            @if (Auth::user()->role =='broker')
            <!--
            <li class="menu-item">
                <a href="{{ route('broker.credit') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-clock-bitcoin"></i>
                    <div data-i18n="Page 1">Your Crdit</div>
                </a>
            </li>
            -->
            <li class="menu-item">
                <a href="{{ route('broker.transactions') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-receipt"></i>
                    <div data-i18n="Page 1">ประวัติการทำรายการ</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('broker.user', ['broker' => Auth::user()->sales_partner_id]) }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-user-check"></i>
                    <div data-i18n="Page 1">พนักงานขาย</div>
                </a>
            </li>

            @endif

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
                    <!--
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
                -->

                    <li class="menu-item">
                        <a href="{{ route('infoImage.index') }}" class="menu-link">
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
                    <li class="menu-item">
                        <a href="{{ route('tag.index') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-tag"></i>
                            <div data-i18n="Analytics">Tag</div>
                        </a>
                    </li>

                </ul>
            </li>
            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ti tabler-affiliate"></i>
                    <div data-i18n="Page 2">Sales Partner</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('agent.index') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-users-group"></i>
                            <div data-i18n="Analytics">Agent</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('broker.index') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-shield-check"></i>
                            <div data-i18n="Analytics">Broker</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="{{ route('employee.index') }}" class="menu-link">
                            <i class="menu-icon icon-base ti tabler-user-circle"></i>
                            <div data-i18n="Analytics">Employee</div>
                        </a>
                    </li>
                </ul>
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
