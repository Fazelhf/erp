<?php

declare(strict_types=1);

namespace Modules\Accounting\Presentation\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Accounting\Domain\Payment\Entities\Payment;
use Modules\Accounting\Presentation\Http\Resources\PaymentResource;
use Shared\Presentation\Http\Controllers\ApiController;

final class PaymentController extends ApiController
{
    public function index(Request $request): JsonResponse
    {
        $payments = Payment::query()
            ->where('company_id', $request->user()->company_id)
            ->when($request->input('invoice_id'), fn ($q, $v) => $q->where('invoice_id', $v))
            ->latest('paid_at')
            ->paginate($request->integer('per_page', 15));

        return $this->ok(PaymentResource::collection($payments)->response()->getData(true));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'invoice_id' => ['required', 'integer', 'exists:invoices,id'],
            'amount'     => ['required', 'numeric', 'min:0.01'],
            'method'     => ['required', 'string', 'in:cash,bank_transfer,card,cheque,online'],
            'reference'  => ['nullable', 'string', 'max:255'],
            'paid_at'    => ['nullable', 'date'],
            'notes'      => ['nullable', 'string'],
        ]);

        $payment = Payment::create(array_merge($data, [
            'company_id' => $request->user()->company_id,
            'paid_at'    => $data['paid_at'] ?? now(),
        ]));

        return $this->created(new PaymentResource($payment));
    }

    public function show(int $id): JsonResponse
    {
        return $this->ok(new PaymentResource(Payment::findOrFail($id)));
    }
}
