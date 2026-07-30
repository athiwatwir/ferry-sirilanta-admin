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
                            <p>{{ $broker->code ?? '-' }}</p>
                        </div>
                        <div class="col-12 col-lg-4">
                            <strong>Email</strong>
                            <p>{{ $broker->user?->email ?? '-' }}</p>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-start p-3 rounded bg-label-warning mt-1">
                                <div class="avatar avatar-sm me-3 flex-shrink-0 mt-1">
                                    <span class="avatar-initial rounded bg-warning">
                                        <i class="icon-base ti tabler-discount icon-sm"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                        <h6 class="mb-0 fw-semibold text-warning">Discount</h6>
                                        <span class="badge bg-warning text-dark fs-6">{{ number_format($broker->discount ?? 0, 2) }}</span>
                                        @if($broker->discount_type)
                                        <span class="badge bg-label-warning">{{ $discountTypes[$broker->discount_type] ?? $broker->discount_type }}</span>
                                        @endif
                                    </div>
                                    <p class="text-muted small mb-0">
                                        การคำนวณราคาตั๋วตามปกติ แต่จะได้รับเครดิตคืนจากการขายตั๋ว
                                        @if($broker->discount_type)
                                        <span class="text-body">· คิดส่วนลดแบบ {{ $discountTypes[$broker->discount_type] ?? $broker->discount_type }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </x-card>
    </div>

    <div class="col-12 ">
        <x-card>
            <div class="nav-align-top">
                @php $tab = $activeTab ?? 'bookings'; @endphp
                <ul class="nav nav-pills mb-4" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link {{ $tab === 'bookings' ? 'active' : '' }}" role="tab" data-bs-toggle="tab" data-bs-target="#broker-tab-bookings" aria-selected="{{ $tab === 'bookings' ? 'true' : 'false' }}">
                            <i class="icon-base ti tabler-ticket me-1"></i>รายการจอง
                            <span class="badge bg-label-primary ms-1">{{ count($bookings) }}</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link {{ $tab === 'transactions' ? 'active' : '' }}" role="tab" data-bs-toggle="tab" data-bs-target="#broker-tab-transactions" aria-selected="{{ $tab === 'transactions' ? 'true' : 'false' }}">
                            <i class="icon-base ti tabler-receipt me-1"></i>Transaction
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link {{ $tab === 'users' ? 'active' : '' }}" role="tab" data-bs-toggle="tab" data-bs-target="#broker-tab-users" aria-selected="{{ $tab === 'users' ? 'true' : 'false' }}">
                            <i class="icon-base ti tabler-users-group me-1"></i>รายชื่อพนักงาน Staff ({{ $broker->users->count() }})
                        </button>
                    </li>
                </ul>

                <div class="tab-content p-0">
                    <div class="tab-pane fade p-3 {{ $tab === 'bookings' ? 'show active' : '' }}" id="broker-tab-bookings" role="tabpanel">
                        @include('pages.booking.partials.partner-search', [
                        'formId' => 'frm-broker-bookings',
                        'formAction' => route('broker.show', $broker),
                        'clearUrl' => route('broker.show', ['broker' => $broker, 'tab' => 'bookings']),
                        'collapseId' => 'brokerBookingSearch',
                        'tabValue' => 'bookings',
                        'tablePartial' => 'pages.booking.table.broker',
                        ])
                    </div>

                    <div class="tab-pane fade p-3 {{ $tab === 'transactions' ? 'show active' : '' }}" id="broker-tab-transactions" role="tabpanel">
                        <p class="text-muted small mb-3">รายการ Transaction จาก Agent Account</p>
                        <x-table.datatabble id="broker-transactions-datatable">
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
                                @foreach($transactions as $tx)
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
                                @endforeach
                            </tbody>
                        </x-table.datatabble>
                    </div>

                    <div class="tab-pane fade p-3 {{ $tab === 'users' ? 'show active' : '' }}" id="broker-tab-users" role="tabpanel">
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
            <div class="col-12 col-lg-6">
                <x-form.float.input name="discount" label="Discount%" type="number" :value="old('discount', $broker->discount)" :isrequire="false" placeholder="0" min="0" step="0.01" />
            </div>
            <div class="col-12 col-lg-6">
                <x-form.float.selection name="discount_type" label="Discount Type" :options="$discountTypes" :default="old('discount_type', $broker->discount_type)" :isrequire="false" :isempty="true" />
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
@parent
@include('pages.booking.partials.partner-search-script')
<script>
    document.addEventListener('shown.bs.tab', function(event) {
        var target = event.target.getAttribute('data-bs-target');
        if (target !== '#broker-tab-transactions') return;
        if (typeof $ === 'undefined' || !$.fn.DataTable) return;

        var $table = $('#broker-transactions-datatable');
        if ($table.length && $.fn.DataTable.isDataTable($table)) {
            $table.DataTable().columns.adjust();
            if ($table.DataTable().responsive) {
                $table.DataTable().responsive.recalc();
            }
        }
    });

</script>
@if ($errors->hasAny(['name', 'code', 'email', 'password', 'discount', 'discount_type']))
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
