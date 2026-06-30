<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1;

use Modules\IAM\Domain\User\Entities\User;

final class CustomerApiTest extends ApiTestCase
{
    public function test_index_requires_authentication(): void
    {
        $this->withHeader('Accept', 'application/json')
            ->getJson('/api/v1/crm/customers')
            ->assertUnauthorized();
    }

    public function test_index_returns_customers_for_company(): void
    {
        $this->actingAsApi()
            ->jsonV1('GET', 'crm/customers')
            ->assertOk()
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_store_creates_customer_with_valid_data(): void
    {
        $this->actingAsApi()
            ->jsonV1('POST', 'crm/customers', [
                'type'  => 'individual',
                'name'  => 'علی احمدی',
                'phone' => '09121234567',
                'email' => 'ali@example.com',
            ])
            ->assertCreated();
    }

    public function test_store_validates_required_type(): void
    {
        $this->actingAsApi()
            ->jsonV1('POST', 'crm/customers', [
                'name' => 'Test Customer',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('type');
    }

    public function test_store_validates_required_name(): void
    {
        $this->actingAsApi()
            ->jsonV1('POST', 'crm/customers', [
                'type' => 'individual',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('name');
    }

    public function test_store_validates_type_enum(): void
    {
        $this->actingAsApi()
            ->jsonV1('POST', 'crm/customers', [
                'type' => 'invalid_type',
                'name' => 'Test',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('type');
    }

    public function test_store_validates_email_format(): void
    {
        $this->actingAsApi()
            ->jsonV1('POST', 'crm/customers', [
                'type'  => 'individual',
                'name'  => 'Test',
                'email' => 'not-an-email',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');
    }
}
