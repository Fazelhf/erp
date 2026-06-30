<?php

declare(strict_types=1);

namespace Modules\IAM\Domain\Permission\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\IAM\Domain\Role\Entities\Role;

class Permission extends Model
{
    protected $fillable = [
        'module',
        'name',
        'slug',
        'description',
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }
}
