@extends('layouts.default')

@section('content')

<div class="row">
    <div class="col-12">
        <x-card>
            <div class="row">
                <div class="col-12 col-lg-5 d-flex align-items-center justify-content-center border-end border-lg-bottom-0 pb-4 pb-lg-0 mb-4 mb-lg-0">
                    <div class="text-center py-2 px-3 w-100">
                        <div class="avatar avatar-lg mx-auto mb-3">
                            <span class="avatar-initial rounded-circle bg-label-primary">
                                <i class="icon-base ti tabler-wallet icon-lg text-primary"></i>
                            </span>
                        </div>
                        <p class="text-muted small text-uppercase fw-medium mb-2">Wallet Balance</p>
                        <h2 class="display-5 fw-bold text-primary mb-2 lh-1">
                            {{ number_format($agent->agentAccount?->wallet_balance ?? 0,2) }}
                        </h2>
                        <span class="badge bg-label-primary px-3 py-2 fs-6">THB</span>
                        <p class="text-muted small mt-3 mb-0">ยอดคงเหลือในระบบ Wallet สำหรับออกตั๋ว</p>
                    </div>
                </div>
                <div class="col-12 col-lg-7">
                    <div class="row">
                        <div class="col-12 col-lg-10">
                            <h4>Agent Information</h4>
                        </div>
                        <div class="col-12 col-lg-2 text-end">
                            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#modal-edit-agent">แก้ไข</button>
                        </div>
                    </div>


                    <hr>
                    <div class="row">
                        <div class="col-12 col-lg-4">
                            <strong>Name</strong>
                            <p>{{ $agent->name }}</p>
                        </div>
                        <div class="col-12 col-lg-4">
                            <strong>Code</strong>
                            <p>{{ $agent->code ?? '-' }}</p>
                        </div>
                        <div class="col-12 col-lg-4">
                            <strong>Email</strong>
                            <p>{{ $agent->user?->email ?? '-' }}</p>
                        </div>
                        <div class="col-12 col-lg-6">
                            <strong>Agent API</strong>
                            <p>
                                @if ($agent->agentApi)
                                {{ $agent->agentApi->name }}
                                @if ($agent->agentApi->code)
                                <span class="text-muted">({{ $agent->agentApi->code }})</span>
                                @endif
                                @else
                                -
                                @endif
                            </p>
                        </div>
                        <div class="col-12">
                            <div class="d-flex align-items-start p-3 rounded bg-label-primary mt-1">
                                <div class="avatar avatar-sm me-3 flex-shrink-0 mt-1">
                                    <span class="avatar-initial rounded bg-primary">
                                        <i class="icon-base ti tabler-discount icon-sm"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                                        <h6 class="mb-0 fw-semibold text-primary">Discount</h6>
                                        <span class="badge bg-primary fs-6">{{ number_format($agent->discount ?? 0, 2) }}%</span>
                                        @if ($agent->discount_type)
                                        <span class="badge bg-label-primary">{{ $discountTypes[$agent->discount_type] ?? $agent->discount_type }}</span>
                                        @endif
                                    </div>
                                    <p class="text-muted small mb-0">
                                        การคำนวณราคาตั๋วจะลดราคาตอนชำระเงิน
                                        @if ($agent->discount_type)
                                        <span class="text-body">· คิดส่วนลดแบบ {{ $discountTypes[$agent->discount_type] ?? $agent->discount_type }}</span>
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

    <div class="col-12">
        <x-card>
            <div class="nav-align-top">
                <ul class="nav nav-pills mb-4" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link {{ ($activeTab ?? 'bookings') !== 'topup' ? 'active' : '' }}" role="tab" data-bs-toggle="tab" data-bs-target="#agent-tab-bookings" aria-selected="{{ ($activeTab ?? 'bookings') !== 'topup' ? 'true' : 'false' }}">
                            <i class="icon-base ti tabler-ticket me-1"></i>Booking
                            <span class="badge bg-label-primary ms-1">{{ count($bookings) }}</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link {{ ($activeTab ?? 'bookings') === 'topup' ? 'active' : '' }}" role="tab" data-bs-toggle="tab" data-bs-target="#agent-tab-topup" aria-selected="{{ ($activeTab ?? 'bookings') === 'topup' ? 'true' : 'false' }}">
                            <i class="icon-base ti tabler-wallet me-1"></i>รายการเติมเงิน
                            <span class="badge bg-label-warning ms-1">{{ $transactions->where('isapproved', 'N')->count() }}</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content p-0">
                    <div class="tab-pane fade p-3 {{ ($activeTab ?? 'bookings') !== 'topup' ? 'show active' : '' }}" id="agent-tab-bookings" role="tabpanel">
                        @include('pages.booking.partials.partner-search', [
                        'formId' => 'frm-agent-bookings',
                        'formAction' => route('agent.show', $agent),
                        'clearUrl' => route('agent.show', ['agent' => $agent, 'tab' => 'bookings']),
                        'collapseId' => 'agentBookingSearch',
                        'tabValue' => 'bookings',
                        'tablePartial' => 'pages.booking.table.agent',
                        ])
                    </div>

                    <div class="tab-pane fade p-3 {{ ($activeTab ?? 'bookings') === 'topup' ? 'show active' : '' }}" id="agent-tab-topup" role="tabpanel">
                        <p class="text-muted small mb-3">รายการคำขอเติมเงินเข้า wallet</p>
                        <x-table.datatabble id="agent-topup-datatable">
                            <thead>
                                <tr>
                                    <th>วันที่</th>
                                    <th>ช่องทาง</th>
                                    <th>Ref. / หมายเหตุ</th>
                                    <th class="text-end">จำนวนเงิน</th>
                                    <th class="text-center">สถานะ</th>
                                    <th class="text-center">สลิป</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $tx)
                                @php
                                $desc = (string) ($tx->description ?? '');
                                $isCard = str_starts_with($desc, '[CARD]');
                                $isEtc = str_starts_with($desc, '[ETC]');
                                $refText = $desc;
                                foreach (['[CARD]', '[ETC]'] as $tag) {
                                $refText = trim(str_replace($tag, '', $refText));
                                }
                                @endphp
                                <tr>
                                    <td>{{ $tx->created_at?->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @if ($isCard)
                                        <span class="badge bg-label-info">
                                            <i class="icon-base ti tabler-credit-card me-1"></i>บัตรเครดิต
                                        </span>
                                        @elseif ($isEtc)
                                        <span class="badge bg-label-warning">
                                            <i class="icon-base ti tabler-qrcode me-1"></i>QR / Wallet
                                        </span>
                                        @else
                                        <span class="badge bg-label-success">
                                            <i class="icon-base ti tabler-building-bank me-1"></i>โอนเงิน
                                        </span>
                                        @endif
                                    </td>
                                    <td>{{ $refText !== '' ? $refText : '-' }}</td>
                                    <td class="text-end">
                                        <x-label-price :price="$tx->amount" />
                                    </td>
                                    <td class="text-center">
                                        @if(($tx->isapproved ?? '') === 'Y')
                                        <span class="badge bg-success">อนุมัติแล้ว</span>
                                        @elseif(($tx->isapproved ?? '') === 'N')
                                        <span class="badge bg-warning text-dark">รออนุมัติ</span>
                                        @else
                                        <span class="badge bg-secondary">{{ $tx->isapproved ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($tx->image_path)
                                        <a href="{{ route('agentAccount.slip', $tx) }}" target="_blank" class="btn btn-sm btn-outline-primary">ดูสลิป</a>
                                        @else
                                        -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if(($tx->isapproved ?? '') === 'N')
                                        <form action="{{ route('agent.topup.approve', ['agent' => $agent->id, 'transaction' => $tx->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('อนุมัติรายการเติมเงินนี้?');">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">อนุมัติ</button>
                                        </form>
                                        @else
                                        -
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </x-table.datatabble>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</div>

@endsection

@section('modal')
<x-modal id="modal-edit-agent" title="แก้ไขข้อมูล Agent">
    <x-form type="modal" :action="route('agent.update', $agent)" method="POST">
        @method('PUT')
        <div class="row">
            <div class="col-12 col-lg-6">
                <x-form.float.input name="name" label="Name" :value="old('name', $agent->name)" :isrequire="true" />
            </div>
            <div class="col-12 col-lg-6">
                <x-form.float.input name="code" label="Code" :value="old('code', $agent->code)" :isrequire="false" />
            </div>
            <div class="col-12 col-lg-6">
                <x-form.float.input name="email" label="Email" type="email" :value="old('email', $agent->user?->email)" :isrequire="true" />
            </div>
            <div class="col-12 col-lg-6">
                <x-form.float.selection name="discount_type" label="Discount Type" :options="$discountTypes" :default="old('discount_type', $agent->discount_type)" :isrequire="false" :isempty="true" />
            </div>
            <div class="col-12 col-lg-6">
                <x-form.float.input name="discount" label="Discount Amount" type="number" :value="old('discount', $agent->discount)" :isrequire="false" placeholder="0" min="0" max="100" step="0.01" />
            </div>

            <div class="col-12">
                <x-form.float.selection name="agent_api_id" label="Agent API" :options="$apiAgents" :default="old('agent_api_id', $agent->agent_api_id)" :isrequire="false" :isempty="true" help="เลือก Agent จากตาราง agents สำหรับเชื่อมต่อ API" />
                @error('agent_api_id')
                <div class="text-danger small mt-n2 mb-3">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-12">
                <x-form.float.input name="password" label="Password (เว้นว่างถ้าไม่เปลี่ยน)" type="password" :isrequire="false" value="" />
            </div>
        </div>
    </x-form>
</x-modal>
@endsection

@section('script')
@parent
@include('pages.booking.partials.partner-search-script')
<script>
    document.addEventListener('shown.bs.tab', function(event) {
        var target = event.target.getAttribute('data-bs-target');
        if (target !== '#agent-tab-topup') return;
        if (typeof $ === 'undefined' || !$.fn.DataTable) return;

        var $table = $('#agent-topup-datatable');
        if ($table.length && $.fn.DataTable.isDataTable($table)) {
            $table.DataTable().columns.adjust();
            if ($table.DataTable().responsive) {
                $table.DataTable().responsive.recalc();
            }
        }
    });

</script>
@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var el = document.getElementById('modal-edit-agent');
        if (el && typeof bootstrap !== 'undefined') {
            new bootstrap.Modal(el).show();
        }
    });

</script>
@endif
@endsection
