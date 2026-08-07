@extends('layouts.default')


@section('content')

<div class="row">
    <div class="col-12 col-lg-5">
        <x-card class="">
            <div class="row">
                <div class="col-12 col-lg-8">
                    <strong class="text-dark">Balance (THB)</strong>
                    <h3 class="text-primary">
                        <x-label-price :price="$agentAccount->wallet_balance" />
                    </h3>
                </div>
                <div class="col-12 col-lg-4 text-end">
                    <a href="javascript:void(0);"
                        class="btn btn-success iframe-modal"
                        modal-id="#modal-iframe-topup"
                        modal-url="{{ route('agentAccount.topUpPage', ['agentAccount' => $agentAccount, 'embed' => 1], false) }}">
                        Top up
                    </a>
                </div>
            </div>
        </x-card>
    </div>

    <div class="col-12 col-lg-7">

    </div>
</div>

<div class="row">
    <div class="col-12">
        <x-card>
            <div class="row">
                <div class="col-12">
                    <h4 class="mb-0">Top up wallet</h4>
                    <p>รายการคำขอเติมเงินเข้า wallet ของ agent</p>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12">
                    <x-table.datatabble>
                        <thead>
                            <tr>
                                <th>เวลา</th>
                                <th>Agent</th>
                                <th>Amount</th>
                                <th>ช่องทาง</th>
                                <th>Ref.</th>
                                <th>สลิป</th>
                                <th class="text-center">สถานะ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $tx)
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
                                <td>{{ $agentAccount->salesPartner?->name ?? '-' }}</td>
                                <td>
                                    <x-label-price :price="$tx->type=='topup' ? $tx->amount : $tx->amount*-1" />
                                </td>
                                <td>
                                    @if($tx->type=='topup')
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
                                    @endif
                                </td>
                                <td>{{ $refText !== '' ? $refText : '-' }}</td>
                                <td>
                                    @if($tx->image_path)
                                    <a href="{{ route('agentAccount.slip', $tx) }}" target="_blank" class="btn btn-sm btn-outline-primary">ดูสลิป</a>
                                    @else
                                    -
                                    @endif
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
                            </tr>
                            @empty
                            {{-- ไม่ใส่แถว colspan: DataTables จะแสดง emptyTable เอง --}}
                            @endforelse
                        </tbody>
                    </x-table.datatabble>
                </div>
            </div>
        </x-card>
    </div>
</div>

@stop

@section('modal')
<x-modal id="modal-iframe-topup" title="เติมเงิน (Top up)" size="modal-lg">
    <iframe id="topup-iframe" src="" width="100%" height="640" style="border: none;"></iframe>
</x-modal>
@stop

@section('script')
<script>
    $(document).ready(function() {
        $('.iframe-modal').on('click', function() {
            let id = $(this).attr('modal-id');
            let url = $(this).attr('modal-url');
            $('#topup-iframe').attr('src', url);
            $(id).modal('show');
        });

        $('#modal-iframe-topup').on('hidden.bs.modal', function() {
            $('#topup-iframe').attr('src', '');
            location.reload();
        });
    });
</script>
@stop
