<?php

return [

    /*
    |--------------------------------------------------------------------------
    | 2C2P Payment Gateway
    |--------------------------------------------------------------------------
    |
    | Docs: https://developer.2c2p.com/docs/sandbox-setup
    | Sandbox base: https://sandbox-pgw.2c2p.com
    | Production base: https://pgw.2c2p.com
    |
    | โปรเจกต์นี้มี 2 Merchant ID:
    | - credit : สำหรับบัตรเครดิต/เดบิต (CC)
    | - etc    : สำหรับช่องทางอื่น (QR, Wallet, ฯลฯ)
    |
    */

    'merchant_id_credit' => env('TWOC2P_MERCHANT_ID_CREDIT', env('MERCHANT_ID_CREDIT')),
    'merchant_id_etc' => env('TWOC2P_MERCHANT_ID_ETC', env('MERCHANT_ID_ETC')),

    // fallback เก่า (ถ้ายังใช้ตัวเดียว)
    'merchant_id' => env('TWOC2P_MERCHANT_ID'),

    'secret_key_credit' => env('TWOC2P_SECRET_KEY_CREDIT', env('TWOC2P_SECRET_KEY')),
    'secret_key_etc' => env('TWOC2P_SECRET_KEY_ETC', env('TWOC2P_SECRET_KEY')),
    'secret_key' => env('TWOC2P_SECRET_KEY'),

    'currency_code' => env('TWOC2P_CURRENCY_CODE', 'THB'),

    'base_url' => env(
        'TWOC2P_BASE_URL',
        env('TWOC2P_SANDBOX', true)
            ? 'https://sandbox-pgw.2c2p.com'
            : 'https://pgw.2c2p.com'
    ),

    'sandbox' => filter_var(env('TWOC2P_SANDBOX', true), FILTER_VALIDATE_BOOLEAN),

    'timeout' => (int) env('TWOC2P_TIMEOUT', 30),

    /*
    | Default payment channels for hosted checkout.
    | Empty array = all channels enabled by merchant.
    | Example: ['CC'] for credit/debit card only.
    */
    'default_payment_channels' => array_values(array_filter(
        array_map('trim', explode(',', (string) env('TWOC2P_PAYMENT_CHANNELS', 'CC')))
    )),

    /*
    | Channels ที่ถือว่าเป็นกลุ่มบัตร → ใช้ merchant_id_credit
    */
    'credit_channels' => ['CC', 'CSTOKEN', 'GCARD'],

    /*
    | Channels สำหรับ merchant ETC (QR / Wallet ฯลฯ)
    | ว่าง = ให้ 2C2P โชว์ทุกช่องทางที่เปิดไว้บน merchant ETC
    */
    'etc_payment_channels' => array_values(array_filter(
        array_map('trim', explode(',', (string) env('TWOC2P_ETC_PAYMENT_CHANNELS', 'THQR,DPAY,QRC,CSQR')))
    )),

];
