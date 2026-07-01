<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1;

final class InvoiceApiTest extends ApiTestCase
{
    public function test_index_requires_authentication(): void
    {
        $this->withHeader('Accept', 'application/json')
            ->getJson('/api/v1/accounting/invoices')
            ->assertUnauthorized();
    }

    public function test_index_returns_ok_for_authenticated_user(): void
    {
        $this->actingAsApi()
            ->jsonV1('GET', 'accounting/invoices')
            ->assertOk()
            ->assertJsonStructure(['success', 'data']);
    }

    public function test_store_validates_required_customer_id(): void
    {
        $this->actingAsApi()
            ->jsonV1('POST', 'accounting/invoices', [
                'issue_date' => '2026-01-01',
                'due_date'   => '2026-02-01',
                'items'      => [
                    ['description' => 'Item', 'quantity' => 1, 'unit_price' => 1000],
                ],
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('customer_id');
    }

    public function test_store_validates_due_date_after_issue_date(): void
    {
        $this->actingAsApi()
            ->jsonV1('POST', 'accounting/invoices', [
                'customer_id' => 1,
                'issue_date'  => '2026-02-01',
                'due_date'    => '2026-01-01',
                'items'       => [
                    ['description' => 'Item', 'quantity' => 1, 'unit_price' => 1000],
                ],
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('due_date');
    }

    public function test_store_validates_items_not_empty(): void
    {
        $this->actingAsApi()
            ->jsonV1('POST', 'accounting/invoices', [
                'customer_id' => 1,
                'issue_date'  => '2026-01-01',
                'due_date'    => '2026-02-01',
                'items'       => [],
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('items');
    }

    public function test_store_validates_item_unit_price(): void
    {
        $this->actingAsApi()
            ->jsonV1('POST', 'accounting/invoices', [
                'customer_id' => 1,
                'issue_date'  => '2026-01-01',
                'due_date'    => '2026-02-01',
                'items'       => [
                    ['description' => 'Item', 'quantity' => 1, 'unit_price' => -1],
                ],
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('items.0.unit_price');
    }
}
