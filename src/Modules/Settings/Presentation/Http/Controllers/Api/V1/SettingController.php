<?php

declare(strict_types=1);

namespace Modules\Settings\Presentation\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Settings\Application\Services\SettingsService;
use Shared\Presentation\Http\Controllers\ApiController;

final class SettingController extends ApiController
{
    public function __construct(private readonly SettingsService $settings) {}

    public function index(Request $request): JsonResponse
    {
        $group    = $request->input('group', 'general');
        $settings = $this->settings->getGroup($request->user()->company_id, $group);

        return $this->ok($settings);
    }

    public function update(Request $request): JsonResponse
    {
        $data = $request->validate([
            'group'    => ['sometimes', 'string', 'max:100'],
            'settings' => ['required', 'array'],
            'settings.*.key'   => ['required', 'string'],
            'settings.*.value' => ['nullable'],
        ]);

        $group = $data['group'] ?? 'general';

        foreach ($data['settings'] as $item) {
            $this->settings->set(
                companyId: $request->user()->company_id,
                group:     $group,
                key:       $item['key'],
                value:     $item['value'],
            );
        }

        return $this->ok(null, 'Settings saved');
    }
}
