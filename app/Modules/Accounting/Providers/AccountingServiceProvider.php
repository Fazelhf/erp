<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Providers;

use App\Modules\Accounting\Infrastructure\Repositories\InvoiceRepository;
use Illuminate\Support\ServiceProvider;

final class AccountingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(InvoiceRepository::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Presentation/Routes/web.php');
    }
}
