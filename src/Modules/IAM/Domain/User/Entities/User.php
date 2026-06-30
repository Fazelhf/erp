<?php

declare(strict_types=1);

namespace Modules\IAM\Domain\User\Entities;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\IAM\Domain\User\Enums\UserStatusEnum;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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

    public function isActive(): bool
    {
        return $this->status === UserStatusEnum::Active;
    }
}
