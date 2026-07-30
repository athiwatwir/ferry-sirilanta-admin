@extends('layouts.default')

@section('content')
<div class="row">
    <div class="col-12 col-xl-10 mx-auto">
        <div class="d-flex align-items-start p-3 rounded bg-label-warning mb-4">
            <div class="avatar avatar-sm me-3 flex-shrink-0 mt-1">
                <span class="avatar-initial rounded bg-warning">
                    <i class="icon-base ti tabler-user-plus icon-sm"></i>
                </span>
            </div>
            <div>
                <h6 class="mb-1 fw-semibold text-warning">สร้าง Employee ใหม่</h6>
                <p class="mb-0 text-body small">
                    กรอกข้อมูลพนักงานขายและบัญชีเข้าสู่ระบบ
                    ชื่อที่ระบุจะใช้ทั้งในโปรไฟล์ Employee และชื่อผู้ใช้งาน
                </p>
            </div>
        </div>

        <x-card>
            <x-form :action="route('salesPartner.store')" :backUrl="route('employee.index')">
                <input type="hidden" name="type" value="employee">
                <input type="hidden" name="user[name]" id="user_name" value="{{ old('user.name', old('name')) }}">

                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar avatar-sm">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="icon-base ti tabler-id icon-sm"></i>
                                </span>
                            </span>
                            <div>
                                <h5 class="mb-0">ข้อมูล Employee</h5>
                                <small class="text-muted">ชื่อและรหัสอ้างอิงของพนักงานขาย</small>
                            </div>
                        </div>
                        <hr class="mt-3 mb-0">
                    </div>

                    <div class="col-12 col-md-6">
                        <x-form.float.input name="name" label="Name" :value="old('name')" placeholder="ชื่อพนักงาน" />
                    </div>
                    <div class="col-12 col-md-3">
                        <x-form.float.input
                            name="code"
                            label="Code"
                            :value="old('code')"
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
                                <h5 class="mb-0">บัญชีเข้าสู่ระบบ</h5>
                                <small class="text-muted">ใช้อีเมลและรหัสผ่านเพื่อล็อกอินเข้าระบบ</small>
                            </div>
                        </div>
                        <hr class="mt-3 mb-0">
                    </div>

                    <div class="col-12 col-md-6">
                        <x-form.float.input name="user[email]" label="Email" type="email" :value="old('user.email')" placeholder="email@example.com" />
                    </div>
                    <div class="col-12 col-md-6">
                        <x-form.float.input name="user[password]" label="Password" type="password" placeholder="อย่างน้อย 8 ตัวอักษร" />
                    </div>
                </div>
            </x-form>
        </x-card>
    </div>
</div>
@stop

@section('script')
<script src="{{ asset('js/form-input.js') }}"></script>
<script>
    (function() {
        const nameInput = document.getElementById('name');
        const userNameInput = document.getElementById('user_name');
        if (!nameInput || !userNameInput) return;

        const syncName = () => {
            userNameInput.value = nameInput.value.trim();
        };

        nameInput.addEventListener('input', syncName);
        document.getElementById('frm')?.addEventListener('submit', syncName);
        syncName();
    })();

</script>
@stop
