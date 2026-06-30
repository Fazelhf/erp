<?php

declare(strict_types=1);

namespace Modules\Accounting\Presentation\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Modules\Accounting\Application\Commands\CreateInvoice\CreateInvoiceCommand;
use Modules\Accounting\Application\Commands\DeleteInvoice\DeleteInvoiceCommand;
use Modules\Accounting\Application\Commands\MarkInvoicePaid\MarkInvoicePaidCommand;
use Modules\Accounting\Application\Queries\GetInvoice\GetInvoiceQuery;
use Modules\Accounting\Application\Queries\GetInvoices\GetInvoicesQuery;
use Modules\Accounting\Domain\Invoice\Enums\InvoiceStatusEnum;
use Modules\Accounting\Presentation\Http\Requests\StoreInvoiceRequest;
use Shared\Application\Bus\CommandBusInterface;
use Shared\Application\Bus\QueryBusInterface;

class InvoiceController extends Controller
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface   $queryBus,
    ) {}

    public function index(Request $request): View
    {
        $invoices = $this->queryBus->ask(new GetInvoicesQuery(
            companyId:  auth()->user()->company_id,
            search:     $request->input('search'),
            status:     $request->input('status'),
            customerId: $request->integer('customer_id') ?: null,
        ));

        return view('accounting.invoices.index', [
            'invoices' => $invoices,
            'statuses' => InvoiceStatusEnum::cases(),
        ]);
    }

    public function create(): View
    {
        return view('accounting.invoices.create');
    }

    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $v = $request->validated();

        $this->commandBus->dispatch(new CreateInvoiceCommand(
            companyId:  auth()->user()->company_id,
            customerId: $v['customer_id'],
            issueDate:  $v['issue_date'],
            dueDate:    $v['due_date'],
            items:      $v['items'],
            discount:   (float) ($v['discount'] ?? 0),
            notes:      $v['notes'] ?? null,
        ));

        return redirect()->route('invoices.index')->with('success', 'فاکتور با موفقیت ثبت شد.');
    }

    public function show(int $id): View
    {
        $invoice = $this->queryBus->ask(new GetInvoiceQuery($id));

        return view('accounting.invoices.show', compact('invoice'));
    }

    public function markPaid(int $id): RedirectResponse
    {
        $this->commandBus->dispatch(new MarkInvoicePaidCommand($id));

        return back()->with('success', 'فاکتور پرداخت‌شده علامت‌گذاری شد.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->commandBus->dispatch(new DeleteInvoiceCommand($id));

        return redirect()->route('invoices.index')->with('success', 'فاکتور حذف شد.');
    }
}
