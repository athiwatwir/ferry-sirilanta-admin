@extends('layouts.default')

@section('content')
<div class="row mb-4">
    <div class="col-12 col-xl-10 mx-auto">
        <div class="d-flex align-items-start p-3 rounded bg-label-primary mb-4">
            <div class="avatar avatar-sm me-3 flex-shrink-0 mt-1">
                <span class="avatar-initial rounded bg-primary">
                    <i class="icon-base ti tabler-receipt-2 icon-sm"></i>
                </span>
            </div>
            <div>
                <h6 class="mb-1 fw-semibold text-primary">Payment Fee Settings</h6>
                <p class="mb-0 text-body small">
                    Configure processing fees for Credit Card and Thai QR payment channels.
                    Fee can be a percentage of the ticket amount or a fixed THB amount.
                </p>
            </div>
        </div>

        <x-card>
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <div class="d-flex align-items-center gap-2">
                    <span class="avatar avatar-sm">
                        <span class="avatar-initial rounded bg-label-info">
                            <i class="icon-base ti tabler-list icon-sm"></i>
                        </span>
                    </span>
                    <div>
                        <h5 class="mb-0">Fee Profiles</h5>
                        <small class="text-muted">{{ $fees->count() }} profile{{ $fees->count() === 1 ? '' : 's' }}</small>
                    </div>
                </div>
                <!--
                <x-button.new :href="route('settingFee.create')" text="New Fee"  />
                -->
            </div>
            <hr class="mt-0">

            <div class="table-responsive">
                <table class="table table-hover mb-10">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Credit Card Fee</th>
                            <th>Thai QR Fee</th>
                            <th>Updated</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($fees as $fee)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $fee->name }}</td>
                            <td><span class="badge bg-label-secondary">{{ $fee->code }}</span></td>
                            <td>
                                {{ $fee->formatFee('credit_card') }}
                                <small class="text-muted d-block">{{ $feeTypes[$fee->credit_card_fee_type] ?? $fee->credit_card_fee_type }}</small>
                            </td>
                            <td>
                                {{ $fee->formatFee('thai_qr') }}
                                <small class="text-muted d-block">{{ $feeTypes[$fee->thai_qr_fee_type] ?? $fee->thai_qr_fee_type }}</small>
                            </td>
                            <td>{{ $fee->updated_at?->format('d/m/Y H:i') ?? '-' }}</td>
                            <td class="text-end">
                                <x-button.dropdown :editUrl="route('settingFee.edit', $fee)" :deleteUrl="route('settingFee.destroy', $fee)" />
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                No fee settings yet. Click <strong>New Fee</strong> to create one.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </div>
</div>
@endsection
