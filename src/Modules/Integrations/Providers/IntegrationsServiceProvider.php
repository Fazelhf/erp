<?php

declare(strict_types=1);

namespace Modules\Integrations\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Integrations\Domain\Channel\Contracts\PaymentGatewayInterface;
use Modules\Integrations\Infrastructure\Channels\Payment\ZarinpalGateway;
use Modules\Integrations\Infrastructure\Channels\Sms\KavenegarSmsDriver;

class IntegrationsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(PaymentGatewayInterface::class, fn () =>
            new ZarinpalGateway(config('integrations.zarinpal', []))
        );

        $this->app->singleton(KavenegarSmsDriver::class, fn () =>
            new KavenegarSmsDriver(config('integrations.kavenegar', []))
        );
    }

    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/integrations.php', 'integrations');
    }
}
