@extends('layouts.default')

@section('content')

<div class="row">
    <div class="col-12">
        <x-card>
            <div class="row">
                <div class="col-12 col-lg-4 text-center border-end">
                    <h1 class="text-primary">
                        Point {{ number_format($employee->point ?? 0) }}
                    </h1>
                    <p class="text-muted small mb-0">จากจำนวนผู้โดยสารใน Booking</p>
                    <hr>
                    <div class="row">

                        <div class="col-12 text-center">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#earnPointModal">
                                ถอน Point
                            </button>
                        </div>
                    </div>

                </div>
                <div class="col-12 col-lg-8">
                    <h4>Employee Information</h4>
                    <hr>
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <strong>Name</strong>
                            <p>{{ $employee->name }}</p>
                        </div>
                        <div class="col-12 col-lg-6">
                            <strong>Email</strong>
                            <p>{{ $employee->user?->email ?? '-' }}</p>
                        </div>
                    </div>


                </div>
            </div>
        </x-card>
    </div>

    <div class="col-12 ">
        <x-card>
            <div class="row">
                <div class="col-12">
                    <h4>Transaction History</h4>
                    <table class="table table-hover table-sm">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Booking No</th>
                                <th class="text-center">ผู้ใหญ่</th>
                                <th class="text-center">เด็ก</th>
                                <th class="text-center">ทารก</th>
                                <th class="text-center">Point</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                            <tr>
                                <td>{{ $booking->departdate?->format('d/m/Y') }}</td>
                                <td>{{ $booking->bookingno }}</td>
                                <td class="text-center">{{ $booking->adult_passenger }}</td>
                                <td class="text-center">{{ $booking->child_passenger }}</td>
                                <td class="text-center">{{ $booking->infant_passenger }}</td>
                                <td class="text-center fw-semibold">{{ $booking->point }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">ยังไม่มีรายการจอง</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </x-card>
    </div>
</div>

{{-- Modal ถอน Point --}}
<x-modal id="earnPointModal" title="ถอน Point - รายการที่ยังไม่ถอน (isearned = N)" size="modal-lg">
    <div id="earnPointModalLoading" class="text-center py-4">
        <div class="spinner-border text-primary" role="status"></div>
        <p class="mt-2 mb-0">กำลังโหลด...</p>
    </div>
    <div id="earnPointModalContent" class="d-none">
        <p class="text-muted small">เลือกรายการจองที่ต้องการถอน point (อัพเดท isearned เป็น Y)</p>
        <div class="table-responsive">
            <table class="table table-sm table-hover">
                <thead>
                    <tr>
                        <th><input type="checkbox" id="earnPointSelectAll" class="form-check-input" title="เลือกทั้งหมด"></th>
                        <th>วันเดินทาง</th>
                        <th>Booking No</th>
                        <th class="text-center">ผู้ใหญ่</th>
                        <th class="text-center">เด็ก</th>
                        <th class="text-center">ทารก</th>
                        <th class="text-center">Point</th>
                    </tr>
                </thead>
                <tbody id="earnPointTableBody"></tbody>
            </table>
        </div>
        <p class="mb-0 small"><strong>รวม Point ที่เลือก:</strong> <span id="earnPointSelectedSum">0</span></p>
        <div class="modal-footer border-0 px-0 pb-0">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            <button type="button" class="btn btn-success" id="earnPointSubmitBtn" disabled>ถอน Point</button>
        </div>
    </div>
    <div id="earnPointModalEmpty" class="d-none text-center py-4 text-muted">
        ไม่มีรายการจองที่ยังไม่ถอน point
    </div>
</x-modal>

@section('script')
<script>
(function() {
    const employeeId = '{{ $employee->id }}';
    const modalEl = document.getElementById('earnPointModal');
    const loadingEl = document.getElementById('earnPointModalLoading');
    const contentEl = document.getElementById('earnPointModalContent');
    const emptyEl = document.getElementById('earnPointModalEmpty');
    const tbody = document.getElementById('earnPointTableBody');
    const selectAllCb = document.getElementById('earnPointSelectAll');
    const sumEl = document.getElementById('earnPointSelectedSum');
    const submitBtn = document.getElementById('earnPointSubmitBtn');

    let bookings = [];

    function getCsrf() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    }

    function renderRows() {
        tbody.innerHTML = bookings.map(b => `
            <tr>
                <td><input type="checkbox" class="form-check-input earn-point-cb" value="${b.id}" data-point="${b.point}"></td>
                <td>${b.departdate || '-'}</td>
                <td>${b.bookingno}</td>
                <td class="text-center">${b.adult_passenger}</td>
                <td class="text-center">${b.child_passenger}</td>
                <td class="text-center">${b.infant_passenger}</td>
                <td class="text-center fw-semibold">${b.point}</td>
            </tr>
        `).join('');
        tbody.querySelectorAll('.earn-point-cb').forEach(cb => {
            cb.addEventListener('change', updateSumAndSubmit);
        });
        selectAllCb.checked = false;
        updateSumAndSubmit();
    }

    function updateSumAndSubmit() {
        const checked = tbody.querySelectorAll('.earn-point-cb:checked');
        let sum = 0;
        checked.forEach(cb => { sum += parseInt(cb.dataset.point || 0, 10); });
        sumEl.textContent = sum.toLocaleString();
        submitBtn.disabled = checked.length === 0;
        selectAllCb.checked = checked.length > 0 && checked.length === bookings.length;
    }

    selectAllCb.addEventListener('change', function() {
        tbody.querySelectorAll('.earn-point-cb').forEach(cb => { cb.checked = this.checked; });
        updateSumAndSubmit();
    });

    modalEl.addEventListener('show.bs.modal', function() {
        loadingEl.classList.remove('d-none');
        contentEl.classList.add('d-none');
        emptyEl.classList.add('d-none');
        bookings = [];

        fetch('{{ route('employee.earnPointBookings', $employee) }}')
            .then(r => r.json())
            .then(data => {
                loadingEl.classList.add('d-none');
                bookings = data.bookings || [];
                if (bookings.length === 0) {
                    emptyEl.classList.remove('d-none');
                } else {
                    contentEl.classList.remove('d-none');
                    renderRows();
                }
            })
            .catch(() => {
                loadingEl.classList.add('d-none');
                emptyEl.classList.remove('d-none');
                emptyEl.textContent = 'โหลดข้อมูลไม่สำเร็จ';
            });
    });

    submitBtn.addEventListener('click', function() {
        const ids = Array.from(tbody.querySelectorAll('.earn-point-cb:checked')).map(cb => cb.value);
        if (ids.length === 0) return;
        submitBtn.disabled = true;

        fetch('{{ route('employee.earnPoint', $employee) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': getCsrf(),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ booking_ids: ids })
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    if (typeof bootstrap !== 'undefined') {
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        if (modal) modal.hide();
                    }
                    window.location.reload();
                } else {
                    alert(data.message || 'เกิดข้อผิดพลาด');
                    submitBtn.disabled = false;
                }
            })
            .catch(() => {
                alert('เกิดข้อผิดพลาด');
                submitBtn.disabled = false;
            });
    });
})();
</script>
@endsection

@endsection
