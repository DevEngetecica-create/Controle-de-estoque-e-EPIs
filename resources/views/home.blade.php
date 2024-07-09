@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <a href="{{route('products.index')}}">
                        <button class="btn btn-primary">Produtos</button>
                    </a>
                    <a href="{{route('categories.index')}}">
                        <button class="btn btn-warning mx-3">Categorias</button>
                    </a>
                    <a href="{{route('subcategories.index')}}">
                        <button class="btn btn-info">Subcategorias</button>
                    </a>
                    <a href="{{route('brands.index')}}">
                        <button class="btn btn-success mx-3">Subcategorias</button>
                    </a>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection