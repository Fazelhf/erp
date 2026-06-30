<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Modules\IAM\Domain\User\Entities\User;
use Tests\TestCase;

abstract class ApiTestCase extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected int $companyId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->companyId = DB::table('companies')->insertGetId([
            'tenant_id'  => 1,
            'name'       => 'Test Company',
            'legal_name' => 'Test Company LLC',
            'currency'   => 'IRR',
            'is_active'  => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->user = User::factory()->create([
            'company_id' => $this->companyId,
        ]);
    }

    protected function actingAsApi(User $user = null): static
    {
        $user ??= $this->user;
        $token = $user->createToken('test-token')->plainTextToken;

        $this->withHeader('Authorization', "Bearer {$token}");
        $this->withHeader('Accept', 'application/json');

        return $this;
    }

    protected function jsonV1(string $method, string $uri, array $data = []): \Illuminate\Testing\TestResponse
    {
        return $this->json($method, "/api/v1/{$uri}", $data);
    }
}
