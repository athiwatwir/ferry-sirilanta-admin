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
<script src="{{ asset('assets/vendor/libs/notyf/notyf.js') }}"></script>
<script src="{{ asset('js/ui-toasts.js') }}"></script>

<script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

<!-- endbuild -->

<!-- Vendors JS -->

<!-- Main JS -->
<script src="{{ asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/bootstrap-daterangepicker/bootstrap-daterangepicker.js') }}"></script>


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

        $(document).ready(function() {
            //$(".selectpicker").selectpicker();
        });
    });

</script>
