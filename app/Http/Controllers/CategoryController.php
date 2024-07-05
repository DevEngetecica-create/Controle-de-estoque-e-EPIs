<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $categories = Category::where('name', 'like', "%{$search}%")->paginate(10);

        return view('categories.index', compact('categories', 'search'));
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
            'color' => 'required|string|max:7',
        ]);

        try {
            $category = new Category();
            $category->name = $request->name;
            $category->color = $request->color;
            $category->created_by = Auth::user()->email;
            $category->save();

            Log::create(['action' => 'Category created', 'user_email' => Auth::user()->email]);

            return redirect()->route('categories.index')->with('success', 'Categoria cadastrada com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Erro ao cadastrar categoria.');
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
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|max:7',
        ]);

        try {
            $category->name = $request->name;
            $category->color = $request->color;
            $category->updated_by = Auth::user()->email;
            $category->save();

            Log::create(['action' => 'Category updated', 'user_email' => Auth::user()->email]);

            return redirect()->route('categories.index')->with('success', 'Categoria atualizada com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Erro ao atualizar categoria.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            if ($category->subcategories()->count() > 0) {
                return redirect()->route('categories.index')->with('warning', 'Não é possível excluir uma categoria com subcategorias cadastradas.');
            }

            $category->delete();

            Log::create(['action' => 'Category deleted', 'user_email' => Auth::user()->email]);

            return redirect()->route('categories.index')->with('success', 'Categoria excluída com sucesso.');
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', 'Erro ao excluir categoria.');
        }
    }
}
