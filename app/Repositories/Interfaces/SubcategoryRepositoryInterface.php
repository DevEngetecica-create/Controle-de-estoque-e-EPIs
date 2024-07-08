<?php

namespace App\Repositories\Interfaces;

use App\Models\Subcategory;

interface SubcategoryRepositoryInterface
{
    public function all();
    public function paginate($perPage = 10, $search = null);
    public function find($id);
    public function create(array $data);
    public function update(Subcategory $category, array $data);
    public function delete(Subcategory $category);
}