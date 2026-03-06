@extends('layouts.default')

@section('content')
<x-card>
    <x-form :action="route('broker.updateUser', ['user' => $user])">
        @method('patch')
        <div class="row">
            <div class="col-12 col-lg-3">
                <x-form.float.input name="name" label="Full Name" :value="$user->name" />
            </div>
            <div class="col-12 col-lg-3">
                <x-form.float.input name="mobile" label="Mobile" :isrequire="false" :value="$user->mobile" />
            </div>
            <div class="col-12 col-lg-3">
                <x-form.float.input name="email" label="Email" :value="$user->email" />
            </div>
            <div class="col-12 col-lg-3">
                <x-form.float.input name="password" label="Password" type="password" :isrequire="false" help="เว้นว่าง หากไม่ต้องการเปลี่ยนรหัสผ่าน" />
            </div>
        </div>
    </x-form>
</x-card>
@stop
