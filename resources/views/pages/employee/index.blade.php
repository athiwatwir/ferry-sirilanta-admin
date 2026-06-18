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
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($brokers as $broker)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $broker->name }}</td>
                <td>{{ $broker->code }}</td>
                <td>{{ $broker->user->email }}</td>
                <td class="text-center">{{ number_format($broker->point ?? 0) }}</td>
                <td class="text-end">
                    <a href="{{ route('employee.show', ['employee' => $broker]) }}" class="btn btn-outline-secondary ">View</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</x-card>
@endsection
