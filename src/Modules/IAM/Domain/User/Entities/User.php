<?php

declare(strict_types=1);

namespace Modules\IAM\Domain\User\Entities;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\IAM\Domain\Role\Entities\Role;
use Modules\IAM\Domain\User\Enums\UserStatusEnum;
use Shared\Domain\Models\Concerns\HasCompanyScope;

class User extends Authenticatable
{
    use HasApiTokens, HasCompanyScope, HasFactory, Notifiable;

    protected static function newFactory(): Factory
    {
        return UserFactory::new();
    }

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'password',
        'status',
        'avatar',
        'locale',
        'timezone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'status'            => UserStatusEnum::class,
        ];
    }

    public function roles(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_role')
            ->withPivot(['company_id', 'expires_at'])
            ->withTimestamps();
    }

    public function hasPermission(string $slug): bool
    {
        return $this->roles->contains(fn (Role $role) => $role->can($slug));
    }

    public function isActive(): bool
    {
        return $this->status === UserStatusEnum::Active;
    }
}
