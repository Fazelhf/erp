<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkflowSeeder extends Seeder
{
    private const WORKFLOWS = [
        [
            'name'        => 'تایید فاکتور',
            'slug'        => 'invoice-approval',
            'applies_to'  => 'invoice',
            'is_active'   => true,
            'config'      => ['auto_approve_under' => 5000000],
            'steps'       => [
                [
                    'name'          => 'بررسی حسابدار',
                    'type'          => 'approval',
                    'order'         => 1,
                    'assignee_role' => 'accountant',
                    'is_required'   => true,
                    'conditions'    => ['amount_gte' => 0],
                ],
                [
                    'name'          => 'تایید مدیر مالی',
                    'type'          => 'approval',
                    'order'         => 2,
                    'assignee_role' => 'admin',
                    'is_required'   => true,
                    'conditions'    => ['amount_gte' => 10000000],
                ],
            ],
        ],
        [
            'name'        => 'تایید مرخصی',
            'slug'        => 'leave-approval',
            'applies_to'  => 'leave_request',
            'is_active'   => true,
            'config'      => [],
            'steps'       => [
                [
                    'name'          => 'تایید سرپرست مستقیم',
                    'type'          => 'approval',
                    'order'         => 1,
                    'assignee_role' => 'hr_manager',
                    'is_required'   => true,
                    'conditions'    => null,
                ],
                [
                    'name'          => 'تایید مدیر منابع انسانی',
                    'type'          => 'approval',
                    'order'         => 2,
                    'assignee_role' => 'hr_manager',
                    'is_required'   => true,
                    'conditions'    => ['days_gte' => 3],
                ],
            ],
        ],
        [
            'name'        => 'خرید و تدارکات',
            'slug'        => 'purchase-approval',
            'applies_to'  => 'purchase_order',
            'is_active'   => true,
            'config'      => [],
            'steps'       => [
                [
                    'name'          => 'بررسی کارشناس خرید',
                    'type'          => 'approval',
                    'order'         => 1,
                    'assignee_role' => 'employee',
                    'is_required'   => true,
                    'conditions'    => null,
                ],
                [
                    'name'          => 'تایید مدیر بخش',
                    'type'          => 'approval',
                    'order'         => 2,
                    'assignee_role' => 'admin',
                    'is_required'   => true,
                    'conditions'    => ['amount_gte' => 5000000],
                ],
                [
                    'name'          => 'تایید مدیر ارشد',
                    'type'          => 'approval',
                    'order'         => 3,
                    'assignee_role' => 'super_admin',
                    'is_required'   => false,
                    'conditions'    => ['amount_gte' => 50000000],
                ],
            ],
        ],
        [
            'name'        => 'اضافه کاری',
            'slug'        => 'overtime-approval',
            'applies_to'  => 'overtime_request',
            'is_active'   => true,
            'config'      => [],
            'steps'       => [
                [
                    'name'          => 'تایید سرپرست',
                    'type'          => 'approval',
                    'order'         => 1,
                    'assignee_role' => 'hr_manager',
                    'is_required'   => true,
                    'conditions'    => null,
                ],
            ],
        ],
    ];

    public function run(): void
    {
        $companyId = cache()->get('seed.company_id');

        foreach (self::WORKFLOWS as $workflow) {
            $definitionId = DB::table('workflow_definitions')->insertGetId([
                'company_id'  => $companyId,
                'name'        => $workflow['name'],
                'slug'        => $workflow['slug'],
                'applies_to'  => $workflow['applies_to'],
                'is_active'   => $workflow['is_active'],
                'config'      => json_encode($workflow['config']),
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);

            foreach ($workflow['steps'] as $step) {
                DB::table('workflow_steps')->insert([
                    'workflow_definition_id' => $definitionId,
                    'name'                   => $step['name'],
                    'type'                   => $step['type'],
                    'order'                  => $step['order'],
                    'assignee_role'          => $step['assignee_role'],
                    'is_required'            => $step['is_required'],
                    'conditions'             => json_encode($step['conditions']),
                    'created_at'             => now(),
                    'updated_at'             => now(),
                ]);
            }
        }

        $this->command->info('✓ ' . count(self::WORKFLOWS) . ' workflow definitions seeded.');
    }
}
