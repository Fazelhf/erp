<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\IAM\Domain\Permission\Entities\Permission;

class PermissionSeeder extends Seeder
{
    private const PERMISSIONS = [
        // ── Organization ──────────────────────────────────────────────────────
        'organization' => [
            ['name' => 'مشاهده شرکت',         'slug' => 'organization.company.view'],
            ['name' => 'ویرایش شرکت',          'slug' => 'organization.company.edit'],
            ['name' => 'مدیریت شعبه‌ها',       'slug' => 'organization.branches.manage'],
            ['name' => 'مدیریت دپارتمان‌ها',   'slug' => 'organization.departments.manage'],
        ],

        // ── IAM ───────────────────────────────────────────────────────────────
        'iam' => [
            ['name' => 'مشاهده کاربران',       'slug' => 'iam.users.view'],
            ['name' => 'مدیریت کاربران',       'slug' => 'iam.users.manage'],
            ['name' => 'مشاهده نقش‌ها',        'slug' => 'iam.roles.view'],
            ['name' => 'مدیریت نقش‌ها',        'slug' => 'iam.roles.manage'],
            ['name' => 'مشاهده مجوزها',        'slug' => 'iam.permissions.view'],
            ['name' => 'مدیریت سیاست‌ها',      'slug' => 'iam.policies.manage'],
        ],

        // ── CRM ───────────────────────────────────────────────────────────────
        'crm' => [
            ['name' => 'مشاهده مشتریان',       'slug' => 'crm.customers.view'],
            ['name' => 'ایجاد مشتری',          'slug' => 'crm.customers.create'],
            ['name' => 'ویرایش مشتری',         'slug' => 'crm.customers.edit'],
            ['name' => 'حذف مشتری',            'slug' => 'crm.customers.delete'],
        ],

        // ── Inventory ─────────────────────────────────────────────────────────
        'inventory' => [
            ['name' => 'مشاهده محصولات',       'slug' => 'inventory.products.view'],
            ['name' => 'ایجاد محصول',          'slug' => 'inventory.products.create'],
            ['name' => 'ویرایش محصول',         'slug' => 'inventory.products.edit'],
            ['name' => 'حذف محصول',            'slug' => 'inventory.products.delete'],
        ],

        // ── Accounting ────────────────────────────────────────────────────────
        'accounting' => [
            ['name' => 'مشاهده فاکتورها',      'slug' => 'accounting.invoices.view'],
            ['name' => 'ایجاد فاکتور',         'slug' => 'accounting.invoices.create'],
            ['name' => 'تایید پرداخت فاکتور',  'slug' => 'accounting.invoices.mark_paid'],
            ['name' => 'حذف فاکتور',           'slug' => 'accounting.invoices.delete'],
            ['name' => 'مشاهده پرداخت‌ها',     'slug' => 'accounting.payments.view'],
            ['name' => 'ثبت پرداخت',           'slug' => 'accounting.payments.create'],
            ['name' => 'مشاهده دفتر کل',       'slug' => 'accounting.journal.view'],
        ],

        // ── HRM ───────────────────────────────────────────────────────────────
        'hrm' => [
            ['name' => 'درخواست مرخصی',        'slug' => 'hrm.leave.request'],
            ['name' => 'تایید مرخصی',          'slug' => 'hrm.leave.approve'],
            ['name' => 'مشاهده همه مرخصی‌ها',  'slug' => 'hrm.leave.view_all'],
        ],

        // ── Workflow ──────────────────────────────────────────────────────────
        'workflow' => [
            ['name' => 'مشاهده گردش‌کارها',    'slug' => 'workflow.view'],
            ['name' => 'پیشبرد گردش‌کار',      'slug' => 'workflow.advance'],
            ['name' => 'مدیریت تعاریف گردش‌کار', 'slug' => 'workflow.definitions.manage'],
        ],

        // ── Reporting ─────────────────────────────────────────────────────────
        'reporting' => [
            ['name' => 'مشاهده گزارش‌ها',      'slug' => 'reporting.view'],
            ['name' => 'ایجاد گزارش',          'slug' => 'reporting.create'],
            ['name' => 'صدور گزارش',           'slug' => 'reporting.export'],
        ],

        // ── Settings ──────────────────────────────────────────────────────────
        'settings' => [
            ['name' => 'مشاهده تنظیمات',       'slug' => 'settings.view'],
            ['name' => 'ویرایش تنظیمات',       'slug' => 'settings.edit'],
        ],

        // ── Audit ─────────────────────────────────────────────────────────────
        'audit' => [
            ['name' => 'مشاهده لاگ حسابرسی',   'slug' => 'audit.logs.view'],
        ],

        // ── Search ────────────────────────────────────────────────────────────
        'search' => [
            ['name' => 'جستجوی سراسری',        'slug' => 'search.global'],
        ],
    ];

    public function run(): void
    {
        foreach (self::PERMISSIONS as $module => $permissions) {
            foreach ($permissions as $permission) {
                Permission::updateOrCreate(
                    ['slug' => $permission['slug']],
                    [
                        'name'   => $permission['name'],
                        'module' => $module,
                    ]
                );
            }
        }

        $this->command->info('✓ ' . Permission::count() . ' permissions seeded.');
    }
}
