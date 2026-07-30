@extends('layouts.default')

@section('content')
@php
    $broker = $user->salesPartner;
    $brokerCode = $broker?->code;
@endphp
<div class="row">
    <div class="col-12 col-xl-10 mx-auto">
        <div class="d-flex align-items-start p-3 rounded bg-label-warning mb-4">
            <div class="avatar avatar-sm me-3 flex-shrink-0 mt-1">
                <span class="avatar-initial rounded bg-warning">
                    <i class="icon-base ti tabler-user-edit icon-sm"></i>
                </span>
            </div>
            <div>
                <h6 class="mb-1 fw-semibold text-warning">แก้ไข User{{ $broker ? ' ของ ' . $broker->name : '' }}</h6>
                <p class="mb-0 text-body small">
                    อัปเดตข้อมูลพนักงานและบัญชีเข้าสู่ระบบ
                    @if ($brokerCode)
                    · รหัสอ้างอิงแนะนำ: <strong>{{ $brokerCode }}-XXXX</strong>
                    @endif
                </p>
            </div>
        </div>

        <x-card>
            <x-form :action="route('broker.updateUser', ['user' => $user])" :backUrl="route('broker.user', $user->sales_partner_id)">
                @method('patch')

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
                        <x-form.float.input name="name" label="Full Name" :value="old('name', $user->name)" placeholder="ชื่อ-นามสกุล" />
                    </div>
                    <div class="col-12 col-md-3">
                        <x-form.float.input
                            name="code"
                            label="Code"
                            :value="old('code', $user->code)"
                            :isrequire="false"
                            :placeholder="$brokerCode ? $brokerCode . '-0001' : 'รหัสอ้างอิง'"
                            :help="$brokerCode ? 'รูปแบบแนะนำ: ' . $brokerCode . '-XXXX' : null"
                        />
                    </div>
                    <div class="col-12 col-md-3">
                        <x-form.float.input name="mobile" label="Mobile" :value="old('mobile', $user->mobile)" :isrequire="false" placeholder="08xxxxxxxx" />
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
                        <x-form.float.input name="email" label="Email" type="email" :value="old('email', $user->email)" placeholder="email@example.com" />
                    </div>
                    <div class="col-12 col-md-6">
                        <x-form.float.input
                            name="password"
                            label="Password"
                            type="password"
                            :isrequire="false"
                            placeholder="เว้นว่างถ้าไม่เปลี่ยน"
                            help="เว้นว่าง หากไม่ต้องการเปลี่ยนรหัสผ่าน"
                        />
                    </div>
                </div>
            </x-form>
        </x-card>
    </div>
</div>
@stop
