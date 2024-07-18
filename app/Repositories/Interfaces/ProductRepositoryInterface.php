<?php

namespace App\Repositories\Interfaces;

use App\Models\Product;

interface ProductRepositoryInterface
{
    public function all();
    public function paginate($perPage = 10, $search = null);
    public function find($id);
    public function create(array $data);
    public function update(Product $product, array $data);
    public function delete(Product $product);

    /**
     * Filtra produtos com base na marca, categoria e subcategoria.
     *
     * @param int|null $brandId
     * @param int|null $categoryId
     * @param int|null $subcategoryId
     * @return \Illuminate\Support\Collection
     */
    public function filter($brandId, $categoryId, $subcategoryId);

}