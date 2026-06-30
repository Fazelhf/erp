<?php

return [
    // Core Infrastructure
    App\Providers\AppServiceProvider::class,
    App\Modules\Core\Providers\CoreServiceProvider::class,

    // Domain Modules
    App\Modules\Auth\Providers\AuthModuleServiceProvider::class,
    App\Modules\IAM\Providers\IAMServiceProvider::class,
    App\Modules\Dashboard\Providers\DashboardServiceProvider::class,
    App\Modules\CRM\Providers\CRMServiceProvider::class,
    App\Modules\Inventory\Providers\InventoryServiceProvider::class,
    App\Modules\Accounting\Providers\AccountingServiceProvider::class,
    App\Modules\HRM\Providers\HRMServiceProvider::class,
    App\Modules\Documents\Providers\DocumentsServiceProvider::class,
];
