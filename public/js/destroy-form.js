//data-action=""
//.delete-button
$(document).ready(function () {
    $('.delete-button').on('click', function () {
        let url = $(this).attr('data-action');

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            customClass: {
                confirmButton: 'btn btn-primary me-1',
                cancelButton: 'btn btn-label-secondary'
            },
            buttonsStyling: false
        }).then(function (result) {
            if (result.value) {
                // สร้างฟอร์มใหม่
                const form = $('<form>', {
                    method: 'POST',
                    action: url
                });

                // ใส่ CSRF และ Method DELETE
                const token = $('meta[name="csrf-token"]').attr('content');

                form.append($('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: token
                }));

                form.append($('<input>', {
                    type: 'hidden',
                    name: '_method',
                    value: 'DELETE'
                }));

                // เพิ่มฟอร์มไปใน body แล้ว submit
                $('body').append(form);

                showLoading();
                form.submit();
            }
        });
    });
});
