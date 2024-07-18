<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Estoque\CategoryController;
use App\Http\Controllers\Estoque\SubcategoryController;
use App\Http\Controllers\Estoque\ProductController;
use App\Http\Controllers\Estoque\BrandController;
use App\Http\Controllers\Estoque\ProductEntryController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\NFeController;


use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();



Route::middleware(['auth'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('subcategories', SubcategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('logs', LogController::class);

    Route::resource('product_entries', ProductEntryController::class);
    Route::post('product_entries/invoice', [ProductEntryController::class, 'fetchInvoice'])->name('product_entries.fetchInvoice');


    Route::post('api/nfe/emitir', [NFeController::class, 'emitir'])->name('api.nfe.emitir');

    Route::get('api/nfe/consultar/{chave}', [NFeController::class, 'consultar'])->name('api.nfe.consultar');
    Route::get('api/invoice/{chave}', [InvoiceController::class, 'consultarNFe'])->name('api.invoice.consultar');

    Route::get('api/subcategories/{categoryId}', [CategoryController::class, 'getSubcategories']);
    Route::get('api/products', [ProductController::class, 'filterProducts']);
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
