<?php

namespace App\Providers;

use App\Repositories\Eloquent\BrandRepository;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\SubcategoryRepository;
use App\Repositories\Interfaces\SubcategoryRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\Interfaces\BrandRepositoryInterface;
use App\Repositories\Interfaces\ProductEntryRepositoryInterface;
use App\Repositories\Eloquent\ProductEntryRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(SubcategoryRepositoryInterface::class, SubcategoryRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(BrandRepositoryInterface::class, BrandRepository::class);
        $this->app->bind(ProductEntryRepositoryInterface::class, ProductEntryRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
