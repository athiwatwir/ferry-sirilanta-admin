@extends('layouts.default')

@section('content')
<x-card>
    <div class="row align-items-center mb-3">
        <div class="col-12 col-lg-8">
            <div class="d-flex align-items-start p-3 rounded bg-label-warning">
                <div class="avatar avatar-sm me-3 flex-shrink-0 mt-1">
                    <span class="avatar-initial rounded bg-warning">
                        <i class="icon-base ti tabler-user-star icon-sm"></i>
                    </span>
                </div>
                <div>
                    <h6 class="mb-1 fw-semibold text-warning">ระบบ Employee (Point)</h6>
                    <p class="mb-2 mb-md-3 text-body small">
                        Employee คือบัญชีพนักงานขายตั๋วที่สามารถรับชำระเงินสดหรือบัตรเครดิตผ่านระบบได้
                        โดยมีการบันทึกยอดขายและสะสมคะแนนตามจำนวนผู้โดยสารที่ขายได้
                    </p>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-label-warning">
                            <i class="icon-base ti tabler-cash icon-xs me-1"></i>รับชำระเงินสด/บัตร
                        </span>
                        <span class="badge bg-label-primary">
                            <i class="icon-base ti tabler-receipt icon-xs me-1"></i>บันทึกยอดขาย
                        </span>
                        <span class="badge bg-label-success">
                            <i class="icon-base ti tabler-coins icon-xs me-1"></i>สะสม Point
                        </span>
                        <span class="badge bg-label-info">
                            <i class="icon-base ti tabler-users icon-xs me-1"></i>ตามจำนวนผู้โดยสาร
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4 text-lg-end mt-3 mt-lg-0">
            <x-button.new :href="route('employee.create', ['type' => 'employee'])" />
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
                <th class="text-center">Point</th>
                <th class="text-center">Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($brokers as $broker)
            @php $isActive = ($broker->isactive ?? 'Y') === 'Y'; @endphp
            <tr class="{{ $isActive ? '' : 'table-secondary' }}">
                <td>{{ $loop->iteration }}</td>
                <td>
                    <span class="fw-medium {{ $isActive ? '' : 'text-muted' }}">{{ $broker->name }}</span>
                </td>
                <td>
                    <code class="small">{{ $broker->code ?? '-' }}</code>
                </td>
                <td class="{{ $isActive ? '' : 'text-muted' }}">{{ $broker->user?->email ?? '-' }}</td>
                <td class="text-center">
                    <span class="badge bg-label-warning">{{ number_format($broker->point ?? 0) }}</span>
                </td>
                <td class="text-center">
                    <div class="d-inline-flex flex-column align-items-center gap-2">
                        @if ($isActive)
                        <span class="badge bg-label-success">
                            <i class="icon-base ti tabler-circle-check me-1"></i>Active
                        </span>
                        @else
                        <span class="badge bg-label-danger">
                            <i class="icon-base ti tabler-circle-x me-1"></i>Inactive
                        </span>
                        @endif

                    </div>
                </td>
                <td class="text-end">
                    <a href="{{ route('employee.show', ['employee' => $broker]) }}" class="btn btn-outline-secondary btn-sm">
                        <i class="icon-base ti tabler-eye me-1"></i>View
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</x-card>
@endsection
