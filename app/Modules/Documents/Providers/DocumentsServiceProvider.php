<?php

declare(strict_types=1);

namespace App\Modules\Documents\Providers;

use App\Modules\Documents\Infrastructure\Repositories\LetterRepository;
use Illuminate\Support\ServiceProvider;

final class DocumentsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(LetterRepository::class);
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Presentation/Routes/web.php');
    }
}
