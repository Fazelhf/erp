<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\IAM\Domain\Role\Entities\Role;
use Modules\IAM\Domain\User\Entities\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $companyId = cache()->get('seed.company_id');

        // ── Super Admin ───────────────────────────────────────────────────────
        $admin = User::updateOrCreate(
            ['email' => 'admin@erp.local'],
            [
                'name'       => 'مدیر ارشد سیستم',
                'password'   => Hash::make('Admin@1234'),
                'company_id' => $companyId,
                'status'     => 'active',
                'locale'     => 'fa',
                'timezone'   => 'Asia/Tehran',
            ]
        );

        $superAdminRole = Role::where('slug', 'super_admin')
            ->where('company_id', $companyId)
            ->first();

        if ($superAdminRole) {
            $admin->roles()->syncWithoutDetaching([
                $superAdminRole->id => ['company_id' => $companyId],
            ]);
        }

        // ── Demo Users ────────────────────────────────────────────────────────
        $demoUsers = [
            [
                'name'  => 'علی احمدی (حسابدار)',
                'email' => 'accountant@erp.local',
                'role'  => 'accountant',
            ],
            [
                'name'  => 'فاطمه رضایی (فروشنده)',
                'email' => 'sales@erp.local',
                'role'  => 'sales',
            ],
            [
                'name'  => 'محمد حسینی (منابع انسانی)',
                'email' => 'hr@erp.local',
                'role'  => 'hr_manager',
            ],
            [
                'name'  => 'زهرا کریمی (کارمند)',
                'email' => 'employee@erp.local',
                'role'  => 'employee',
            ],
        ];

        foreach ($demoUsers as $userData) {
            $user = User::updateOrCreate(
                ['email' => $userData['email']],
                [
                    'name'       => $userData['name'],
                    'password'   => Hash::make('Demo@1234'),
                    'company_id' => $companyId,
                    'status'     => 'active',
                    'locale'     => 'fa',
                    'timezone'   => 'Asia/Tehran',
                ]
            );

            $role = Role::where('slug', $userData['role'])
                ->where('company_id', $companyId)
                ->first();

            if ($role) {
                $user->roles()->syncWithoutDetaching([
                    $role->id => ['company_id' => $companyId],
                ]);
            }
        }

        $this->command->info('✓ Admin + 4 demo users seeded.');
        $this->command->table(
            ['Email', 'Password', 'Role'],
            [
                ['admin@erp.local',      'Admin@1234', 'مدیر ارشد'],
                ['accountant@erp.local', 'Demo@1234',  'حسابدار'],
                ['sales@erp.local',      'Demo@1234',  'فروشنده'],
                ['hr@erp.local',         'Demo@1234',  'منابع انسانی'],
                ['employee@erp.local',   'Demo@1234',  'کارمند'],
            ]
        );
    }
}
