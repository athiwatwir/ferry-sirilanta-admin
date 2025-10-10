<!doctype html>

<html lang="en" class="layout-compact layout-menu-fixed" dir="ltr" data-skin="default" data-assets-path="../../assets/" data-template="horizontal-menu-template-no-customizer-starter" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME') }}:{{ $title??'' }}</title>

    <meta name="description" content="" />
    <link rel="icon" type="image/png" href="{{ asset('favicon/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('favicon/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}" />
    <meta name="apple-mobile-web-app-title" content="168789chang.com" />
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}" />


    @include('layouts.section.style')
    @stack('styles')
</head>

<body>
    <div class="loader-overlay">
        <div class="loader"></div>
    </div>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
        <div class="layout-container">
            <!-- Navbar -->

            @include('layouts.section.nav')

            <!-- / Navbar -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Menu -->

                    <!-- / Menu -->

                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @isset($breadcrumbs)
                        @php
                        $backUrl = '';
                        @endphp
                        <div class="row">
                            <div class="col-12 col-lg-10">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        @foreach ($breadcrumbs as $index => $item)
                                        @if ($loop->last)
                                        <li class="breadcrumb-item active">{{ $index }}</li>
                                        @else
                                        <li class="breadcrumb-item">
                                            <a href="{{ $item }}">{{ $index }}</a>
                                        </li>
                                        @php
                                        $backUrl = $item;
                                        @endphp
                                        @endif

                                        @endforeach
                                    </ol>
                                </nav>
                            </div>
                            <div class="col-12 col-lg-2 text-end">
                                @if (sizeof($breadcrumbs)>1)
                                <a href="{{ $backUrl }}" class="btn btn-outline-secondary mb-2 waves-effect btn-hover"><i class="icon-base ti tabler-square-rounded-chevrons-left"></i> BACK</a>
                                @endif
                            </div>
                        </div>

                        @endisset

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        @if (isset($title))
                        <h1 class="h3 d-flex align-items-center text-primary">
                            {{ $title }}
                        </h1>
                        @endif
                        @yield('content')
                    </div>
                    <!--/ Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                                <div class="text-body">
                                    ©
                                    <script>
                                        document.write(new Date().getFullYear());

                                    </script>

                                </div>
                                <div class="d-none d-lg-inline-block">

                                </div>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!--/ Content wrapper -->
            </div>

            <!--/ Layout container -->
        </div>
    </div>

    <!-- Overlay -->
    <div class="layout-overlay layout-menu-toggle"></div>

    <!-- Drag Target Area To SlideIn Menu On Small Screens -->
    <div class="drag-target"></div>

    @yield('modal')

    <!--/ Layout wrapper -->

    @include('layouts.section.script')
    @yield('script')
</body>
</html>
