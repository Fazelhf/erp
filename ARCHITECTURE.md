# ERP Platform — Enterprise Architecture Reference

## Overview

This ERP is built as a **Modular Monolith** on Laravel 12, applying Domain-Driven Design (DDD), Clean Architecture, and SOLID principles. The goal is to support dozens of modules and hundreds of thousands of lines of code without requiring structural rewrites.

---

## Guiding Principles

| Principle | Application |
|-----------|-------------|
| Domain-Driven Design | Code organized by business domain, not technical type |
| Clean Architecture | Strict layering: Domain → Application → Infrastructure → Presentation |
| Single Responsibility | Each class has exactly one reason to change |
| Open/Closed | Extend modules without modifying the core |
| Dependency Inversion | Depend on contracts, not concrete implementations |
| Loose Coupling | Modules communicate through events, not direct imports |
| High Cohesion | All code inside a module relates to the same business concern |

---

## Directory Structure

```
app/
├── Http/
│   └── Controllers/
│       └── Controller.php          ← Laravel base controller (kept for compatibility)
├── Modules/
│   ├── Core/                       ← Shared Kernel (no business logic)
│   │   ├── Domain/
│   │   │   ├── Contracts/          ← RepositoryInterface, ActionInterface
│   │   │   ├── Exceptions/         ← Base exception classes
│   │   │   ├── Rules/              ← Shared validation rules (JalaliDateRule)
│   │   │   └── ValueObjects/       ← Immutable value types
│   │   ├── Helpers/                ← DateHelper (Jalali/Gregorian conversion)
│   │   ├── Infrastructure/
│   │   │   └── Repositories/       ← BaseRepository implementation
│   │   └── Providers/
│   │       └── CoreServiceProvider.php
│   │
│   ├── Auth/                       ← Authentication (Login, Register, Password Reset)
│   │   ├── Presentation/
│   │   │   ├── Http/Controllers/   ← Breeze auth controllers
│   │   │   ├── Http/Requests/      ← LoginRequest
│   │   │   └── Routes/auth.php
│   │   └── Providers/
│   │       └── AuthModuleServiceProvider.php
│   │
│   ├── IAM/                        ← Identity & Access Management
│   │   ├── Domain/
│   │   │   ├── Models/User.php
│   │   │   └── Enums/UserStatusEnum.php
│   │   ├── Presentation/
│   │   │   ├── Http/Controllers/ProfileController.php
│   │   │   ├── Http/Requests/UpdateProfileRequest.php
│   │   │   └── Routes/web.php
│   │   └── Providers/IAMServiceProvider.php
│   │
│   ├── Dashboard/                  ← Analytics & KPI dashboard
│   │   ├── Application/
│   │   │   └── Queries/DashboardStatsQuery.php
│   │   ├── Presentation/
│   │   │   ├── Http/Controllers/DashboardController.php
│   │   │   └── Routes/web.php
│   │   └── Providers/DashboardServiceProvider.php
│   │
│   ├── CRM/                        ← Customer Relationship Management
│   │   ├── Domain/
│   │   │   ├── Models/Customer.php
│   │   │   ├── Enums/CustomerTypeEnum.php
│   │   │   ├── Events/CustomerCreatedEvent.php
│   │   │   └── Exceptions/CustomerNotFoundException.php
│   │   ├── Application/
│   │   │   ├── Actions/            ← CreateCustomerAction, UpdateCustomerAction, DeleteCustomerAction
│   │   │   └── DTOs/CustomerData.php
│   │   ├── Infrastructure/
│   │   │   └── Repositories/CustomerRepository.php
│   │   ├── Presentation/
│   │   │   ├── Http/Controllers/CustomerController.php
│   │   │   ├── Http/Requests/      ← StoreCustomerRequest, UpdateCustomerRequest
│   │   │   └── Routes/web.php
│   │   └── Providers/CRMServiceProvider.php
│   │
│   ├── Inventory/                  ← Products, Stock Management
│   │   ├── Domain/
│   │   │   ├── Models/Product.php
│   │   │   ├── Enums/ProductUnitEnum.php
│   │   │   └── Events/LowStockDetectedEvent.php
│   │   ├── Application/
│   │   │   ├── Actions/            ← CreateProductAction, UpdateProductAction, DeleteProductAction
│   │   │   └── DTOs/ProductData.php
│   │   ├── Infrastructure/
│   │   │   └── Repositories/ProductRepository.php
│   │   ├── Presentation/
│   │   │   ├── Http/Controllers/ProductController.php
│   │   │   ├── Http/Requests/      ← StoreProductRequest, UpdateProductRequest
│   │   │   └── Routes/web.php
│   │   └── Providers/InventoryServiceProvider.php
│   │
│   ├── Accounting/                 ← Invoicing, Finance
│   │   ├── Domain/
│   │   │   ├── Models/             ← Invoice, InvoiceItem
│   │   │   ├── Enums/InvoiceStatusEnum.php
│   │   │   ├── Events/             ← InvoiceCreatedEvent, InvoicePaidEvent
│   │   │   └── Exceptions/InvoiceException.php
│   │   ├── Application/
│   │   │   ├── Actions/            ← CreateInvoiceAction, UpdateInvoiceAction, DeleteInvoiceAction
│   │   │   ├── Services/InvoiceCalculationService.php
│   │   │   └── DTOs/               ← InvoiceData, InvoiceItemData
│   │   ├── Infrastructure/
│   │   │   └── Repositories/InvoiceRepository.php
│   │   ├── Presentation/
│   │   │   ├── Http/Controllers/InvoiceController.php
│   │   │   ├── Http/Requests/      ← StoreInvoiceRequest, UpdateInvoiceRequest
│   │   │   └── Routes/web.php
│   │   └── Providers/AccountingServiceProvider.php
│   │
│   ├── HRM/                        ← Human Resources Management
│   │   ├── Domain/
│   │   │   ├── Models/LeaveRequest.php
│   │   │   ├── Enums/              ← LeaveTypeEnum, LeaveStatusEnum
│   │   │   └── Events/LeaveRequestSubmittedEvent.php
│   │   ├── Application/
│   │   │   ├── Actions/            ← SubmitLeaveRequestAction, ReviewLeaveRequestAction
│   │   │   └── DTOs/LeaveRequestData.php
│   │   ├── Infrastructure/
│   │   │   └── Repositories/LeaveRequestRepository.php
│   │   ├── Presentation/
│   │   │   ├── Http/Controllers/   ← LeaveRequestController, LeaveReviewController
│   │   │   ├── Http/Requests/      ← SubmitLeaveRequestRequest, ReviewLeaveRequestRequest
│   │   │   └── Routes/web.php
│   │   └── Providers/HRMServiceProvider.php
│   │
│   └── Documents/                  ← Internal Letter Routing, File Management
│       ├── Domain/
│       │   ├── Models/             ← Letter, LetterAction
│       │   └── Enums/              ← LetterTypeEnum, LetterStatusEnum
│       ├── Application/
│       │   ├── Actions/            ← SendLetterAction, ReviewLetterAction
│       │   └── DTOs/LetterData.php
│       ├── Infrastructure/
│       │   └── Repositories/LetterRepository.php
│       ├── Presentation/
│       │   ├── Http/Controllers/LetterController.php
│       │   ├── Http/Requests/SendLetterRequest.php
│       │   └── Routes/web.php
│       └── Providers/DocumentsServiceProvider.php
│
└── Providers/
    └── AppServiceProvider.php      ← Kept minimal; delegates to module providers
```

---

## Architectural Layers

### 1. Domain Layer
**Purpose:** Pure business logic — no Laravel dependencies.

Contains:
- **Models** — Eloquent entities representing business objects
- **Enums** — PHP 8.1 backed enums replacing magic strings
- **Events** — Domain events for cross-module communication
- **Exceptions** — Domain-specific exception types
- **ValueObjects** — Immutable types (Money, Date, etc.)

**Rule:** Nothing in this layer imports from Application, Infrastructure, or Presentation.

### 2. Application Layer
**Purpose:** Orchestrate domain objects to fulfill use cases.

Contains:
- **Actions** — Single-responsibility use case handlers (`CreateCustomerAction`)
- **Services** — Multi-step logic shared across actions (`InvoiceCalculationService`)
- **DTOs** — Typed, immutable data transfer objects
- **Queries** — Read-only data fetching for reporting/dashboards

**Rule:** Actions receive DTOs, call repositories and domain services, fire events. They do not know about HTTP.

### 3. Infrastructure Layer
**Purpose:** Technical implementation of domain contracts.

Contains:
- **Repositories** — Concrete data access extending `BaseRepository`

**Rule:** Repositories only contain query logic. No business rules here.

### 4. Presentation Layer
**Purpose:** Adapter between HTTP and Application layer.

Contains:
- **Controllers** — Thin: parse request → call action → return response
- **Requests** — Form Requests handle all validation
- **Resources** — API response transformers (future)
- **Routes** — Module-owned route files

**Rule:** Controllers do zero business logic. They are routing adapters only.

---

## Module Service Providers

Each module self-registers via its `ServiceProvider`:

```php
// bootstrap/providers.php
App\Modules\CRM\Providers\CRMServiceProvider::class,
```

Each provider:
1. Binds its repository as a singleton
2. Loads its own routes
3. Can load its own migrations, translations, views in the future

---

## Adding a New Module

1. Create `app/Modules/{ModuleName}/` directory
2. Add Domain Models, Enums, Events
3. Add Application Actions and DTOs
4. Add Infrastructure Repository
5. Add Presentation Controllers, Requests, Routes
6. Create `Providers/{ModuleName}ServiceProvider.php`
7. Register in `bootstrap/providers.php`

No changes to any other module required.

---

## Key Architectural Decisions

### Decision 1: Modular Monolith over Microservices
**Why:** Microservices add distributed systems complexity (network failures, eventual consistency, deployment overhead) without benefit until traffic demands it. A modular monolith delivers the same clean domain boundaries while remaining deployable as a single unit and extractable to services later.

### Decision 2: Action Pattern over Fat Services
**Why:** Fat services grow to hundreds of lines. Each Action has exactly one public method (`execute()`), one reason to change, and is independently testable. SAP and Oracle use similar patterns internally under different names.

### Decision 3: DTOs for Data Transfer
**Why:** Passing raw `$request->validated()` arrays throughout the system creates invisible contracts. Typed `readonly` DTOs make data structure explicit, catch errors at construction time, and decouple HTTP layer from business logic.

### Decision 4: Repository Pattern
**Why:** Controllers and Actions that query Eloquent directly are tightly coupled to the database. Repositories centralize query logic, enable testing with fakes/mocks, and allow swapping the underlying storage without changing business logic.

### Decision 5: PHP 8.1 Backed Enums
**Why:** Magic strings like `'paid'`, `'draft'`, `'approved'` scattered across the codebase are unmaintainable. Enums provide type safety, IDE completion, exhaustiveness checks, and centralized labeling/color logic used in views.

### Decision 6: Domain Events for Cross-Module Communication
**Why:** When `CreateInvoiceAction` fires `InvoiceCreatedEvent`, future modules (Notifications, Audit, Analytics) can listen without modifying Accounting code. This is the only safe way for modules to communicate without creating coupling.

### Decision 7: InvoiceCalculationService
**Why:** The tax rate (9%) was hardcoded in the controller in two places. Extracting to a dedicated service means: one place to change the tax rate, testable in isolation, injectable into any action that needs calculation.

### Decision 8: Module-Owned Routes
**Why:** A single `routes/web.php` with hundreds of route definitions is unmaintainable at enterprise scale. Each module owns its routes file, loaded by its provider. Adding/removing a module doesn't touch any other file.

---

## Future Modules Roadmap

The architecture is immediately ready for:

| Module | Location |
|--------|----------|
| RBAC / Permissions | `app/Modules/IAM/` (extend existing) |
| Organization / Company | `app/Modules/Organization/` |
| Employees | `app/Modules/HRM/` (extend existing) |
| Attendance | `app/Modules/HRM/` |
| Payroll | `app/Modules/Payroll/` |
| Purchasing | `app/Modules/Purchasing/` |
| Sales | `app/Modules/Sales/` |
| POS | `app/Modules/POS/` |
| Warehouse | `app/Modules/Warehouse/` |
| Manufacturing | `app/Modules/Manufacturing/` |
| Projects & Tasks | `app/Modules/Projects/` |
| Tickets / Help Desk | `app/Modules/Support/` |
| Notifications | `app/Modules/Notifications/` |
| Audit Logs | `app/Modules/Audit/` |
| Reports / Analytics | `app/Modules/Reports/` |
| Workflow Engine | `app/Modules/Workflow/` |
| Settings | `app/Modules/Settings/` |
| Multi-language | `app/Modules/Localization/` |
| API Layer | `app/Modules/Api/` |
| AI Services | `app/Modules/AI/` |

---

## Database Migration Convention

**Current:** Flat `database/migrations/` directory (kept for backward compat).

**Target (future modules):** Migrations live inside each module and are loaded via:
```php
$this->loadMigrationsFrom(__DIR__.'/../../Infrastructure/Persistence/Migrations');
```

This makes each module self-contained and deployable independently.

---

## Testing Strategy

```
tests/
├── Unit/
│   ├── Modules/
│   │   ├── Accounting/
│   │   │   └── Application/
│   │   │       └── Services/InvoiceCalculationServiceTest.php
│   │   └── CRM/
│   │       └── Application/
│   │           └── Actions/CreateCustomerActionTest.php
│   └── ...
└── Feature/
    ├── Modules/
    │   ├── CRM/CustomerManagementTest.php
    │   ├── Accounting/InvoiceManagementTest.php
    │   └── HRM/LeaveRequestTest.php
    └── Auth/
```

Each Action and Service is independently unit-testable because they depend on injected repositories (mockable) not static Eloquent calls.
