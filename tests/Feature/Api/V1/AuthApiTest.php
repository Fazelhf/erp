<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\IAM\Domain\User\Entities\User;
use Tests\TestCase;

final class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(array $attrs = []): User
    {
        return User::factory()->create(array_merge(['company_id' => null], $attrs));
    }

    public function test_login_with_valid_credentials_returns_token(): void
    {
        $this->makeUser([
            'email'    => 'test@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $this->postJson('/api/v1/auth/login', [
            'email'    => 'test@example.com',
            'password' => 'secret123',
        ])
            ->assertOk()
            ->assertJsonStructure([
                'success',
                'data' => ['token', 'user' => ['id', 'name', 'email']],
            ]);
    }

    public function test_login_with_wrong_password_returns_401(): void
    {
        $this->makeUser(['email' => 'test@example.com']);

        $this->postJson('/api/v1/auth/login', [
            'email'    => 'test@example.com',
            'password' => 'wrong-password',
        ])->assertUnauthorized();
    }

    public function test_login_validation_requires_email(): void
    {
        $this->postJson('/api/v1/auth/login', ['password' => 'secret'])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');
    }

    public function test_me_returns_authenticated_user(): void
    {
        $user  = $this->makeUser();
        $token = $user->createToken('test')->plainTextToken;

        $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/v1/auth/me')
            ->assertOk()
            ->assertJsonPath('data.email', $user->email);
    }

    public function test_me_without_token_returns_401(): void
    {
        $this->getJson('/api/v1/auth/me')->assertUnauthorized();
    }

    public function test_logout_revokes_token(): void
    {
        $user  = $this->makeUser();
        $token = $user->createToken('test')->plainTextToken;

        $this->withHeader('Authorization', "Bearer {$token}")
            ->postJson('/api/v1/auth/logout')
            ->assertOk();

        $this->assertSame(0, $user->tokens()->count());
    }
}
