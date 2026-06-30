<?php

return [
    // Laravel Core
    App\Providers\AppServiceProvider::class,

    // ─── src/ Architecture (DDD + CQRS + Bounded Contexts) ───────────────────

    // Shared Kernel — must boot first (registers CommandBus & QueryBus singletons)
    Shared\Providers\SharedServiceProvider::class,

    // Cross-cutting modules
    Modules\Audit\Providers\AuditServiceProvider::class,
    Modules\Notifications\Providers\NotificationsServiceProvider::class,
    Modules\Settings\Providers\SettingsServiceProvider::class,

    // Organization hierarchy (Tenant → Company → Branch → Department)
    Modules\Organization\Providers\OrganizationServiceProvider::class,

    // Identity & Access Management
    Modules\IAM\Providers\IAMServiceProvider::class,

    // Business modules
    Modules\Dashboard\Providers\DashboardServiceProvider::class,
    Modules\CRM\Providers\CRMServiceProvider::class,
    Modules\Inventory\Providers\InventoryServiceProvider::class,
    Modules\Accounting\Providers\AccountingServiceProvider::class,
    Modules\HRM\Providers\HRMServiceProvider::class,

    // ─── Legacy app/Modules/ (Breeze auth + Documents — kept until migrated) ──
    App\Modules\Core\Providers\CoreServiceProvider::class,
    App\Modules\Auth\Providers\AuthModuleServiceProvider::class,
    App\Modules\Documents\Providers\DocumentsServiceProvider::class,
];
