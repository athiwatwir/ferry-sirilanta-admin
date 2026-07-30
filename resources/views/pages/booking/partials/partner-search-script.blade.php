<script>
    (function() {
        if (typeof window.__partnerBookingSearchInit === 'function') {
            window.__partnerBookingSearchInit();
            return;
        }

        window.__partnerBookingSearchInit = function() {
            if (typeof $ === 'undefined' || typeof moment === 'undefined') return;

            @php
                $drStart = isset($startDate) && $startDate instanceof \Carbon\CarbonInterface
                    ? $startDate->toDateTimeString()
                    : (string) ($startDate ?? now()->subDays(6));
                $drEnd = isset($endDate) && $endDate instanceof \Carbon\CarbonInterface
                    ? $endDate->toDateTimeString()
                    : (string) ($endDate ?? now());
            @endphp

            var start = moment(@json($drStart));
            var end = moment(@json($drEnd));

            $('.partner-booking-daterange').each(function() {
                var $input = $(this);
                if ($input.data('daterangepicker')) return;

                $input.daterangepicker({
                    startDate: start,
                    endDate: end,
                    ranges: {
                        Today: [moment(), moment()],
                        Yesterday: [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    locale: { format: 'DD/MM/YYYY' },
                    opens: (typeof window.isRtl !== 'undefined' && window.isRtl) ? 'left' : 'right'
                });
            });

            function submitExport(formId, type) {
                var form = $('#' + formId);
                if (!form.length) return;
                $('#' + formId + '-ispdf').val(type === 'pdf' ? 'Y' : 'N');
                $('#' + formId + '-export').val(type === 'excel' ? 'excel' : '');
                form.attr('target', '_blank');
                form.submit();
                setTimeout(function() {
                    form.removeAttr('target');
                    $('#' + formId + '-ispdf').val('N');
                    $('#' + formId + '-export').val('');
                }, 300);
            }

            $(document).off('click.partnerBookingExport');
            $(document).on('click.partnerBookingExport', '.partner-booking-export-excel', function() {
                submitExport($(this).data('form'), 'excel');
            });
            $(document).on('click.partnerBookingExport', '.partner-booking-export-pdf', function() {
                submitExport($(this).data('form'), 'pdf');
            });
        };

        $(document).ready(function() {
            window.__partnerBookingSearchInit();
        });
    })();
</script>
