<?php

namespace App\Repositories\Interfaces;

use App\Models\ProductEntry;

interface ProductEntryRepositoryInterface
{
    public function all();
    public function paginate($perPage = 10, $search = null);
    public function find($id);
    public function create(array $data);
    public function update(ProductEntry $product_entries_repository, array $data);
    public function delete(ProductEntry $product_entries_repository);

    
}

