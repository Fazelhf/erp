<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\IAM\Domain\Permission\Entities\Permission;
use Modules\IAM\Domain\Role\Entities\Role;

class RoleSeeder extends Seeder
{
    private const ROLES = [
        'super_admin' => [
            'name'        => 'مدیر ارشد',
            'description' => 'دسترسی کامل به تمام بخش‌های سیستم',
            'permissions' => '*',
        ],
        'admin' => [
            'name'        => 'مدیر سیستم',
            'description' => 'دسترسی به اکثر بخش‌های سیستم به جز تنظیمات امنیتی',
            'permissions' => [
                'organization.company.view', 'organization.company.edit',
                'organization.branches.manage', 'organization.departments.manage',
                'iam.users.view', 'iam.users.manage', 'iam.roles.view', 'iam.roles.manage',
                'crm.customers.view', 'crm.customers.create', 'crm.customers.edit', 'crm.customers.delete',
                'inventory.products.view', 'inventory.products.create', 'inventory.products.edit', 'inventory.products.delete',
                'accounting.invoices.view', 'accounting.invoices.create', 'accounting.invoices.mark_paid', 'accounting.invoices.delete',
                'accounting.payments.view', 'accounting.payments.create', 'accounting.journal.view',
                'hrm.leave.request', 'hrm.leave.approve', 'hrm.leave.view_all',
                'workflow.view', 'workflow.advance', 'workflow.definitions.manage',
                'reporting.view', 'reporting.create', 'reporting.export',
                'settings.view', 'settings.edit',
                'audit.logs.view', 'search.global',
            ],
        ],
        'accountant' => [
            'name'        => 'حسابدار',
            'description' => 'دسترسی به ماژول حسابداری و گزارش‌های مالی',
            'permissions' => [
                'crm.customers.view',
                'inventory.products.view',
                'accounting.invoices.view', 'accounting.invoices.create', 'accounting.invoices.mark_paid',
                'accounting.payments.view', 'accounting.payments.create', 'accounting.journal.view',
                'reporting.view', 'reporting.create', 'reporting.export',
                'search.global',
            ],
        ],
        'sales' => [
            'name'        => 'فروشنده',
            'description' => 'دسترسی به CRM، محصولات و فاکتورها',
            'permissions' => [
                'crm.customers.view', 'crm.customers.create', 'crm.customers.edit',
                'inventory.products.view',
                'accounting.invoices.view', 'accounting.invoices.create',
                'accounting.payments.view',
                'reporting.view',
                'search.global',
            ],
        ],
        'hr_manager' => [
            'name'        => 'مدیر منابع انسانی',
            'description' => 'مدیریت درخواست‌های مرخصی و گزارش‌های پرسنلی',
            'permissions' => [
                'iam.users.view',
                'hrm.leave.request', 'hrm.leave.approve', 'hrm.leave.view_all',
                'workflow.view', 'workflow.advance',
                'reporting.view', 'reporting.create',
                'search.global',
            ],
        ],
        'employee' => [
            'name'        => 'کارمند',
            'description' => 'دسترسی پایه — مشاهده و درخواست مرخصی',
            'permissions' => [
                'hrm.leave.request',
                'reporting.view',
                'search.global',
            ],
        ],
        'viewer' => [
            'name'        => 'بازدیدکننده',
            'description' => 'دسترسی فقط خواندنی به تمام بخش‌ها',
            'permissions' => [
                'organization.company.view',
                'crm.customers.view',
                'inventory.products.view',
                'accounting.invoices.view', 'accounting.payments.view', 'accounting.journal.view',
                'hrm.leave.view_all',
                'workflow.view',
                'reporting.view',
                'settings.view',
                'audit.logs.view',
                'search.global',
            ],
        ],
    ];

    public function run(): void
    {
        $companyId   = cache()->get('seed.company_id');
        $allPerms    = Permission::pluck('id', 'slug');

        foreach (self::ROLES as $slug => $config) {
            $role = Role::updateOrCreate(
                ['slug' => $slug, 'company_id' => $companyId],
                [
                    'name'        => $config['name'],
                    'description' => $config['description'],
                    'is_system'   => true,
                    'company_id'  => $companyId,
                ]
            );

            if ($config['permissions'] === '*') {
                $role->permissions()->sync($allPerms->values()->all());
            } else {
                $permIds = collect($config['permissions'])
                    ->map(fn (string $s) => $allPerms->get($s))
                    ->filter()
                    ->values()
                    ->all();
                $role->permissions()->sync($permIds);
            }
        }

        $this->command->info('✓ ' . Role::count() . ' roles seeded with permissions.');
    }
}
