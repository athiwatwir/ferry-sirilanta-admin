<div class="row">
    <div class="col-12">
        <x-card>
            <div class="card-body d-flex align-items-end">
                <div class="w-100">
                    <div class="row gy-3">
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded bg-label-primary me-4 p-2">
                                    <i class="icon-base ti tabler-ticket icon-lg"></i>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">{{ number_format($employeeDashboard['ticket_sales_amount'] ?? 0) }} THB</h5>
                                    <small>ยอดขายตั๋ว ({{ number_format($employeeDashboard['ticket_sales_count'] ?? 0) }} รายการ)</small>
                                    <div class="text-muted" style="font-size: 0.7rem;">7 วันล่าสุด</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('employee.point') }}" class="text-body">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded bg-label-warning me-4 p-2">
                                        <i class="icon-base ti tabler-coins icon-lg"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">{{ number_format($employeeDashboard['pending_point'] ?? 0) }} Point</h5>
                                        <small>Point ที่ยังไม่ได้ถอน</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded bg-label-info me-4 p-2">
                                    <i class="icon-base ti tabler-user icon-lg"></i>
                                </div>
                                <div class="card-info">
                                    <h6 class="mb-1 fw-semibold">{{ Auth::user()->name }}</h6>
                                    <small class="d-block text-muted">{{ Auth::user()->email }}</small>
                                    <small class="d-block text-muted">Code: {{ Auth::user()->code ?? '-' }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('employee.point') }}" class="text-body">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded bg-label-success me-4 p-2">
                                        <i class="icon-base ti tabler-history icon-lg"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">Your Point</h5>
                                        <small>ดูรายละเอียด Point</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</div>
