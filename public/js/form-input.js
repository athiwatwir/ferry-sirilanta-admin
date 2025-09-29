'use strict';

(function () {
    $(document).ready(function () {
        $('.inp-eng-num').on('input', function () {

            let value = $(this).val();
            // กรองเฉพาะ a-z, A-Z, 0-9
            let filtered = value.replace(/[^a-zA-Z0-9]/g, '');
            $(this).val(filtered);
        });
    });
})();
