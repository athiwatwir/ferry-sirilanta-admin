<aside id="layout-menu" class="layout-menu-horizontal menu-horizontal menu flex-grow-0">
    <div class="container-xxl d-flex h-100">
        <ul class="menu-inner py-1">
            <!-- Page -->
            <li class="menu-item">
                <a href="{{ route('booking.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-category  "></i>
                    <div data-i18n="Page 1">Bookings</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('route.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-speedboat"></i>
                    <div data-i18n="Page 1">Routes</div>
                </a>
            </li>



            <li class="menu-item">
                <a href="{{ route('payment.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-report-money"></i>
                    <div data-i18n="Page 2">Payment Transactions</div>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('user.index') }}" class="menu-link">
                    <i class="menu-icon icon-base ti tabler-user-hexagon"></i>
                    <div data-i18n="Page 2">Users</div>
                </a>
            </li>

            <li class="menu-item">
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ti tabler-info-square-rounded"></i>
                    <div data-i18n="Page 2">Information Setting</div>
                </a>
                <ul class="menu-sub">

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
                <a href="javascript:void(0)" class="menu-link menu-toggle">
                    <i class="menu-icon icon-base ti tabler-adjustments-dollar"></i>
                    <div data-i18n="Page 2">Financial Setting</div>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="{{ route('financial.fee') }}" class="menu-link">
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


                </ul>
            </li>

        </ul>
    </div>
</aside>
