<?php

declare(strict_types=1);

namespace App\Modules\CRM\Providers;

use App\Modules\CRM\Infrastructure\Repositories\CustomerRepository;
use Illuminate\Support\ServiceProvider;

final class CRMServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CustomerRepository::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Presentation/Routes/web.php');
    }
}
