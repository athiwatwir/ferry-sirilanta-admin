@extends('layouts.default')

@section('content')
<div class="row">
    <div class="col-12 col-xl-10 mx-auto">
        <div class="d-flex align-items-start p-3 rounded bg-label-primary mb-4">
            <div class="avatar avatar-sm me-3 flex-shrink-0 mt-1">
                <span class="avatar-initial rounded bg-primary">
                    <i class="icon-base ti tabler-user-plus icon-sm"></i>
                </span>
            </div>
            <div>
                <h6 class="mb-1 fw-semibold text-primary">สร้าง User ของ {{ $broker->name }}</h6>
                <p class="mb-0 text-body small">
                    เพิ่มพนักงานภายใต้ Broker นี้ เพื่อเข้าใช้งานระบบด้วยอีเมลและรหัสผ่านของตนเอง
                    @if ($broker->code)
                    · รหัสอ้างอิงแนะนำ: <strong>{{ $broker->code }}-XXXX</strong>
                    @endif
                </p>
            </div>
        </div>

        <x-card>
            <x-form :action="route('broker.storeUser', ['broker' => $broker])" :backUrl="route('broker.show', $broker)">
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <div class="d-flex align-items-center gap-2">
                            <span class="avatar avatar-sm">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class="icon-base ti tabler-id icon-sm"></i>
                                </span>
                            </span>
                            <div>
                                <h5 class="mb-0">ข้อมูลผู้ใช้</h5>
                                <small class="text-muted">ชื่อ รหัสอ้างอิง และเบอร์ติดต่อ</small>
                            </div>
                        </div>
                        <hr class="mt-3 mb-0">
                    </div>

                    <div class="col-12 col-md-6">
                        <x-form.float.input name="name" label="Full Name" :value="old('name')" placeholder="ชื่อ-นามสกุล" />
                    </div>
                    <div class="col-12 col-md-3">
                        <x-form.float.input name="code" label="Code" :value="old('code')" :isrequire="false" :placeholder="$broker->code ? $broker->code . '-0001' : 'รหัสอ้างอิง'" :help="$broker->code ? 'รูปแบบแนะนำ: ' . $broker->code . '-XXXX' : null" />
                    </div>
                    <div class="col-12 col-md-3">
                        <x-form.float.input name="mobile" label="Mobile" :value="old('mobile')" :isrequire="false" placeholder="08xxxxxxxx" />
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
                        <x-form.float.input name="email" label="Email" type="email" :value="old('email')" placeholder="email@example.com" />
                    </div>
                    <div class="col-12 col-md-6">
                        <x-form.float.input name="password" label="Password" type="password" placeholder="อย่างน้อย 8 ตัวอักษร" />
                    </div>
                </div>
            </x-form>
        </x-card>
    </div>
</div>
@stop
