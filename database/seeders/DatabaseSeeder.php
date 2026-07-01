<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // 1. Multi-tenant hierarchy (Tenant → Company → Branch → Departments)
            TenantSeeder::class,

            // 2. RBAC — permissions first, then roles with permission assignments
            PermissionSeeder::class,
            RoleSeeder::class,

            // 3. Users — admin + demo users with roles
            AdminUserSeeder::class,

            // 4. Workflow definitions (invoice approval, leave approval, procurement)
            WorkflowSeeder::class,

            // 5. Default settings per group (general, accounting, hrm, notifications, …)
            SettingsSeeder::class,

            // 6. Legacy demo data (customers, products, invoices)
            CustomerSeeder::class,
            ProductSeeder::class,
            InvoiceSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('🎉 Database seeded successfully.');
        $this->command->newLine();
        $this->command->table(
            ['Module', 'Status'],
            [
                ['Tenant + Company + Branch + Departments', '✓'],
                ['Permissions (35)', '✓'],
                ['Roles (7 system roles)', '✓'],
                ['Admin + 4 demo users', '✓'],
                ['Workflow definitions (4)', '✓'],
                ['Default settings (6 groups)', '✓'],
                ['Demo customers / products / invoices', '✓'],
            ]
        );
    }
}
