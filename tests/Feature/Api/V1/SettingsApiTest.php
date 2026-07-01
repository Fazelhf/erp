<?php

declare(strict_types=1);

namespace Tests\Feature\Api\V1;

final class SettingsApiTest extends ApiTestCase
{
    public function test_index_returns_empty_group_initially(): void
    {
        $this->actingAsApi()
            ->jsonV1('GET', 'settings?group=general')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data', []);
    }

    public function test_update_stores_settings(): void
    {
        $this->actingAsApi()
            ->jsonV1('PUT', 'settings', [
                'group'    => 'general',
                'settings' => [
                    ['key' => 'company_name', 'value' => 'شرکت نمونه'],
                    ['key' => 'currency',     'value' => 'IRR'],
                ],
            ])
            ->assertOk()
            ->assertJsonPath('message', 'Settings saved');
    }

    public function test_settings_are_retrievable_after_saving(): void
    {
        $this->actingAsApi();

        $this->jsonV1('PUT', 'settings', [
            'group'    => 'invoice',
            'settings' => [
                ['key' => 'default_due_days', 'value' => '30'],
            ],
        ]);

        $this->jsonV1('GET', 'settings?group=invoice')
            ->assertOk()
            ->assertJsonPath('data.default_due_days', '30');
    }

    public function test_update_validates_settings_array(): void
    {
        $this->actingAsApi()
            ->jsonV1('PUT', 'settings', [
                'group' => 'general',
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('settings');
    }

    public function test_update_validates_each_setting_has_key(): void
    {
        $this->actingAsApi()
            ->jsonV1('PUT', 'settings', [
                'settings' => [
                    ['value' => 'some_value'],
                ],
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('settings.0.key');
    }

    public function test_index_requires_auth(): void
    {
        $this->withHeader('Accept', 'application/json')
            ->getJson('/api/v1/settings')
            ->assertUnauthorized();
    }
}
