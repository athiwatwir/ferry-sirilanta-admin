@extends('layouts.default')

@section('content')
<div class="row">
    <div class="col-12 col-xl-10 mx-auto">
        <div class="d-flex align-items-start p-3 rounded bg-label-warning mb-4">
            <div class="avatar avatar-sm me-3 flex-shrink-0 mt-1">
                <span class="avatar-initial rounded bg-warning">
                    <i class="icon-base ti tabler-building-store icon-sm"></i>
                </span>
            </div>
            <div>
                <h6 class="mb-1 fw-semibold text-warning">สร้าง Broker ใหม่</h6>
                <p class="mb-0 text-body small">
                    กรอกข้อมูล Broker ส่วนลด การเชื่อม Agent API และบัญชีเข้าสู่ระบบ
                    ชื่อที่ระบุจะใช้ทั้งในโปรไฟล์ Broker และชื่อผู้ใช้งาน
                </p>
            </div>
        </div>

        <x-card>
            <x-form :action="route('salesPartner.store')" :backUrl="route('broker.index')">
                <input type="hidden" name="type" value="broker">
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
                                <h5 class="mb-0">ข้อมูล Broker</h5>
                                <small class="text-muted">ชื่อ รหัสอ้างอิง ส่วนลด และการเชื่อม Agent API</small>
                            </div>
                        </div>
                        <hr class="mt-3 mb-0">
                    </div>

                    <div class="col-12 col-md-4">
                        <x-form.float.input name="name" label="Name" :value="old('name')" placeholder="ชื่อ Broker" />
                    </div>
                    <div class="col-12 col-md-2">
                        <x-form.float.input name="code" label="Code" :value="old('code')" :isrequire="false" placeholder="รหัสอ้างอิง" />
                    </div>
                    <div class="col-12 col-md-3">
                        <x-form.float.selection name="discount_type" label="Discount Type" :options="$discountTypes" :default="old('discount_type')" :isrequire="false" :isempty="true" />
                    </div>
                    <div class="col-12 col-md-3">
                        <x-form.float.input name="discount" label="Discount" type="number" :value="old('discount')" :isrequire="false" placeholder="0" min="0" step="0.01" />
                    </div>

                    <div class="col-12 col-md-4">
                        <x-form.float.selection name="agent_api_id" label="Agent API" :options="$apiAgents" :default="old('agent_api_id')" :isrequire="false" :isempty="true" help="เลือก Agent จากตาราง agents สำหรับเชื่อมต่อ API" />
                        @error('agent_api_id')
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
        document.getElementById('frm') ? .addEventListener('submit', syncName);
        syncName();
    })();

</script>
@stop
