<?php

declare(strict_types=1);

namespace App\Modules\Accounting\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Accounting\Application\Actions\CreateInvoiceAction;
use App\Modules\Accounting\Application\Actions\DeleteInvoiceAction;
use App\Modules\Accounting\Application\Actions\UpdateInvoiceAction;
use App\Modules\Accounting\Application\DTOs\InvoiceData;
use App\Modules\Accounting\Domain\Models\Invoice;
use App\Modules\Accounting\Infrastructure\Repositories\InvoiceRepository;
use App\Modules\Accounting\Presentation\Http\Requests\StoreInvoiceRequest;
use App\Modules\Accounting\Presentation\Http\Requests\UpdateInvoiceRequest;
use App\Modules\CRM\Domain\Models\Customer;
use App\Modules\Inventory\Infrastructure\Repositories\ProductRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class InvoiceController extends Controller
{
    public function __construct(
        private readonly InvoiceRepository   $repository,
        private readonly ProductRepository   $productRepository,
        private readonly CreateInvoiceAction $createAction,
        private readonly UpdateInvoiceAction $updateAction,
        private readonly DeleteInvoiceAction $deleteAction,
    ) {}

    public function index(Request $request): View
    {
        $invoices = $this->repository->paginateWithFilters(
            search: $request->string('search')->toString() ?: null,
            status: $request->string('status')->toString() ?: null,
        );

        return view('invoices.index', compact('invoices'));
    }

    public function create(): View
    {
        $customers = Customer::active()->get();
        $products  = $this->productRepository->allActive();

        return view('invoices.create', compact('customers', 'products'));
    }

    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $this->createAction->execute(InvoiceData::fromArray($request->validated()));

        return redirect()->route('invoices.index')
            ->with('success', 'فاکتور با موفقیت ایجاد شد.');
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load(['customer', 'user', 'items.product']);

        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice): View
    {
        $invoice->load('items');
        $customers = Customer::active()->get();
        $products  = $this->productRepository->allActive();

        return view('invoices.edit', compact('invoice', 'customers', 'products'));
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        $this->updateAction->execute($invoice, InvoiceData::fromArray($request->validated()));

        return redirect()->route('invoices.index')
            ->with('success', 'فاکتور با موفقیت به‌روزرسانی شد.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $this->deleteAction->execute($invoice);

        return redirect()->route('invoices.index')
            ->with('success', 'فاکتور با موفقیت حذف شد.');
    }
}
