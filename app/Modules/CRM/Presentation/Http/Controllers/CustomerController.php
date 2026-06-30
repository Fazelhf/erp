<?php

declare(strict_types=1);

namespace App\Modules\CRM\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\CRM\Application\Actions\CreateCustomerAction;
use App\Modules\CRM\Application\Actions\DeleteCustomerAction;
use App\Modules\CRM\Application\Actions\UpdateCustomerAction;
use App\Modules\CRM\Application\DTOs\CustomerData;
use App\Modules\CRM\Domain\Models\Customer;
use App\Modules\CRM\Infrastructure\Repositories\CustomerRepository;
use App\Modules\CRM\Presentation\Http\Requests\StoreCustomerRequest;
use App\Modules\CRM\Presentation\Http\Requests\UpdateCustomerRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class CustomerController extends Controller
{
    public function __construct(
        private readonly CustomerRepository   $repository,
        private readonly CreateCustomerAction $createAction,
        private readonly UpdateCustomerAction $updateAction,
        private readonly DeleteCustomerAction $deleteAction,
    ) {}

    public function index(Request $request): View
    {
        $customers = $this->repository->paginateWithSearch($request->string('search')->toString() ?: null);

        return view('customers.index', compact('customers'));
    }

    public function create(): View
    {
        return view('customers.create');
    }

    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        $this->createAction->execute(CustomerData::fromArray($request->validated()));

        return redirect()->route('customers.index')
            ->with('success', 'مشتری با موفقیت ایجاد شد.');
    }

    public function show(Customer $customer): View
    {
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer): View
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): RedirectResponse
    {
        $this->updateAction->execute($customer, CustomerData::fromArray($request->validated()));

        return redirect()->route('customers.index')
            ->with('success', 'مشتری با موفقیت به‌روزرسانی شد.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $this->deleteAction->execute($customer);

        return redirect()->route('customers.index')
            ->with('success', 'مشتری با موفقیت حذف شد.');
    }
}
