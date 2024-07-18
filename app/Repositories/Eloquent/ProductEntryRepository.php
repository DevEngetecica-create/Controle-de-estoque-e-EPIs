<?php


namespace App\Repositories\Eloquent;

use App\Models\ProductEntry;
use App\Repositories\Interfaces\ProductEntryRepositoryInterface;

class ProductEntryRepository implements ProductEntryRepositoryInterface
{
    public function all()
    {
        return ProductEntry::all();
    }

    public function paginate($perPage = 10, $search = null)
    {
        return ProductEntry::with(['product'])
            ->where('invoice_number', 'like', "%{$search}%")
            ->orWhere('number_OC', 'like', "%{$search}%")
            ->WhereHas('product', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })            
            ->paginate($perPage);
    }

    public function find($id)
    {
        return ProductEntry::find($id);
    }

    public function create(array $data)
    {
        return ProductEntry::create($data);
    }

    public function update(ProductEntry $product_entries_repository, array $data)
    {
        return $product_entries_repository->update($data);
    }

    public function delete(ProductEntry $product_entries_repository)
    {
        return $product_entries_repository->delete();
    }
}
