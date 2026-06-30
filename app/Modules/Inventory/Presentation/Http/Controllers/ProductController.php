<?php

declare(strict_types=1);

namespace App\Modules\Inventory\Presentation\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Inventory\Application\Actions\CreateProductAction;
use App\Modules\Inventory\Application\Actions\DeleteProductAction;
use App\Modules\Inventory\Application\Actions\UpdateProductAction;
use App\Modules\Inventory\Application\DTOs\ProductData;
use App\Modules\Inventory\Domain\Models\Product;
use App\Modules\Inventory\Infrastructure\Repositories\ProductRepository;
use App\Modules\Inventory\Presentation\Http\Requests\StoreProductRequest;
use App\Modules\Inventory\Presentation\Http\Requests\UpdateProductRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

final class ProductController extends Controller
{
    public function __construct(
        private readonly ProductRepository   $repository,
        private readonly CreateProductAction $createAction,
        private readonly UpdateProductAction $updateAction,
        private readonly DeleteProductAction $deleteAction,
    ) {}

    public function index(Request $request): View
    {
        $products = $this->repository->paginateWithSearch(
            search:       $request->string('search')->toString() ?: null,
            lowStockOnly: $request->boolean('low_stock'),
        );

        return view('products.index', compact('products'));
    }

    public function create(): View
    {
        return view('products.create');
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        $this->createAction->execute(ProductData::fromArray($request->validated()));

        return redirect()->route('products.index')
            ->with('success', 'محصول با موفقیت ایجاد شد.');
    }

    public function show(Product $product): View
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product): View
    {
        return view('products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $this->updateAction->execute($product, ProductData::fromArray($request->validated()));

        return redirect()->route('products.index')
            ->with('success', 'محصول با موفقیت به‌روزرسانی شد.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $this->deleteAction->execute($product);

        return redirect()->route('products.index')
            ->with('success', 'محصول با موفقیت حذف شد.');
    }
}
