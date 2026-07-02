<?php

declare(strict_types=1);

namespace Modules\IAM\Infrastructure\Persistence;

use Modules\IAM\Domain\User\Entities\User;

/**
 * Thin guard that answers "can user X do Y in company Z?"
 */
final class RbacGuard
{
    public function check(User $user, string $permission, int $companyId): bool
    {
        return $user->roles()
            ->where('user_role.company_id', $companyId)
            ->where(function ($q): void {
                $q->whereNull('user_role.expires_at')
                  ->orWhere('user_role.expires_at', '>', now());
            })
            ->whereHas('permissions', fn ($p) => $p->where('slug', $permission))
            ->exists();
    }

    public function checkAny(User $user, array $permissions, int $companyId): bool
    {
        foreach ($permissions as $permission) {
            if ($this->check($user, $permission, $companyId)) {
                return true;
            }
        }

        return false;
    }
}
