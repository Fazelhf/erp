<?php

return [
    // Laravel Core
    App\Providers\AppServiceProvider::class,

    // ─── Shared Kernel — boots first (registers CommandBus, QueryBus, EventBus) ──
    Shared\Providers\SharedServiceProvider::class,

    // ─── Cross-cutting infrastructure ────────────────────────────────────────────
    Modules\Audit\Providers\AuditServiceProvider::class,
    Modules\Notifications\Providers\NotificationsServiceProvider::class,
    Modules\Settings\Providers\SettingsServiceProvider::class,
    Modules\Search\Providers\SearchServiceProvider::class,
    Modules\Scheduler\Providers\SchedulerServiceProvider::class,
    Modules\Reporting\Providers\ReportingServiceProvider::class,
    Modules\Integrations\Providers\IntegrationsServiceProvider::class,

    // ─── Workflow Engine ─────────────────────────────────────────────────────────
    Modules\Workflow\Providers\WorkflowServiceProvider::class,

    // ─── Organization hierarchy (Tenant → Company → Branch → Department) ─────────
    Modules\Organization\Providers\OrganizationServiceProvider::class,

    // ─── Identity & Access Management (Users, Roles, Permissions, Policies) ──────
    Modules\IAM\Providers\IAMServiceProvider::class,

    // ─── Business modules ────────────────────────────────────────────────────────
    Modules\Dashboard\Providers\DashboardServiceProvider::class,
    Modules\CRM\Providers\CRMServiceProvider::class,
    Modules\Inventory\Providers\InventoryServiceProvider::class,
    Modules\Accounting\Providers\AccountingServiceProvider::class,
    Modules\HRM\Providers\HRMServiceProvider::class,

    // ─── Legacy app/Modules/ (Breeze auth + Documents — kept until migrated) ──────
    App\Modules\Core\Providers\CoreServiceProvider::class,
    App\Modules\Auth\Providers\AuthModuleServiceProvider::class,
    App\Modules\Documents\Providers\DocumentsServiceProvider::class,
];
