@extends('layouts.default')

@section('content')
<x-card>
    <div class="row align-items-center mb-3">
        <div class="col-12 col-lg-8">
            <div class="d-flex align-items-start p-3 rounded bg-label-info">
                <div class="avatar avatar-sm me-3 flex-shrink-0 mt-1">
                    <span class="avatar-initial rounded bg-info">
                        <i class="icon-base ti tabler-building-store icon-sm"></i>
                    </span>
                </div>
                <div>
                    <h6 class="mb-1 fw-semibold text-info">ระบบ Broker (Credit)</h6>
                    <p class="mb-2 text-body small">
                        Broker คือบัญชีตัวแทนจำหน่ายตั๋วเรือที่ได้รับวงเงินเครดิตจากระบบ
                        สามารถทำรายการขายตั๋วได้ตามวงเงินที่ได้รับอนุมัติ
                        โดยแต่ละ Broker สามารถมีบัญชีผู้ใช้งาน (Staff) หลายคนภายใต้องค์กรเดียวกัน
                    </p>
                    <p class="mb-2 mb-md-3 text-body small">
                        พนักงานทุกคนของ Broker ใช้งานวงเงินเครดิตร่วมกัน
                        ทุกการขายตั๋วจะถูกหักจากเครดิตคงเหลือ
                        และสามารถตรวจสอบยอดเครดิต ประวัติการใช้งาน และรายการขายของพนักงานแต่ละคนได้จากระบบ
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-label-info">
                            <i class="icon-base ti tabler-credit-card icon-xs me-1"></i>วงเงินเครดิต
                        </span>
                        <span class="badge bg-label-primary">
                            <i class="icon-base ti tabler-users-group icon-xs me-1"></i>Staff หลายคน
                        </span>
                        <span class="badge bg-label-success">
                            <i class="icon-base ti tabler-receipt icon-xs me-1"></i>หักเครดิตอัตโนมัติ
                        </span>
                        <span class="badge bg-label-warning">
                            <i class="icon-base ti tabler-history icon-xs me-1"></i>ตรวจสอบประวัติ
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 text-lg-end mt-3 mt-lg-0">
            <x-button.new :href="route('broker.create', ['type' => 'broker'])" />
        </div>
    </div>
    <hr>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Code</th>
                <th>Email</th>
                <th class="text-end">Credit Used</th>
                <th class="text-end">Credit Limit</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($brokers as $broker)
            <tr class="clickable-row pointer" data-href="{{ route('broker.show', ['broker' => $broker]) }}">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $broker->name }}</td>
                <td>{{ $broker->code }}</td>
                <td>{{ $broker->user?->email ?? '-' }}</td>
                <td class="text-end">
                    <x-label-price :price="$broker->agentAccount?->credit_balance ?? 0" />
                </td>
                <td class="text-end">
                    <x-label-price :price="$broker->agentAccount?->credit_limit ?? 0" />
                </td>
                <td class="text-end">
                    <a href="{{ route('broker.show', ['broker' => $broker]) }}" class="btn btn-outline-secondary">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</x-card>
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.clickable-row').forEach(function(row) {
            row.addEventListener('click', function(e) {
                if (e.target.closest('a, button')) {
                    return;
                }
                window.location = row.dataset.href;
            });
        });
    });
</script>
@endsection
