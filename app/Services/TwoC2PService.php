<?php

namespace App\Services;

use App\Exceptions\TwoC2PException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Reusable 2C2P Payment Gateway service (Payment API v4.3).
 *
 * Merchant profiles:
 * - credit : MERCHANT_ID_CREDIT → บัตรเครดิต/เดบิต (CC)
 * - etc    : MERCHANT_ID_ETC    → QR / Wallet / ช่องทางอื่น
 *
 * Docs: https://developer.2c2p.com/docs/sandbox-setup
 */
class TwoC2PService
{
    public const PROFILE_CREDIT = 'credit';
    public const PROFILE_ETC = 'etc';

    protected string $baseUrl;
    protected string $currencyCode;
    protected int $timeout;
    protected array $defaultPaymentChannels;
    protected array $creditChannels;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('twoc2p.base_url'), '/');
        $this->currencyCode = (string) config('twoc2p.currency_code', 'THB');
        $this->timeout = (int) config('twoc2p.timeout', 30);
        $this->defaultPaymentChannels = (array) config('twoc2p.default_payment_channels', ['CC']);
        $this->creditChannels = array_map('strtoupper', (array) config('twoc2p.credit_channels', ['CC', 'CSTOKEN', 'GCARD']));
    }

    /**
     * Create Payment Token and return hosted payment page URL.
     *
     * @param  array{
     *     invoiceNo: string,
     *     description: string,
     *     amount: float|int|string,
     *     currencyCode?: string,
     *     paymentChannel?: array<int, string>,
     *     merchantProfile?: string,
     *     frontendReturnUrl?: string,
     *     backendReturnUrl?: string,
     *     request3DS?: string,
     *     paymentExpiry?: string,
     *     userDefined1?: string,
     *     userDefined2?: string,
     *     userDefined3?: string,
     *     userDefined4?: string,
     *     userDefined5?: string
     * }  $data
     * @return array{
     *     webPaymentUrl: string,
     *     paymentToken: string,
     *     respCode: string,
     *     respDesc: string,
     *     merchantProfile: string,
     *     merchantID: string,
     *     raw: array
     * }
     */
    public function createPaymentToken(array $data): array
    {
        $channels = $data['paymentChannel'] ?? $this->defaultPaymentChannels;
        $profile = $data['merchantProfile'] ?? $this->resolveMerchantProfile($channels);
        [$merchantId, $secretKey] = $this->credentialsFor($profile);

        $payload = array_filter([
            'merchantID' => $merchantId,
            'invoiceNo' => (string) $data['invoiceNo'],
            'description' => (string) $data['description'],
            'amount' => round((float) $data['amount'], 2),
            'currencyCode' => $data['currencyCode'] ?? $this->currencyCode,
            'paymentChannel' => $channels,
            'frontendReturnUrl' => $data['frontendReturnUrl'] ?? null,
            'backendReturnUrl' => $data['backendReturnUrl'] ?? null,
            'request3DS' => $data['request3DS'] ?? 'Y',
            'paymentExpiry' => $data['paymentExpiry'] ?? null,
            'userDefined1' => $data['userDefined1'] ?? null,
            'userDefined2' => $data['userDefined2'] ?? null,
            'userDefined3' => $data['userDefined3'] ?? null,
            'userDefined4' => $data['userDefined4'] ?? $profile,
            'userDefined5' => $data['userDefined5'] ?? null,
        ], fn ($value) => $value !== null && $value !== '');

        if (isset($payload['paymentChannel']) && $payload['paymentChannel'] === []) {
            unset($payload['paymentChannel']);
        }

        $decoded = $this->request('/payment/4.3/paymentToken', $payload, $secretKey);

        if (($decoded['respCode'] ?? null) !== '0000') {
            throw new TwoC2PException(
                $decoded['respDesc'] ?? '2C2P payment token request failed',
                $decoded['respCode'] ?? null,
                $decoded
            );
        }

        if (empty($decoded['webPaymentUrl'])) {
            throw new TwoC2PException('2C2P response missing webPaymentUrl', $decoded['respCode'] ?? null, $decoded);
        }

        return [
            'webPaymentUrl' => $decoded['webPaymentUrl'],
            'paymentToken' => $decoded['paymentToken'] ?? '',
            'respCode' => $decoded['respCode'],
            'respDesc' => $decoded['respDesc'] ?? 'Success',
            'merchantProfile' => $profile,
            'merchantID' => $merchantId,
            'raw' => $decoded,
        ];
    }

    /**
     * Shortcut: create token and return only the hosted checkout URL.
     */
    public function createHostedPaymentUrl(array $data): string
    {
        return $this->createPaymentToken($data)['webPaymentUrl'];
    }

    /**
     * Payment Inquiry API.
     *
     * @return array<string, mixed>
     */
    public function inquirePayment(string $invoiceNo, ?string $merchantProfile = null, ?string $paymentToken = null): array
    {
        $profile = $merchantProfile ?? self::PROFILE_CREDIT;
        [$merchantId, $secretKey] = $this->credentialsFor($profile);

        $payload = array_filter([
            'merchantID' => $merchantId,
            'invoiceNo' => $paymentToken ? null : $invoiceNo,
            'paymentToken' => $paymentToken,
        ], fn ($value) => $value !== null && $value !== '');

        return $this->request('/payment/4.3/paymentInquiry', $payload, $secretKey);
    }

    /**
     * เก็บ paymentToken ไว้ inquiry ตอน callback (โดยเฉพาะตอน backend เข้า local ไม่ได้)
     */
    public function rememberPaymentToken(string $invoiceNo, string $paymentToken, ?string $transactionId = null): void
    {
        $ttl = now()->addHours(6);
        if ($invoiceNo !== '') {
            Cache::put($this->paymentTokenCacheKey($invoiceNo), $paymentToken, $ttl);
        }
        if ($transactionId) {
            Cache::put($this->paymentTokenCacheKey('tx:' . $transactionId), $paymentToken, $ttl);
        }
    }

    public function recallPaymentToken(?string $invoiceNo = null, ?string $transactionId = null): ?string
    {
        if ($invoiceNo) {
            $token = Cache::get($this->paymentTokenCacheKey($invoiceNo));
            if (is_string($token) && $token !== '') {
                return $token;
            }
        }
        if ($transactionId) {
            $token = Cache::get($this->paymentTokenCacheKey('tx:' . $transactionId));
            if (is_string($token) && $token !== '') {
                return $token;
            }
        }

        return null;
    }

    protected function paymentTokenCacheKey(string $key): string
    {
        return '2c2p:payment_token:' . $key;
    }

    /**
     * Whether a response code means payment success / approved.
     */
    public function isPaymentSuccessful(?string $respCode): bool
    {
        return (string) $respCode === '0000';
    }

    /**
     * Frontend มักคืน 2000 = completed แล้วให้ inquiry เพื่อได้สถานะเต็ม
     */
    public function needsPaymentInquiry(?string $respCode): bool
    {
        return in_array((string) $respCode, ['2000', '2001'], true);
    }

    /**
     * ยืนยันผลการชำระหลัง frontend/backend callback
     * - 0000 = สำเร็จทันที
     * - 2000/2001 = inquiry (+ retry) จนได้สถานะจริง
     * - ถ้า frontend เป็น 2000 แต่ inquiry ได้ 2002 ซ้ำๆ → ถือว่าสำเร็จ (ตาม flow hosted payment)
     *
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    public function resolveCompletedPayment(
        array $payload,
        ?string $preferredProfile = null,
        ?string $paymentToken = null
    ): array {
        $respCode = (string) ($payload['respCode'] ?? '');
        if ($this->isPaymentSuccessful($respCode)) {
            return $payload;
        }

        if (!$this->needsPaymentInquiry($respCode)) {
            return $payload;
        }

        $invoiceNo = (string) ($payload['invoiceNo'] ?? '');
        $transactionId = (string) ($payload['userDefined3'] ?? '');
        $paymentToken = $paymentToken
            ?: $this->recallPaymentToken($invoiceNo !== '' ? $invoiceNo : null, $transactionId !== '' ? $transactionId : null);

        $profiles = array_values(array_unique(array_filter([
            $preferredProfile,
            isset($payload['userDefined4']) ? (string) $payload['userDefined4'] : null,
            self::PROFILE_CREDIT,
            self::PROFILE_ETC,
        ])));

        $attempts = 5;
        $lastInquiry = null;

        for ($attempt = 1; $attempt <= $attempts; $attempt++) {
            if ($attempt > 1) {
                usleep(700000);
            }

            foreach ($profiles as $profile) {
                try {
                    $inquiry = $this->inquirePayment($invoiceNo, $profile, $paymentToken);
                    $lastInquiry = $inquiry;
                    $code = (string) ($inquiry['respCode'] ?? '');

                    Log::info('2C2P payment inquiry attempt', [
                        'invoiceNo' => $invoiceNo,
                        'profile' => $profile,
                        'attempt' => $attempt,
                        'respCode' => $code,
                        'usedPaymentToken' => $paymentToken !== null && $paymentToken !== '',
                    ]);

                    // สำเร็จ / ยกเลิก / fail ชัดเจน
                    if ($this->isPaymentSuccessful($code) || in_array($code, ['0003', '2003'], true)) {
                        return array_merge($payload, $inquiry);
                    }

                    // 0001 pending / 2001 in progress / 2002 not found yet → retry
                } catch (\Throwable $e) {
                    Log::warning('2C2P payment inquiry failed', [
                        'invoiceNo' => $invoiceNo,
                        'profile' => $profile,
                        'attempt' => $attempt,
                        'message' => $e->getMessage(),
                    ]);
                }
            }
        }

        // Frontend บอก completed (2000) แต่ inquiry หาไม่เจอ/ไม่จบ — อนุมัติตาม frontend
        if ($respCode === '2000') {
            Log::warning('2C2P inquiry inconclusive after frontend 2000; treating as success', [
                'invoiceNo' => $invoiceNo,
                'lastInquiryRespCode' => $lastInquiry['respCode'] ?? null,
            ]);

            return array_merge($payload, $lastInquiry ?? [], [
                'respCode' => '0000',
                'respDesc' => 'Approved from frontend completion (inquiry inconclusive)',
                'approvedViaFrontend2000' => true,
            ]);
        }

        return $lastInquiry ? array_merge($payload, $lastInquiry) : $payload;
    }

    /**
     * เลือก merchant profile จาก payment channels
     * - มีแค่ช่องทางบัตร → credit
     * - นอกนั้น → etc
     *
     * @param  array<int, string>|null  $channels
     */
    public function resolveMerchantProfile(?array $channels = null): string
    {
        $channels = array_values(array_filter(array_map(
            fn ($c) => strtoupper(trim((string) $c)),
            $channels ?? $this->defaultPaymentChannels
        )));

        if ($channels === []) {
            return self::PROFILE_CREDIT;
        }

        $allCredit = collect($channels)->every(
            fn (string $channel) => in_array($channel, $this->creditChannels, true)
        );

        return $allCredit ? self::PROFILE_CREDIT : self::PROFILE_ETC;
    }

    /**
     * @return array{0: string, 1: string} [merchantId, secretKey]
     */
    public function credentialsFor(string $profile): array
    {
        $profile = strtolower($profile);

        if ($profile === self::PROFILE_ETC) {
            $merchantId = trim((string) (
                config('twoc2p.merchant_id_etc')
                ?: config('twoc2p.merchant_id')
            ));
            $secretKey = trim((string) (
                config('twoc2p.secret_key_etc')
                ?: config('twoc2p.secret_key')
            ));
            $label = 'TWOC2P_MERCHANT_ID_ETC / MERCHANT_ID_ETC';
        } else {
            $merchantId = trim((string) (
                config('twoc2p.merchant_id_credit')
                ?: config('twoc2p.merchant_id')
            ));
            $secretKey = trim((string) (
                config('twoc2p.secret_key_credit')
                ?: config('twoc2p.secret_key')
            ));
            $label = 'TWOC2P_MERCHANT_ID_CREDIT / MERCHANT_ID_CREDIT';
        }

        if ($merchantId === '') {
            throw new TwoC2PException("ยังไม่ได้กำหนด {$label} ใน .env");
        }

        if ($secretKey === '') {
            throw new TwoC2PException(
                'ยังไม่ได้กำหนด TWOC2P_SECRET_KEY (หรือ SECRET_KEY_CREDIT / SECRET_KEY_ETC) ใน .env'
            );
        }

        return [$merchantId, $secretKey];
    }

    /**
     * Encode payload as JWT (HS256) for 2C2P API.
     *
     * @param  array<string, mixed>  $payload
     */
    public function encodeJwt(array $payload, ?string $secretKey = null): string
    {
        $secretKey ??= $this->defaultSecretKey();
        $header = ['alg' => 'HS256', 'typ' => 'JWT'];

        $segments = [
            $this->base64UrlEncode(json_encode($header, JSON_UNESCAPED_SLASHES)),
            $this->base64UrlEncode(json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)),
        ];

        $signingInput = implode('.', $segments);
        $signature = hash_hmac('sha256', $signingInput, $secretKey, true);
        $segments[] = $this->base64UrlEncode($signature);

        return implode('.', $segments);
    }

    /**
     * Decode and verify JWT from 2C2P response / callback.
     * ลอง secret ของ credit และ etc ตามลำดับ (callback อาจมาจาก merchant คนละตัว)
     *
     * @return array<string, mixed>
     */
    public function decodeJwt(string $jwt, ?string $secretKey = null): array
    {
        $secrets = $secretKey
            ? [$secretKey]
            : array_values(array_unique(array_filter([
                trim((string) config('twoc2p.secret_key_credit')),
                trim((string) config('twoc2p.secret_key_etc')),
                trim((string) config('twoc2p.secret_key')),
            ])));

        if ($secrets === []) {
            throw new TwoC2PException('ยังไม่ได้กำหนด TWOC2P_SECRET_KEY ใน .env');
        }

        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            throw new TwoC2PException('Invalid JWT format from 2C2P');
        }

        [$headerB64, $payloadB64, $signatureB64] = $parts;
        $signingInput = $headerB64 . '.' . $payloadB64;
        $lastError = null;

        foreach ($secrets as $secret) {
            $expected = $this->base64UrlEncode(hash_hmac('sha256', $signingInput, $secret, true));
            if (!hash_equals($expected, $signatureB64)) {
                $lastError = 'Invalid JWT signature from 2C2P';
                continue;
            }

            $json = $this->base64UrlDecode($payloadB64);
            $decoded = json_decode($json, true);

            if (!is_array($decoded)) {
                throw new TwoC2PException('Unable to decode JWT payload from 2C2P');
            }

            return $decoded;
        }

        throw new TwoC2PException($lastError ?? 'Invalid JWT signature from 2C2P');
    }

    /**
     * Decode payload จาก frontend/backend callback
     * รองรับ: JWT, Base64URL JSON, raw JSON
     *
     * @return array<string, mixed>
     */
    public function decodeRequestPayload(?string $payload): array
    {
        if ($payload === null || trim($payload) === '') {
            throw new TwoC2PException('Missing 2C2P payload');
        }

        $payload = trim(rawurldecode(trim($payload)));

        // JWT (header.payload.signature)
        if (substr_count($payload, '.') === 2) {
            return $this->decodeJwt($payload);
        }

        // Base64 / Base64URL encoded JSON (frontend response แบบนี้บ่อย)
        try {
            $decoded = $this->base64UrlDecode($payload);
            $json = json_decode($decoded, true);
            if (is_array($json)) {
                return $json;
            }
        } catch (\Throwable $e) {
            // continue
        }

        // Raw JSON
        $json = json_decode($payload, true);
        if (is_array($json)) {
            return $json;
        }

        throw new TwoC2PException('Invalid payment response format from 2C2P');
    }

    /**
     * Build a unique invoice number suitable for 2C2P (max 50 chars).
     */
    public function makeInvoiceNo(string $prefix = 'INV'): string
    {
        $prefix = preg_replace('/[^A-Za-z0-9]/', '', $prefix) ?: 'INV';
        $suffix = strtoupper(bin2hex(random_bytes(4))) . now()->format('ymdHis');

        return substr($prefix . $suffix, 0, 50);
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    protected function request(string $endpoint, array $payload, string $secretKey): array
    {
        $jwt = $this->encodeJwt($payload, $secretKey);

        $response = Http::timeout($this->timeout)
            ->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'text/plain',
            ])
            ->withBody(json_encode(['payload' => $jwt], JSON_UNESCAPED_SLASHES), 'application/json')
            ->post($this->baseUrl . $endpoint);

        $rawBody = $response->body();

        if (!$response->successful()) {
            Log::warning('2C2P HTTP error', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'body' => $rawBody,
            ]);

            throw new TwoC2PException(
                '2C2P HTTP error: ' . $response->status(),
                null,
                ['body' => $rawBody]
            );
        }

        $body = json_decode($rawBody, true);

        if (is_array($body) && !empty($body['payload']) && is_string($body['payload'])) {
            return $this->decodeJwt($body['payload'], $secretKey);
        }

        if (is_array($body) && isset($body['respCode'])) {
            throw new TwoC2PException(
                $body['respDesc'] ?? ('2C2P error ' . $body['respCode']),
                (string) $body['respCode'],
                $body
            );
        }

        if (is_string($rawBody) && substr_count($rawBody, '.') === 2) {
            return $this->decodeJwt(trim($rawBody, "\" \n\r\t"), $secretKey);
        }

        Log::warning('2C2P unexpected response', [
            'endpoint' => $endpoint,
            'body' => $rawBody,
        ]);

        throw new TwoC2PException('2C2P response missing payload', null, [
            'body' => $rawBody,
        ]);
    }

    protected function defaultSecretKey(): string
    {
        $secret = trim((string) (
            config('twoc2p.secret_key_credit')
            ?: config('twoc2p.secret_key_etc')
            ?: config('twoc2p.secret_key')
        ));

        if ($secret === '') {
            throw new TwoC2PException('ยังไม่ได้กำหนด TWOC2P_SECRET_KEY ใน .env');
        }

        return $secret;
    }

    protected function base64UrlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    protected function base64UrlDecode(string $data): string
    {
        $remainder = strlen($data) % 4;
        if ($remainder > 0) {
            $data .= str_repeat('=', 4 - $remainder);
        }

        $decoded = base64_decode(strtr($data, '-_', '+/'), true);
        if ($decoded === false) {
            throw new TwoC2PException('Invalid base64url data in JWT');
        }

        return $decoded;
    }
}
