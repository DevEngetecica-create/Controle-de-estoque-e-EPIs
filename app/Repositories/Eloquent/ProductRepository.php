<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function all()
    {
        return Product::all();
    }

    public function paginate($perPage = 10, $search = null)
    {
        return Product::with(['category', 'subcategory', 'brand'])
            ->where('name', 'like', "%{$search}%")
            ->orWhereHas('category', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orWhereHas('subcategory', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orWhereHas('brand', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    public function filter($brandId, $categoryId, $subcategoryId)
    {
        $query = Product::query();

        if ($brandId) {
            $query->where('brand_id', $brandId);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($subcategoryId) {
            $query->where('subcategory_id', $subcategoryId);
        }

        return $query->get();
    }

    public function find($id)
    {
        return Product::find($id);
    }


    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data)
    {
        return $product->update($data);
    }

    public function delete(Product $product)
    {
        return $product->delete();
    }
}
