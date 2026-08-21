<?php

declare(strict_types=1);

namespace Presentation\Http\Controllers\Admin;

use Domain\Hardware\Entities\Brand;
use Domain\Hardware\Entities\Product;
use Domain\Hardware\Entities\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AdminHardwareController
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $categoryId = $request->query('category_id');

        $query = Product::query()->with(['brand', 'category'])->latest('id');

        if (!empty($search)) {
            $query->where(function ($q) use ($search): void {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('model_name', 'like', "%{$search}%");
            });
        }

        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->paginate(15)->withQueryString();
        $categories = ProductCategory::query()->get();

        return view('admin.hardware.index', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

    public function create(): View
    {
        $categories = ProductCategory::query()->get();
        $brands = Brand::query()->get();

        return view('admin.hardware.create', [
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:product_categories,id'],
            'brand_id' => ['required', 'exists:brands,id'],
            'model_name' => ['required', 'string', 'max:200'],
            'full_name' => ['required', 'string', 'max:300'],
            'release_date' => ['nullable', 'date'],
            'launch_msrp_usd' => ['nullable', 'numeric', 'min:0'],
            'thumbnail_url' => ['nullable', 'url', 'max:500'],
            'overall_score' => ['required', 'numeric', 'min:0', 'max:10'],
            'gaming_score' => ['required', 'numeric', 'min:0', 'max:10'],
            'productivity_score' => ['required', 'numeric', 'min:0', 'max:10'],
            'specs_raw' => ['nullable', 'string'],
        ]);

        $slug = Str::slug($validated['full_name']);
        $count = Product::query()->where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        $specs = [];
        if (!empty($validated['specs_raw'])) {
            $decoded = json_decode($validated['specs_raw'], true);
            if (is_array($decoded)) {
                $specs = $decoded;
            }
        }

        Product::query()->create([
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'],
            'slug' => $slug,
            'model_name' => $validated['model_name'],
            'full_name' => $validated['full_name'],
            'release_date' => $validated['release_date'],
            'launch_msrp_usd' => $validated['launch_msrp_usd'],
            'thumbnail_url' => $validated['thumbnail_url'],
            'overall_score' => $validated['overall_score'],
            'gaming_score' => $validated['gaming_score'],
            'productivity_score' => $validated['productivity_score'],
            'specs' => $specs,
            'is_active' => true,
        ]);

        return redirect()->route('admin.hardware.index')->with('success', 'Đã thêm thiết bị phần cứng mới thành công!');
    }

    public function edit(int $id): View
    {
        $product = Product::query()->findOrFail($id);
        $categories = ProductCategory::query()->get();
        $brands = Brand::query()->get();

        return view('admin.hardware.edit', [
            'product' => $product,
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $product = Product::query()->findOrFail($id);

        $validated = $request->validate([
            'category_id' => ['required', 'exists:product_categories,id'],
            'brand_id' => ['required', 'exists:brands,id'],
            'model_name' => ['required', 'string', 'max:200'],
            'full_name' => ['required', 'string', 'max:300'],
            'release_date' => ['nullable', 'date'],
            'launch_msrp_usd' => ['nullable', 'numeric', 'min:0'],
            'thumbnail_url' => ['nullable', 'url', 'max:500'],
            'overall_score' => ['required', 'numeric', 'min:0', 'max:10'],
            'gaming_score' => ['required', 'numeric', 'min:0', 'max:10'],
            'productivity_score' => ['required', 'numeric', 'min:0', 'max:10'],
            'specs_raw' => ['nullable', 'string'],
        ]);

        $specs = $product->specs;
        if (!empty($validated['specs_raw'])) {
            $decoded = json_decode($validated['specs_raw'], true);
            if (is_array($decoded)) {
                $specs = $decoded;
            }
        }

        $product->update([
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'],
            'model_name' => $validated['model_name'],
            'full_name' => $validated['full_name'],
            'release_date' => $validated['release_date'],
            'launch_msrp_usd' => $validated['launch_msrp_usd'],
            'thumbnail_url' => $validated['thumbnail_url'],
            'overall_score' => $validated['overall_score'],
            'gaming_score' => $validated['gaming_score'],
            'productivity_score' => $validated['productivity_score'],
            'specs' => $specs,
        ]);

        return redirect()->route('admin.hardware.index')->with('success', 'Đã cập nhật thông số phần cứng thành công!');
    }

    public function destroy(int $id): RedirectResponse
    {
        $product = Product::query()->findOrFail($id);
        $product->delete();

        return redirect()->route('admin.hardware.index')->with('success', 'Đã xóa sản phẩm.');
    }
}
