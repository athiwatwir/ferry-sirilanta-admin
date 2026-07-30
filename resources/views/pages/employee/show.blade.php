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
                            <a href="{{ route('employee.withdrawPoint', $employee) }}" class="btn btn-success">
                                ถอน Point
                            </a>
                        </div>
                    </div>

                </div>
                <div class="col-12 col-lg-8">
                    <div class="row align-items-center">
                        <div class="col-12 col-lg-8">
                            <h4 class="mb-1">Employee Information</h4>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <x-label-active-status :isactive="$employee->isactive ?? 'Y'" />
                                <x-switch :isactive="$employee->isactive ?? 'Y'" :action="route('employee.changeStatus', $employee)" />
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

    <div class="col-12">
        <x-card>
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-primary">
                            <i class="icon-base ti tabler-ticket icon-sm"></i>
                        </span>
                    </span>
                    <div>
                        <h5 class="mb-0">Bookings</h5>
                        <small class="text-muted">รายการจองของ Employee นี้</small>
                    </div>
                </div>
            </div>
            <hr class="mt-0">
            @include('pages.booking.partials.partner-search', [
                'formId' => 'frm-employee-bookings',
                'formAction' => route('employee.show', $employee),
                'clearUrl' => route('employee.show', $employee),
                'collapseId' => 'employeeBookingSearch',
                'tablePartial' => 'pages.booking.table.employee',
            ])
        </x-card>
    </div>
</div>

@endsection

@section('modal')
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
                <x-form.float.input name="name" label="Name" :value="old('name', $employee->name)" :isrequire="true" placeholder="ชื่อพนักงาน" />
            </div>
            <div class="col-12 col-md-5">
                <x-form.float.input name="code" label="Code" :value="old('code', $employee->code ?? $employee->user?->code)" :isrequire="true" placeholder="เช่น EMP00001" maxlength="8" pattern="[A-Za-z0-9]{8}" title="ภาษาอังกฤษหรือตัวเลขเท่านั้น 8 ตัว" help="ต้องเป็นภาษาอังกฤษหรือตัวเลข จำนวน 8 ตัวเท่านั้น" />
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
                <x-form.float.input name="email" label="Email" type="email" :value="old('email', $employee->user?->email)" :isrequire="true" placeholder="email@example.com" />
                @error('email')
                <div class="text-danger small mt-n2 mb-3">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12 col-md-6">
                <x-form.float.input name="password" label="Password" type="password" :isrequire="false" value="" placeholder="เว้นว่างถ้าไม่เปลี่ยน" />
                @error('password')
                <div class="text-danger small mt-n2 mb-3">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </x-form>
</x-modal>
@endsection

@section('script')
@parent
@include('pages.booking.partials.partner-search-script')
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
