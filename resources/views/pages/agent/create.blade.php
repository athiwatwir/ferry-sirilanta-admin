@extends('layouts.default')

@section('content')
<x-card>
    <x-form :action="route('salesPartner.store')" :backUrl="route('agent.index')">
        <input type="hidden" name="type" value="agent">
        <div class="row">
            <div class="col-12 col-lg-4">
                <x-form.float.input name="name" label="Name" :value="old('name')" />
            </div>
            <div class="col-12 col-lg-2">
                <x-form.float.input name="code" label="Code" :value="old('code')" :isrequire="false" />
            </div>
            <div class="col-12 col-lg-3">
                <x-form.float.selection name="discount_type" label="Discount Type" :options="$discountTypes" :default="old('discount_type')" :isrequire="false" :isempty="true" />
            </div>
            <div class="col-12 col-lg-2">
                <x-form.float.input name="discount" label="Discount" type="number" :value="old('discount')" :isrequire="false" placeholder="0" min="0" step="0.01" />
            </div>
            <div class="col-12 col-lg-4">
                <x-form.float.selection name="agent_api_id" label="Agent API" :options="$apiAgents" :default="old('agent_api_id')" :isrequire="false" :isempty="true" help="เลือก Agent จากตาราง agents สำหรับเชื่อมต่อ API" />
            </div>

        </div>
        <hr>
        <div class="row">
            <div class="col-12">
                <h5 class="mb-1">Login User</h5>
                <p>ระบบจะสร้างบัญชีผู้ใช้งานให้กับ Agent นี้</p>
            </div>
            <div class="col-12 col-lg-4">
                <x-form.float.input name="user[name]" label="Name" :value="old('user.name')" />
            </div>
            <div class="col-12 col-lg-4">
                <x-form.float.input name="user[email]" label="Email" type="email" :value="old('user.email')" />
            </div>
            <div class="col-12 col-lg-4">
                <x-form.float.input name="user[password]" label="Password" type="password" />
            </div>
        </div>
    </x-form>
</x-card>
@stop

@section('script')
<script src="{{ asset('js/form-input.js') }}"></script>
@stop
