<?php

declare(strict_types=1);

namespace Modules\Accounting\Presentation\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Accounting\Application\Commands\CancelInvoice\CancelInvoiceCommand;
use Modules\Accounting\Application\Commands\CreateInvoice\CreateInvoiceCommand;
use Modules\Accounting\Application\Commands\DeleteInvoice\DeleteInvoiceCommand;
use Modules\Accounting\Application\Commands\MarkInvoicePaid\MarkInvoicePaidCommand;
use Modules\Accounting\Application\Queries\GetInvoice\GetInvoiceQuery;
use Modules\Accounting\Application\Queries\GetInvoices\GetInvoicesQuery;
use Modules\Accounting\Presentation\Http\Resources\InvoiceResource;
use Shared\Application\Bus\CommandBusInterface;
use Shared\Application\Bus\QueryBusInterface;
use Shared\Presentation\Http\Controllers\ApiController;

final class InvoiceController extends ApiController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface   $queryBus,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $invoices = $this->queryBus->ask(new GetInvoicesQuery(
            companyId:  $request->user()->company_id,
            search:     $request->input('search'),
            status:     $request->input('status'),
            customerId: $request->integer('customer_id') ?: null,
        ));

        return $this->ok(InvoiceResource::collection($invoices)->response()->getData(true));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'issue_date'  => ['required', 'date'],
            'due_date'    => ['required', 'date', 'after_or_equal:issue_date'],
            'items'       => ['required', 'array', 'min:1'],
            'items.*.product_id'  => ['nullable', 'integer'],
            'items.*.description' => ['required', 'string'],
            'items.*.quantity'    => ['required', 'numeric', 'min:0.001'],
            'items.*.unit_price'  => ['required', 'numeric', 'min:0'],
            'items.*.discount'    => ['nullable', 'numeric', 'min:0'],
            'items.*.tax_rate'    => ['nullable', 'numeric', 'min:0', 'max:1'],
            'discount'    => ['nullable', 'numeric', 'min:0'],
            'notes'       => ['nullable', 'string'],
        ]);

        $this->commandBus->dispatch(new CreateInvoiceCommand(
            companyId:  $request->user()->company_id,
            customerId: $data['customer_id'],
            issueDate:  $data['issue_date'],
            dueDate:    $data['due_date'],
            items:      $data['items'],
            discount:   (float) ($data['discount'] ?? 0),
            notes:      $data['notes'] ?? null,
        ));

        return $this->created(null, 'Invoice created');
    }

    public function show(int $id): JsonResponse
    {
        $invoice = $this->queryBus->ask(new GetInvoiceQuery($id));

        return $this->ok(new InvoiceResource($invoice));
    }

    public function markPaid(int $id): JsonResponse
    {
        $this->commandBus->dispatch(new MarkInvoicePaidCommand($id));

        return $this->ok(null, 'Invoice marked as paid');
    }

    public function cancel(int $id): JsonResponse
    {
        $this->commandBus->dispatch(new CancelInvoiceCommand($id));

        return $this->ok(null, 'Invoice cancelled');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->commandBus->dispatch(new DeleteInvoiceCommand($id));

        return $this->noContent();
    }
}
