<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Settings\Application\Services\SettingsService;

class SettingsSeeder extends Seeder
{
    public function __construct(private readonly SettingsService $settings) {}

    public function run(): void
    {
        $companyId = cache()->get('seed.company_id');

        $defaults = [
            'general' => [
                'company_name'      => 'شرکت نمونه',
                'company_email'     => 'info@company.local',
                'company_phone'     => '02112345678',
                'currency'          => 'IRR',
                'language'          => 'fa',
                'timezone'          => 'Asia/Tehran',
                'date_format'       => 'jalali',
                'logo_path'         => null,
            ],
            'accounting' => [
                'default_tax_rate'      => 0.09,
                'invoice_prefix'        => 'INV',
                'invoice_start_number'  => 1000,
                'payment_due_days'      => 30,
                'fiscal_year_start'     => '04-01',
                'round_totals'          => true,
                'show_tax_on_invoice'   => true,
            ],
            'hrm' => [
                'annual_leave_days'     => 26,
                'sick_leave_days'       => 10,
                'work_hours_per_day'    => 8,
                'work_days_per_week'    => 5,
                'overtime_multiplier'   => 1.4,
                'leave_approval_required' => true,
            ],
            'notifications' => [
                'email_enabled'         => true,
                'sms_enabled'           => false,
                'invoice_created'       => true,
                'invoice_paid'          => true,
                'leave_requested'       => true,
                'leave_approved'        => true,
                'workflow_action'       => true,
            ],
            'security' => [
                'session_timeout_minutes' => 120,
                'max_login_attempts'      => 5,
                'two_factor_enabled'      => false,
                'password_min_length'     => 8,
                'api_token_expiry_days'   => 30,
            ],
            'reporting' => [
                'default_format'          => 'pdf',
                'report_expiry_days'      => 7,
                'enable_scheduled_reports' => false,
            ],
        ];

        foreach ($defaults as $group => $settings) {
            foreach ($settings as $key => $value) {
                $this->settings->set($companyId, $group, $key, $value);
            }
        }

        $total = collect($defaults)->sum(fn ($g) => count($g));
        $this->command->info("✓ {$total} default settings seeded across " . count($defaults) . ' groups.');
    }
}
