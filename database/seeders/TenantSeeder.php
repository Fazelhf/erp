<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $tenantId = DB::table('tenants')->insertGetId([
            'name'       => 'Demo Tenant',
            'slug'       => 'demo',
            'domain'     => 'demo.erp.local',
            'plan'       => 'enterprise',
            'is_active'  => true,
            'settings'   => json_encode([
                'max_companies' => 5,
                'max_users'     => 100,
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $companyId = DB::table('companies')->insertGetId([
            'tenant_id'         => $tenantId,
            'name'              => 'شرکت نمونه',
            'legal_name'        => 'شرکت نمونه سهامی خاص',
            'tax_id'            => '1234567890',
            'registration_no'   => 'REG-001',
            'currency'          => 'IRR',
            'fiscal_year_start' => '04-01',
            'phone'             => '02112345678',
            'email'             => 'info@company.local',
            'website'           => 'https://company.local',
            'address'           => 'تهران، خیابان ولیعصر',
            'is_active'         => true,
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        $branchId = DB::table('branches')->insertGetId([
            'company_id'      => $companyId,
            'name'            => 'دفتر مرکزی',
            'code'            => 'HQ',
            'is_headquarters' => true,
            'phone'           => '02112345678',
            'address'         => 'تهران، خیابان ولیعصر',
            'is_active'       => true,
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        DB::table('departments')->insert([
            [
                'branch_id'  => $branchId,
                'parent_id'  => null,
                'manager_id' => null,
                'name'       => 'مدیریت',
                'code'       => 'MGT',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'branch_id'  => $branchId,
                'parent_id'  => null,
                'manager_id' => null,
                'name'       => 'حسابداری',
                'code'       => 'ACC',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'branch_id'  => $branchId,
                'parent_id'  => null,
                'manager_id' => null,
                'name'       => 'منابع انسانی',
                'code'       => 'HR',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'branch_id'  => $branchId,
                'parent_id'  => null,
                'manager_id' => null,
                'name'       => 'فناوری اطلاعات',
                'code'       => 'IT',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Store for downstream seeders
        cache()->put('seed.tenant_id', $tenantId);
        cache()->put('seed.company_id', $companyId);
        cache()->put('seed.branch_id', $branchId);
    }
}
