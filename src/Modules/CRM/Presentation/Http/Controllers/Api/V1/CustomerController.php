<?php

declare(strict_types=1);

namespace Modules\CRM\Presentation\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\CRM\Application\Commands\CreateCustomer\CreateCustomerCommand;
use Modules\CRM\Application\Commands\DeleteCustomer\DeleteCustomerCommand;
use Modules\CRM\Application\Commands\UpdateCustomer\UpdateCustomerCommand;
use Modules\CRM\Application\Queries\GetCustomer\GetCustomerQuery;
use Modules\CRM\Application\Queries\GetCustomers\GetCustomersQuery;
use Modules\CRM\Presentation\Http\Resources\CustomerResource;
use Shared\Application\Bus\CommandBusInterface;
use Shared\Application\Bus\QueryBusInterface;
use Shared\Presentation\Http\Controllers\ApiController;

final class CustomerController extends ApiController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface   $queryBus,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $customers = $this->queryBus->ask(new GetCustomersQuery(
            companyId: $request->user()->company_id,
            search:    $request->input('search'),
            type:      $request->input('type'),
        ));

        return $this->ok(CustomerResource::collection($customers)->response()->getData(true));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'type'       => ['required', 'string', 'in:individual,company'],
            'name'       => ['required', 'string', 'max:255'],
            'national_id'=> ['nullable', 'string', 'max:20'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'email'      => ['nullable', 'email'],
            'address'    => ['nullable', 'string'],
        ]);

        $this->commandBus->dispatch(new CreateCustomerCommand(
            companyId:  $request->user()->company_id,
            type:       $data['type'],
            name:       $data['name'],
            nationalId: $data['national_id'] ?? null,
            phone:      $data['phone'] ?? null,
            email:      $data['email'] ?? null,
            address:    $data['address'] ?? null,
        ));

        return $this->created(null, 'Customer created');
    }

    public function show(int $id): JsonResponse
    {
        $customer = $this->queryBus->ask(new GetCustomerQuery($id));

        return $this->ok(new CustomerResource($customer));
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'type'       => ['sometimes', 'string', 'in:individual,company'],
            'name'       => ['sometimes', 'string', 'max:255'],
            'national_id'=> ['nullable', 'string', 'max:20'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'email'      => ['nullable', 'email'],
            'address'    => ['nullable', 'string'],
            'is_active'  => ['boolean'],
        ]);

        $this->commandBus->dispatch(new UpdateCustomerCommand(
            customerId: $id,
            type:       $data['type'] ?? null,
            name:       $data['name'] ?? null,
            nationalId: $data['national_id'] ?? null,
            phone:      $data['phone'] ?? null,
            email:      $data['email'] ?? null,
            address:    $data['address'] ?? null,
            isActive:   $data['is_active'] ?? true,
        ));

        return $this->ok(null, 'Customer updated');
    }

    public function destroy(int $id): JsonResponse
    {
        $this->commandBus->dispatch(new DeleteCustomerCommand($id));

        return $this->noContent();
    }
}
