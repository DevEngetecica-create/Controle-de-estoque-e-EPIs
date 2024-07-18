<?php

namespace App\Http\Controllers\Estoque;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\ProductEntry;
use Illuminate\Support\Facades\Http;
use App\Repositories\Interfaces\ProductEntryRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\SubcategoryRepositoryInterface;
use App\Repositories\Interfaces\BrandRepositoryInterface;
use Illuminate\Support\Facades\Storage;

use Illuminate\Http\Request;

class ProductEntryController extends Controller
{

    /**
     * Display a listing of the resource.
     */

     protected $productRepository;
     protected $categoryRepository;
     protected $subcategoryRepository;
     protected $brandRepository;
     protected $product_entries_repository;
 
     public function __construct(
         ProductRepositoryInterface $productRepository,
         CategoryRepositoryInterface $categoryRepository,
         SubcategoryRepositoryInterface $subcategoryRepository,
         BrandRepositoryInterface $brandRepository,
         ProductEntryRepositoryInterface $product_entries_repository
         
     ) {
         $this->productRepository = $productRepository;
         $this->categoryRepository = $categoryRepository;
         $this->subcategoryRepository = $subcategoryRepository;
         $this->brandRepository = $brandRepository;
         $this->product_entries_repository = $product_entries_repository;
         $this->productRepository = $productRepository;
     }
 
    
       
    public function index(Request $request)
    {
        $search = $request->input('search');
        $entries = $this->product_entries_repository->paginate(10, $search);
        return view('products.product_entries.index', compact('entries', 'search'));
    }

    public function show($id)
    {
        $entry = $this->product_entries_repository->find($id);
        return view('products.product_entries.show', compact('entry'));
    }

    public function create()
    {
        $categories = $this->categoryRepository->all();
        $subcategories = $this->subcategoryRepository->all();
        $brands = $this->brandRepository->all();     
        $products = $this->productRepository->paginate(10);
        
        return view('products.product_entries.create', compact('products','categories', 'subcategories', 'brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'unit_price' => 'required|numeric',
            'total_price' => 'required|numeric',
            'invoice_number' => 'required|string|max:255',
            'invoice_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',

        ]);

        $data = $request->all();
        if ($request->hasFile('invoice_file')) {
            $data['invoice_file'] = $request->file('invoice_file')->store('invoices');
        }

        $this->product_entries_repository->create($data);

        return redirect()->route('product_entries.index')->with('success', 'Produto adicionado ao estoque com sucesso.');
    }

    public function edit($id)
    {
        $entry = $this->product_entries_repository->find($id);
        $products = ProductEntry::all();
        return view('products.product_entries.edit', compact('entry', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
            'unit_price' => 'required|numeric',
            'total_price' => 'required|numeric',
            'invoice_number' => 'required|string|max:255',
            'invoice_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $data = $request->all();
        if ($request->hasFile('invoice_file')) {
            $data['invoice_file'] = $request->file('invoice_file')->store('invoices');
        }

        $this->product_entries_repository->update($id, $data);

        return redirect()->route('product_entries.index')->with('success', 'Produto atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $this->product_entries_repository->delete($id);

        return redirect()->route('product_entries.index')->with('success', 'Produto removido do estoque com sucesso.');
    }

    public function consultarNotaFiscal($numeroNotaFiscal)
    {
        $response = Http::get('https://brasilapi.com.br/api/nfce/v1/' . $numeroNotaFiscal);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    public function getSubcategories($categoryId)
    {
        $subcategories = $this->subcategoryRepository->findByCategoryId($categoryId);
        return response()->json($subcategories);
    }

    public function filterProducts(Request $request)
    {
        $brandId = $request->input('brand_id');
        $categoryId = $request->input('category_id');
        $subcategoryId = $request->input('subcategory_id');

        $products = $this->productRepository->filter($brandId, $categoryId, $subcategoryId);

        return response()->json(['products' => $products]);
    }
}
