<?php

declare(strict_types=1);

namespace Modules\Settings\Application\Services;

use Modules\Settings\Domain\Setting\Entities\Setting;

final class SettingsService
{
    public function get(int $companyId, string $group, string $key, mixed $default = null): mixed
    {
        $setting = Setting::query()
            ->where('company_id', $companyId)
            ->where('group', $group)
            ->where('key', $key)
            ->first();

        return $setting ? $setting->typedValue() : $default;
    }

    public function set(int $companyId, string $group, string $key, mixed $value): void
    {
        $type = match (true) {
            is_bool($value)   => 'boolean',
            is_int($value)    => 'integer',
            is_float($value)  => 'float',
            is_array($value)  => 'array',
            default           => 'string',
        };

        $stored = is_array($value) ? json_encode($value) : (string) $value;

        Setting::updateOrCreate(
            ['company_id' => $companyId, 'group' => $group, 'key' => $key],
            ['value' => $stored, 'type' => $type],
        );
    }

    public function getGroup(int $companyId, string $group): array
    {
        return Setting::query()
            ->where('company_id', $companyId)
            ->where('group', $group)
            ->get()
            ->mapWithKeys(fn (Setting $s) => [$s->key => $s->typedValue()])
            ->all();
    }
}
