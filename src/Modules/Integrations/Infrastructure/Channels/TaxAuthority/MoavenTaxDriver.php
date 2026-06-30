<?php

declare(strict_types=1);

namespace Modules\Integrations\Infrastructure\Channels\TaxAuthority;

use Illuminate\Support\Facades\Http;
use Modules\Integrations\Domain\Channel\Contracts\ChannelDriverInterface;

/**
 * سامانه مودیان (سازمان امور مالیاتی ایران) driver.
 */
final class MoavenTaxDriver implements ChannelDriverInterface
{
    public function __construct(private readonly array $config) {}

    public function send(array $payload): bool
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->config['token'],
            'Content-Type'  => 'application/json',
        ])->post($this->config['endpoint'] . '/invoices', $payload);

        return $response->successful();
    }

    public function isConfigured(): bool
    {
        return !empty($this->config['token']) && !empty($this->config['endpoint']);
    }
}
