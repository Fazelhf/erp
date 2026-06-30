<?php

declare(strict_types=1);

namespace Modules\IAM\Domain\Role\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\IAM\Domain\Permission\Entities\Permission;
use Modules\IAM\Domain\User\Entities\User;

class Role extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'slug',
        'description',
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permission');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_role')
            ->withPivot(['company_id', 'expires_at'])
            ->withTimestamps();
    }

    public function can(string $permissionSlug): bool
    {
        return $this->permissions->contains('slug', $permissionSlug);
    }
}
