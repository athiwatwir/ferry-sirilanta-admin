@extends('layouts.default')

@section('content')

<div class="row">
    <div class="col-12">
        <x-card>
            <div class="row">
                <div class="col-12 col-lg-5 text-center border-end">
                    <div class="row">
                        <div class="col">
                            <h4 class="mb-0">
                                Credit Used
                            </h4>
                            <h2 class="text-success">{{ number_format($broker->agentAccount?->credit_balance ?? 0) }} THB</h2>
                            <button type="button" class="btn btn-sm btn-outline-secondary mt-1" data-bs-toggle="modal" data-bs-target="#modal-credit-used">Clear Credit</button>
                        </div>
                        <div class="col">
                            <h4 class="mb-0">
                                Credit Limit
                            </h4>
                            <h2 class="text-danger">{{ number_format($broker->agentAccount?->credit_limit ?? 0) }} THB</h2>
                            <button type="button" class="btn btn-sm btn-outline-secondary mt-1" data-bs-toggle="modal" data-bs-target="#modal-credit-limit">Update Credit Limit</button>
                        </div>
                    </div>

                </div>
                <div class="col-12 col-lg-7">
                    <div class="row">
                        <div class="col-12 col-lg-10">
                            <h4>Broker Information</h4>
                        </div>
                        <div class="col-12 col-lg-2 text-end">
                            <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modal-edit-broker">Edit</button>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <strong>Name</strong>
                            <p>{{ $broker->name }}</p>
                        </div>
                        <div class="col-12 col-lg-4">
                            <strong>Code</strong>
                            <p>{{ $broker->code }}</p>
                        </div>
                        <div class="col-12 col-lg-4">
                            <strong>Email</strong>
                            <p>{{ $broker->user?->email ?? '-' }}</p>
                        </div>
                    </div>

                </div>
            </div>
        </x-card>
    </div>

    <div class="col-12 ">
        <x-card>
            <div class="nav-align-top">
                <ul class="nav nav-pills mb-4" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#broker-tab-transactions" aria-selected="true">
                            <i class="icon-base ti tabler-receipt me-1"></i>Transaction
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#broker-tab-users" aria-selected="false">
                            <i class="icon-base ti tabler-users-group me-1"></i>รายชื่อพนักงาน Staff ({{ $broker->users->count() }})
                        </button>
                    </li>
                </ul>

                <div class="tab-content p-0">
                    <div class="tab-pane fade show active" id="broker-tab-transactions" role="tabpanel">
                        <p class="text-muted small mb-3">รายการ Transaction จาก Agent Account</p>
                        <table class="table table-hover table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>วันที่</th>
                                    <th>Type</th>
                                    <th>Description</th>
                                    <th class="text-center">จำนวนเงิน</th>
                                    <th class="text-center">สถานะ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $tx)
                                <tr>
                                    <td>{{ $tx->created_at?->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <span class="badge bg-label-primary">{{ ucfirst($tx->type ?? '-') }}</span>
                                    </td>
                                    <td>{{ $tx->description ?? '-' }}</td>
                                    <td class="text-center">
                                        <x-label-price :price="$tx->amount" />
                                    </td>
                                    <td class="text-center">
                                        @if(($tx->isapproved ?? '') === 'Y')
                                        <span class="badge bg-success">อนุมัติแล้ว</span>
                                        @elseif(($tx->isapproved ?? '') === 'N')
                                        <span class="badge bg-warning text-dark">รออนุมัติ</span>
                                        @else
                                        -
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

                    <div class="tab-pane fade" id="broker-tab-users" role="tabpanel">
                        <div class="d-flex justify-content-end mb-3">
                            <x-button.new :href="route('broker.createUser', ['broker' => $broker])" />
                        </div>
                        <table class="table table-hover table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th class="text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($broker->users as $user)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        {{ $user->name }}
                                        @if ($user->isdefault == 'Y')
                                        <span class="badge bg-primary">Default</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->code ?? '-' }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->mobile ?? '-' }}</td>
                                    <td class="text-end">
                                        @if ($user->isdefault == 'N')
                                        <x-button.dropdown :editUrl="route('broker.editUser', ['user' => $user])" :deleteUrl="route('broker.destroyUser', ['user' => $user])" />
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">ยังไม่มีพนักงาน (Staff)</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</div>

@stop


@section('modal')
<x-modal id="modal-credit-limit" title="แก้ไข Credit Limit">
    <x-form type="modal" :action="route('broker.updateCreditLimit', $broker)" method="POST">
        @method('PATCH')
        <div class="row">
            <div class="col-12">
                <x-form.float.input name="credit_limit" label="Credit Limit (THB)" type="number" :value="old('credit_limit', $broker->agentAccount->credit_limit ?? 0)" :isrequire="true" placeholder="0" min="0" step="0.01" />
            </div>
        </div>
    </x-form>
</x-modal>
<x-modal id="modal-credit-used" title="Clear Credit">
    <x-form type="modal" :action="route('broker.updateCreditUsed', $broker)" method="POST">
        @method('PATCH')
        <div class="row">
            <div class="col-12">
                <x-form.float.input name="amount" label="จำนวนเงินที่จะชำระ (THB)" type="number" :value="old('credit_used', $broker->agentAccount->credit_balance ?? 0)" :isrequire="true" placeholder="0" min="0" step="0.01" />
            </div>
        </div>
    </x-form>
</x-modal>
<x-modal id="modal-edit-broker" title="แก้ไขข้อมูล Broker">
    <x-form type="modal" :action="route('broker.update', $broker)" method="POST">
        @method('PUT')
        <div class="row">
            <div class="col-12 col-lg-6">
                <x-form.float.input name="name" label="Name" :value="old('name', $broker->name)" :isrequire="true" />
            </div>
            <div class="col-12 col-lg-6">
                <x-form.float.input name="code" label="Code" :value="old('code', $broker->code)" :isrequire="false" />
            </div>
            <div class="col-12">
                <x-form.float.input name="email" label="Email" type="email" :value="old('email', $broker->user?->email)" :isrequire="true" />
            </div>
            <div class="col-12">
                <x-form.float.input name="password" label="Password (เว้นว่างถ้าไม่เปลี่ยน)" type="password" :isrequire="false" value="" />
            </div>
        </div>
    </x-form>
</x-modal>
@stop

@section('script')
@if ($errors->hasAny(['name', 'code', 'email', 'password']))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var el = document.getElementById('modal-edit-broker');
        if (el && typeof bootstrap !== 'undefined') {
            new bootstrap.Modal(el).show();
        }
    });

</script>
@endif
@stop
