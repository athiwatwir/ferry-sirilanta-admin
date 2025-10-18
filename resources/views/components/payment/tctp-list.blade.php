@props(['isfreecredit' => 'N', 'bg' => '', 'fee' => []])

<div class="row mt-2">
    <div class="col-12">
        <label class="row mb-2 pointer">
            <div class="col-2 col-lg-1 d-flex justify-content-center align-items-center">
                <input class="form-check-input form-check-input-primary payment-methods" type="radio" data-type="_2c2p" data-fee="Y" name="payment_method" id="payment-method-1" value="CC" checked>
            </div>
            <div class="col-9 col-lg-11 card">
                <div class="card-body p-2">
                    <p class="mb-2 d-flex align-items-center flex-wrap">
                        <span class="me-3 d-lg-inline d-block fw-bold w-m-100">Credit Card / Debit Card</span>
                        <img src="{{ asset('icon-payment/visa_icon.svg') }}" class="me-2 w--m-img" width="40" data-bs-toggle="tooltip" data-bs-placement="top" title="VISA">
                        <img src="{{ asset('icon-payment/mastercard_icon.svg') }}" class="me-2 w--m-img" width="40" data-bs-toggle="tooltip" data-bs-placement="top" title="Master Card">
                        <img src="{{ asset('icon-payment/jcb_icon.svg') }}" class="me-2 w--m-img" width="40" data-bs-toggle="tooltip" data-bs-placement="top" title="JCB">
                    </p>

                </div>
            </div>
        </label>

        <label class="row mb-2 pointer">
            <div class="col-2 col-lg-1 d-flex justify-content-center align-items-center">
                <input class="form-check-input form-check-input-primary payment-methods" type="radio" data-type="_2c2p" name="payment_method" id="payment-method-2" value="GCARD">
            </div>
            <div class="col-9 col-lg-11 card">
                <div class="card-body p-2">
                    <p class="mb-2 d-flex align-items-center flex-wrap">
                        <span class="me-3 fw-bold w-m-100">Global Card</span>
                        <img src="{{ asset('icon-payment/unionpay_icon.svg') }}" class="me-2 w--m-img" width="40" data-bs-toggle="tooltip" data-bs-placement="top" title="UnionPay">
                        <img src="{{ asset('icon-payment/diners_icon.svg') }}" class="me-2 w--m-img" width="40" data-bs-toggle="tooltip" data-bs-placement="top" title="Diners">
                    </p>

                </div>
            </div>
        </label>

        <label class="row mb-2 pointer">
            <div class="col-2 col-lg-1 d-flex justify-content-center align-items-center">
                <input class="form-check-input form-check-input-primary payment-methods" type="radio" data-type="_2c2p" name="payment_method" id="payment-method-3" value="DPAY">
            </div>
            <div class="col-9 col-lg-11 card">
                <div class="card-body p-2">
                    <p class="mb-2 d-flex align-items-center flex-wrap">
                        <span class="me-3 fw-bold w-m-100">Digital Payment</span>
                        <img src="{{ asset('icon-payment/alipay_icon.svg') }}" class="me-2 w--m-img" width="40" data-bs-toggle="tooltip" data-bs-placement="top" title="Alipay">
                        <img src="{{ asset('icon-payment/wechatpay_icon.svg') }}" class="me-2 w--m-img" width="40" data-bs-toggle="tooltip" data-bs-placement="top" title="Wechat Pay">
                        <img src="{{ asset('icon-payment/linepay_icon.svg') }}" class="me-2 w--m-img" width="100" data-bs-toggle="tooltip" data-bs-placement="top" title="Line Pay">
                        <img src="{{ asset('icon-payment/truemoney_wallet_icon.svg') }}" class="me-2 w--m-img" width="50" data-bs-toggle="tooltip" data-bs-placement="top" title="TrueMoney">
                    </p>

                </div>
            </div>
        </label>

        <label class="row mb-2 pointer">
            <div class="col-2 col-lg-1 d-flex justify-content-center align-items-center">
                <input class="form-check-input form-check-input-primary payment-methods" type="radio" data-type="_2c2p" name="payment_method" id="payment-method-4" value="THQR">
            </div>
            <div class="col-9 col-lg-11 card">
                <div class="card-body p-2">
                    <p class="mb-2 d-flex align-items-center flex-wrap">
                        <span class="me-3 fw-bold w-m-100">Thai QR Payment</span>
                        <img src="{{ asset('icon-payment/promptpay_icon.svg') }}" class="me-2 w--m-img" width="90" data-bs-toggle="tooltip" data-bs-placement="top" title="Prompt Pay">
                    </p>

                </div>
            </div>
        </label>

        <label class="row mb-2 pointer">
            <div class="col-2 col-lg-1 d-flex justify-content-center align-items-center">
                <input class="form-check-input form-check-input-primary payment-methods" type="radio" data-type="_2c2p" name="payment_method" id="payment-method-5" value="GQR">
            </div>
            <div class="col-9 col-lg-11 card">
                <div class="card-body p-2">
                    <p class="mb-2 d-flex align-items-center flex-wrap">
                        <span class="me-3 fw-bold w-m-100">QR Payment</span>
                        <img src="{{ asset('icon-payment/visa_qr_icon.svg') }}" class="me-2 w--m-img border border-secondary rounded" width="90" data-bs-toggle="tooltip" data-bs-placement="top" title="Visa QR Payment">
                        <img src="{{ asset('icon-payment/mastercard_qr_icon.svg') }}" class="me-2 w--m-img border border-secondary rounded" width="90" data-bs-toggle="tooltip" data-bs-placement="top" title="Master Card QR Payment">
                        <img src="{{ asset('icon-payment/unionpay_qr_icon.svg') }}" class="me-2 w--m-img border border-secondary rounded" width="90" data-bs-toggle="tooltip" data-bs-placement="top" title="UnionPay QR Payment">
                    </p>

                </div>
            </div>
        </label>
    </div>
</div>
