<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    public function index(Request $request)
    {
        $search = $request->input('search');
        $products = $this->productRepository->paginate(10, $search);
        return view('products.index', compact('products', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'unit_price' => 'required|numeric',
            'expiry_date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'image' => 'nullable|string',
            'minimum_stock' => 'required|integer',
            'unit' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
        ]);

        try {
            $product = new Product();
            $product->name = $request->name;
            $product->quantity = $request->quantity;
            $product->unit_price = $request->unit_price;
            $product->expiry_date = $request->expiry_date;
            $product->category_id = $request->category_id;
            $product->subcategory_id = $request->subcategory_id;
            $product->image = $request->image;
            $product->minimum_stock = $request->minimum_stock;
            $product->unit = $request->unit;
            $product->brand_id = $request->brand_id;
            $product->created_by = Auth::user()->email;
            $product->save();

            Log::create(['action' => 'Product created', 'user_email' => Auth::user()->email]);

            return redirect()->route('products.index')->with('success', 'Produto cadastrado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', 'Erro ao cadastrar produto.');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer',
            'unit_price' => 'required|numeric',
            'expiry_date' => 'required|date',
            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => 'required|exists:subcategories,id',
            'image' => 'nullable|string',
            'minimum_stock' => 'required|integer',
            'unit' => 'required|string|max:255',
            'brand_id' => 'required|exists:brands,id',
        ]);

        try {
            $product->name = $request->name;
            $product->quantity = $request->quantity;
            $product->unit_price = $request->unit_price;
            $product->expiry_date = $request->expiry_date;
            $product->category_id = $request->category_id;
            $product->subcategory_id = $request->subcategory_id;
            $product->image = $request->image;
            $product->minimum_stock = $request->minimum_stock;
            $product->unit = $request->unit;
            $product->brand_id = $request->brand_id;
            $product->updated_by = Auth::user()->email;
            $product->save();

            Log::create(['action' => 'Product updated', 'user_email' => Auth::user()->email]);

            return redirect()->route('products.index')->with('success', 'Produto atualizado com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', 'Erro ao atualizar produto.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();

            Log::create(['action' => 'Product deleted', 'user_email' => Auth::user()->email]);

            return redirect()->route('products.index')->with('success', 'Produto excluído com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('products.index')->with('error', 'Erro ao excluir produto.');
        }
    }
}
