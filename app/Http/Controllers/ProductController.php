<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\SubcategoryRepositoryInterface;
use App\Repositories\Interfaces\BrandRepositoryInterface;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $productRepository;
    protected $categoryRepository;
    protected $subcategoryRepository;
    protected $brandRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository,
        SubcategoryRepositoryInterface $subcategoryRepository,
        BrandRepositoryInterface $brandRepository
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
        $this->subcategoryRepository = $subcategoryRepository;
        $this->brandRepository = $brandRepository;
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
        $categories = $this->categoryRepository->all();
        $subcategories = $this->subcategoryRepository->all();
        $brands = $this->brandRepository->all();
        return view('products.create', compact('categories', 'subcategories', 'brands'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Remove pontos e substitui vírgula por ponto para converter o valor em um float
        $request->merge(['unit_price' => str_replace(['.', ','], ['', '.'], $request->unit_price)]);


        $request->validate(
            [
                'name' => 'required|string|max:255',
                'quantity' => 'required|integer',
                'unit_price' => 'required|numeric',
                'expiry_date' => 'required|date',
                'category_id' => 'required|exists:categories,id',
                'subcategory_id' => 'required|exists:subcategories,id',
                'minimum_stock' => 'required|integer',
                'unit' => 'required|string|max:255',
                'brand_id' => 'required|exists:brands,id',
            ],
            [
                'name.required' => 'Insira o nome do produto',
                'quantity.required' => 'Insira a quantidade',
                'unit_price.required' => 'Valor invlálido',
                'expiry_date.required' => 'Data invalida',
                'category_id.required' => 'Selecione uma categoria',
                'subcategory_id.required' => 'Selecione uma subcategoria',
                'minimum_stock.required' => 'Insira o estoque minímo',
                'unit.required' => 'Insira a unidade',
                'brand_idrequired' => 'Insira a marca/ fabricante do produto',
            ]
        );

        try {

            if ($request->file('image')) {

                //valida a extensão da imagem
                $request->validate([
                    'image' => 'mimes:png,jpg,jpeg,svg|max:2048'
                ], [
                    'image.mimes' => 'A imagem enviada possui extensão invalida. O Sistema aceita apenas as extensões "png, jpg, jpeg, svg"'
                ]);


                // obtem o nome da imagem
                $imageName = $request->file('image')->getClientOriginalName(); // obtem o nome da imagem


            $product = new Product();
            $product->name = $request->name;
            $product->quantity = $request->quantity;
            $product->unit_price = $request->unit_price;
            $product->expiry_date = $request->expiry_date;
            $product->category_id = $request->category_id;
            $product->subcategory_id = $request->subcategory_id;
            $product->image = $imageName;
            $product->minimum_stock = $request->minimum_stock;
            $product->unit = $request->unit;
            $product->brand_id = $request->brand_id;
            $product->created_by = Auth::user()->email;
            $product->save();

           

                // obtem o objeto do arquivo arquivo da imagem
                $imagePath = $request->file('image');

                // o caminho onde será salvo a imagem
                $targetDir = public_path("build/assets/images/product/{$product->id}");

                //move o upload da imagem para a pasta pública
                $imagePath->move($targetDir, $imageName);
            }

            /*  if ($request->hasFile('image')) {
                $path = $request->file('image')->store('images', 'public');
                $data['image'] = $path;
            } */

            Log::create(['action' => 'Product created', 'user_email' => Auth::user()->email]);

            return redirect()->route('products.index')->with('success', 'Produto cadastrado com sucesso.');
        } catch (\Exception $e) {

            return redirect()->route('products.index')->with('error', 'Erro ao cadastrar produto.');
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = $this->productRepository->find($id);
        $categories = $this->categoryRepository->all();
        $subcategories = $this->subcategoryRepository->all();
        $brands = $this->brandRepository->all();
        return view('products.show', compact('product', 'categories', 'subcategories', 'brands'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = $this->productRepository->find($id);
        $categories = $this->categoryRepository->all();
        $subcategories = $this->subcategoryRepository->all();
        $brands = $this->brandRepository->all();
        return view('products.edit', compact('product', 'categories', 'subcategories', 'brands'));
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

            if ($request->hasFile('image')) {
                // Deletar a imagem antiga se existir
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                // Salvar a nova imagem
                $path = $request->file('image')->store('images', 'public');
                $data['image'] = $path;
            } else {
                // Se não houver nova imagem, mantenha a antiga
                unset($data['image']);
            }
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
