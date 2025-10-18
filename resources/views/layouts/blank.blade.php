<!doctype html>

<html lang="en" class="layout-compact layout-menu-fixed" dir="ltr" data-skin="default" data-assets-path="../../assets/" data-template="horizontal-menu-template-no-customizer-starter" data-bs-theme="light">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME') }}:{{ $title??'' }}</title>

    <meta name="description" content="" />


    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/pickr/pickr-themes.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/loading.css') }}" />

    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    @yield('style')

    <!-- Vendors CSS -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- endbuild -->

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body>
    <div class="loader-overlay">
        <div class="loader"></div>
    </div>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-navbar-full layout-horizontal layout-without-menu">
        <div class="layout-container">

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
    </div>


    @yield('modal')

    <!--/ Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/theme.js -->

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/@algolia/autocomplete-js.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>

    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->

    <script src="{{ asset('assets/js/main.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="{{ asset('js/change-switch-form.js') }}"></script>
    <script src="{{ asset('js/destroy-form.js') }}"></script>

    @include('layouts.section.flashmessage')
    <!-- Page JS -->
    <script>
        const loaderOverlay = document.querySelector('.loader-overlay');
        // ฟังก์ชันเปิด Loading
        function showLoading() {
            if (loaderOverlay) {
                loaderOverlay.style.display = 'flex'; // หรือ 'block'
            }
        }

        // ฟังก์ชันปิด Loading
        function hideLoading() {
            if (loaderOverlay) {
                loaderOverlay.style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {

            const forms = document.querySelectorAll('form');

            // แสดง Loading เมื่อมีการ Submit Form
            forms.forEach(form => {
                form.addEventListener('submit', function() {
                    showLoading();
                });
            });

            // ซ่อน Loading เมื่อหน้าเว็บโหลดเสร็จสมบูรณ์ (กรณีโหลดครั้งแรก)
            window.addEventListener('load', function() {
                hideLoading();
            });

            // ตัวอย่างการใช้งานฟังก์ชันเปิด/ปิด Loading ด้วยตนเอง (เช่น ใน AJAX)
            // function fetchData() {
            //     showLoading();
            //     fetch('/api/data')
            //         .then(response => response.json())
            //         .then(data => {
            //             console.log(data);
            //             hideLoading();
            //         })
            //         .catch(error => {
            //             console.error('Error:', error);
            //             hideLoading();
            //         });
            // }

            // // เรียกใช้งานฟังก์ชัน fetchData เมื่อต้องการ
            // // document.getElementById('fetchDataButton').addEventListener('click', fetchData);
        });

    </script>
    @yield('script')
</body>
</html>
