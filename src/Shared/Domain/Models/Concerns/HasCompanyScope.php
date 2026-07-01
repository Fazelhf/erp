<?php

declare(strict_types=1);

namespace Shared\Domain\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Adds a global scope that automatically filters by the authenticated user's company_id.
 * Only active during HTTP requests where a user is authenticated.
 * Console commands, queue jobs, and seeders bypass this scope automatically.
 *
 * Use Model::withoutGlobalScopes() or Model::withoutGlobalScope('company') when you
 * need cross-company access (e.g., system admins, internal handlers).
 */
trait HasCompanyScope
{
    public static function bootHasCompanyScope(): void
    {
        static::addGlobalScope('company', function (Builder $builder): void {
            if (app()->runningInConsole()) {
                return;
            }

            $user = auth()->user();

            if ($user && $user->company_id) {
                $builder->where(
                    (new static())->getTable() . '.company_id',
                    $user->company_id
                );
            }
        });
    }
}
