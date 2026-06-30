<?php

declare(strict_types=1);

namespace Modules\Integrations\Infrastructure\Channels\Payment;

use Illuminate\Support\Facades\Http;
use Modules\Integrations\Domain\Channel\Contracts\PaymentGatewayInterface;

final class ZarinpalGateway implements PaymentGatewayInterface
{
    private const SANDBOX_URL = 'https://sandbox.zarinpal.com/pg/rest/WebGate';
    private const LIVE_URL    = 'https://api.zarinpal.com/pg/rest/WebGate';

    public function __construct(private readonly array $config) {}

    public function initiate(float $amount, string $currency, array $meta = []): array
    {
        $response = Http::post($this->baseUrl() . '/PaymentRequest.json', [
            'MerchantID'  => $this->config['merchant_id'],
            'Amount'      => (int) ($amount / 10),
            'Description' => $meta['description'] ?? 'پرداخت',
            'CallbackURL' => $meta['callback_url'],
            'Email'       => $meta['email'] ?? null,
            'Mobile'      => $meta['mobile'] ?? null,
        ])->json();

        return [
            'authority' => $response['Authority'] ?? null,
            'url'       => 'https://www.zarinpal.com/pg/StartPay/' . ($response['Authority'] ?? ''),
            'status'    => $response['Status'] ?? -1,
        ];
    }

    public function verify(string $transactionId): array
    {
        $response = Http::post($this->baseUrl() . '/PaymentVerification.json', [
            'MerchantID' => $this->config['merchant_id'],
            'Authority'  => $transactionId,
            'Amount'     => 0,
        ])->json();

        return [
            'ref_id'  => $response['RefID'] ?? null,
            'status'  => $response['Status'] ?? -1,
            'success' => in_array($response['Status'] ?? -1, [100, 101]),
        ];
    }

    public function refund(string $transactionId, float $amount): bool
    {
        return false;
    }

    private function baseUrl(): string
    {
        return ($this->config['sandbox'] ?? true) ? self::SANDBOX_URL : self::LIVE_URL;
    }
}
