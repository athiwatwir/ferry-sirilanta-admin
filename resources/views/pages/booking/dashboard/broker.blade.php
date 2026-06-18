<div class="row">
    <div class="col-12">
        <x-card>
            <div class="card-body d-flex align-items-end">
                <div class="w-100">
                    <div class="row gy-3">
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded bg-label-primary me-4 p-2">
                                    <i class="icon-base ti tabler-chart-pie-2 icon-lg"></i>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">{{ number_format($salesPartner->agentAccount?->credit_limit-$salesPartner->agentAccount?->credit_balance ?? 0) }}THB</h5>
                                    <small>เครดิตคงเหลือ</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded bg-label-info me-4 p-2">
                                    <i class="icon-base ti tabler-users icon-lg"></i>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">{{ number_format($salesPartner->agentAccount?->credit_balance ?? 0) }}THB</h5>
                                    <small>เครดิตที่ใช้</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <div class="d-flex align-items-center">
                                <div class="badge rounded bg-label-danger me-4 p-2">
                                    <i class="icon-base ti tabler-shopping-cart icon-lg"></i>
                                </div>
                                <div class="card-info">
                                    <h5 class="mb-0">{{ number_format($salesPartner->agentAccount?->credit_limit ?? 0) }}</h5>
                                    <small>เครดิตลิมิต</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('broker.transactions') }}" class="text-body">
                                <div class="d-flex align-items-center">
                                    <div class="badge rounded bg-label-success me-4 p-2">
                                        <i class="icon-base ti tabler-receipt icon-lg"></i>
                                    </div>
                                    <div class="card-info">
                                        <h5 class="mb-0">ประวัติ</h5>
                                        <small>ประวัติการทำรายการ</small>
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
