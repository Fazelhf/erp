<?php

declare(strict_types=1);

namespace Modules\CRM\Presentation\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Modules\CRM\Application\Commands\CreateCustomer\CreateCustomerCommand;
use Modules\CRM\Application\Commands\UpdateCustomer\UpdateCustomerCommand;
use Modules\CRM\Application\Commands\DeleteCustomer\DeleteCustomerCommand;
use Modules\CRM\Application\Queries\GetCustomers\GetCustomersQuery;
use Modules\CRM\Application\Queries\GetCustomer\GetCustomerQuery;
use Modules\CRM\Domain\Customer\Enums\CustomerTypeEnum;
use Modules\CRM\Presentation\Http\Requests\StoreCustomerRequest;
use Modules\CRM\Presentation\Http\Requests\UpdateCustomerRequest;
use Shared\Application\Bus\CommandBusInterface;
use Shared\Application\Bus\QueryBusInterface;

class CustomerController extends Controller
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly QueryBusInterface   $queryBus,
    ) {}

    public function index(Request $request): View
    {
        $customers = $this->queryBus->ask(new GetCustomersQuery(
            companyId: auth()->user()->company_id,
            search:    $request->input('search'),
            type:      $request->input('type'),
        ));

        return view('crm.customers.index', [
            'customers' => $customers,
            'types'     => CustomerTypeEnum::cases(),
        ]);
    }

    public function create(): View
    {
        return view('crm.customers.create', ['types' => CustomerTypeEnum::cases()]);
    }

    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        $v = $request->validated();

        $this->commandBus->dispatch(new CreateCustomerCommand(
            companyId:  auth()->user()->company_id,
            type:       $v['type'],
            name:       $v['name'],
            nationalId: $v['national_id'] ?? null,
            phone:      $v['phone'] ?? null,
            email:      $v['email'] ?? null,
            address:    $v['address'] ?? null,
        ));

        return redirect()->route('customers.index')->with('success', 'مشتری با موفقیت ثبت شد.');
    }

    public function edit(int $id): View
    {
        $customer = $this->queryBus->ask(new GetCustomerQuery($id));

        return view('crm.customers.edit', [
            'customer' => $customer,
            'types'    => CustomerTypeEnum::cases(),
        ]);
    }

    public function update(UpdateCustomerRequest $request, int $id): RedirectResponse
    {
        $v = $request->validated();

        $this->commandBus->dispatch(new UpdateCustomerCommand(
            customerId: $id,
            type:       $v['type'],
            name:       $v['name'],
            nationalId: $v['national_id'] ?? null,
            phone:      $v['phone'] ?? null,
            email:      $v['email'] ?? null,
            address:    $v['address'] ?? null,
            isActive:   (bool) ($v['is_active'] ?? true),
        ));

        return redirect()->route('customers.index')->with('success', 'مشتری با موفقیت بروزرسانی شد.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->commandBus->dispatch(new DeleteCustomerCommand($id));

        return redirect()->route('customers.index')->with('success', 'مشتری با موفقیت حذف شد.');
    }
}
