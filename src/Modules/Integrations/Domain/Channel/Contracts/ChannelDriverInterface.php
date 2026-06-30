<?php

declare(strict_types=1);

namespace Modules\Integrations\Domain\Channel\Contracts;

interface ChannelDriverInterface
{
    public function send(array $payload): bool;

    public function isConfigured(): bool;
}
