<?php

namespace App\Repositories\Interfaces;

use App\Models\Subcategory;

interface SubcategoryRepositoryInterface
{
    
    public function all();
    public function paginate($perPage = 10, $search = null);
    public function find($id);
    public function create(array $data);
    public function update(Subcategory $subcategory, array $data);
    public function delete(Subcategory $subcategory);

     /**
     * Busca subcategorias por ID de categoria.
     *
     * @param int $categoryId
     * @return \Illuminate\Support\Collection
     */
    public function findByCategoryId($categoryId);

}