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
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-8">
                            <h4 class="mb-1">Employee Information</h4>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <x-label-active-status :isactive="$employee->isactive ?? 'Y'" />
                                <x-switch
                                    :isactive="$employee->isactive ?? 'Y'"
                                    :action="route('employee.changeStatus', $employee)"
                                />
                            </div>
                        </div>
                        <div class="col-12 col-lg-4 text-lg-end mt-2 mt-lg-0">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modal-edit-employee">
                                <i class="icon-base ti tabler-edit me-1"></i>Edit
                            </button>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <strong>Name</strong>
                            <p>{{ $employee->name }}</p>
                        </div>
                        <div class="col-12 col-lg-4">
                            <strong>Code</strong>
                            <p>{{ $employee->code ?? $employee->user?->code ?? '-' }}</p>
                        </div>
                        <div class="col-12 col-lg-4">
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
                                <th>Type</th>
                                <th>Description</th>
                                <th class="text-center">Point</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->created_at?->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge bg-{{ $transaction->type == 'withdraw' ? 'success' : 'info' }}">
                                        {{ $transaction->type == 'withdraw' ? 'ถอน Point' : ucfirst($transaction->type) }}
                                    </span>
                                </td>
                                <td>{{ $transaction->description ?? '-' }}</td>
                                <td class="text-center fw-semibold">{{ number_format($transaction->amount) }}</td>
                                <td class="text-center">
                                    @if($transaction->isapproved == 'Y')
                                    <span class="badge bg-success">อนุมัติแล้ว</span>
                                    @else
                                    <span class="badge bg-warning">รออนุมัติ</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">ยังไม่มีรายการ Transaction</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </x-card>
    </div>
</div>

@endsection

@section('modal')
{{-- Modal ถอน Point --}}
<x-modal id="earnPointModal" title="ถอน Point - รายการที่ยังไม่ถอน" size="modal-lg">
    <div id="earnPointModalLoading" class="text-center py-4">
        <div class="spinner-border text-primary" role="status"></div>
        <p class="mt-2 mb-0">กำลังโหลด...</p>
    </div>
    <div id="earnPointModalContent" class="d-none">
        <p class="text-muted small">เลือกรายการจองที่ต้องการถอน</p>
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

<x-modal id="modal-edit-employee" title="แก้ไขข้อมูล Employee" size="modal-lg">
    <x-form type="modal" :action="route('employee.update', $employee)" method="POST">
        @method('PUT')
        <div class="row g-3 mb-3">
            <div class="col-12">
                <div class="d-flex align-items-center gap-2">
                    <span class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="icon-base ti tabler-id icon-sm"></i>
                        </span>
                    </span>
                    <div>
                        <h6 class="mb-0">ข้อมูล Employee</h6>
                        <small class="text-muted">ชื่อจะใช้ทั้งโปรไฟล์และบัญชีเข้าสู่ระบบ</small>
                    </div>
                </div>
                <hr class="mt-3 mb-0">
            </div>
            <div class="col-12 col-md-7">
                <x-form.float.input
                    name="name"
                    label="Name"
                    :value="old('name', $employee->name)"
                    :isrequire="true"
                    placeholder="ชื่อพนักงาน"
                />
            </div>
            <div class="col-12 col-md-5">
                <x-form.float.input
                    name="code"
                    label="Code"
                    :value="old('code', $employee->code ?? $employee->user?->code)"
                    :isrequire="true"
                    placeholder="เช่น EMP00001"
                    maxlength="8"
                    pattern="[A-Za-z0-9]{8}"
                    title="ภาษาอังกฤษหรือตัวเลขเท่านั้น 8 ตัว"
                    help="ต้องเป็นภาษาอังกฤษหรือตัวเลข จำนวน 8 ตัวเท่านั้น"
                />
                @error('code')
                    <div class="text-danger small mt-n2 mb-3">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12">
                <div class="d-flex align-items-center gap-2">
                    <span class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-success">
                            <i class="icon-base ti tabler-lock icon-sm"></i>
                        </span>
                    </span>
                    <div>
                        <h6 class="mb-0">บัญชีเข้าสู่ระบบ</h6>
                        <small class="text-muted">เว้นรหัสผ่านว่างไว้หากไม่ต้องการเปลี่ยน</small>
                    </div>
                </div>
                <hr class="mt-3 mb-0">
            </div>
            <div class="col-12 col-md-6">
                <x-form.float.input
                    name="email"
                    label="Email"
                    type="email"
                    :value="old('email', $employee->user?->email)"
                    :isrequire="true"
                    placeholder="email@example.com"
                />
                @error('email')
                    <div class="text-danger small mt-n2 mb-3">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12 col-md-6">
                <x-form.float.input
                    name="password"
                    label="Password"
                    type="password"
                    :isrequire="false"
                    value=""
                    placeholder="เว้นว่างถ้าไม่เปลี่ยน"
                />
                @error('password')
                    <div class="text-danger small mt-n2 mb-3">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </x-form>
</x-modal>
@endsection

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
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content') || '';
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
            checked.forEach(cb => {
                sum += parseInt(cb.dataset.point || 0, 10);
            });
            sumEl.textContent = sum.toLocaleString();
            submitBtn.disabled = checked.length === 0;
            selectAllCb.checked = checked.length > 0 && checked.length === bookings.length;
        }

        selectAllCb.addEventListener('change', function() {
            tbody.querySelectorAll('.earn-point-cb').forEach(cb => {
                cb.checked = this.checked;
            });
            updateSumAndSubmit();
        });


        modalEl.addEventListener('show.bs.modal', function() {
            loadingEl.classList.remove('d-none');
            contentEl.classList.add('d-none');
            emptyEl.classList.add('d-none');
            bookings = [];

            let _url = @json(route('employee.earnPointBookings', $employee));
            fetch(_url)
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

            let _url = @json(route('employee.earnPoint', $employee));
            fetch(_url, {
                    method: 'POST'
                    , headers: {
                        'Content-Type': 'application/json'
                        , 'Accept': 'application/json'
                        , 'X-CSRF-TOKEN': getCsrf()
                        , 'X-Requested-With': 'XMLHttpRequest'
                    }
                    , body: JSON.stringify({
                        booking_ids: ids
                    })
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
@if ($errors->hasAny(['name', 'code', 'email', 'password']))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var el = document.getElementById('modal-edit-employee');
        if (el && typeof bootstrap !== 'undefined') {
            new bootstrap.Modal(el).show();
        }
    });
</script>
@endif
@endsection
