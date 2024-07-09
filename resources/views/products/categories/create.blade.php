@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body p-5">
            <h1>Cadastrar Produto</h1>
            <form action="{{ route('products.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                </div>
                <div class="form-group">
                    <label for="quantity">Quantidade</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" value="{{ old('quantity') }}">
                </div>
                <div class="form-group">
                    <label for="unit_price">Preço Unitário</label>
                    <input type="text" class="form-control" id="unit_price" name="unit_price" value="{{ old('unit_price') }}">
                </div>
                <div class="form-group">
                    <label for="expiry_date">Data de Validade</label>
                    <input type="date" class="form-control" id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}">
                </div>
                <div class="form-group">
                    <label for="category_id">Categoria</label>
                    <select class="form-control" id="category_id" name="category_id">
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="subcategory_id">Subcategoria</label>
                    <select class="form-control" id="subcategory_id" name="subcategory_id">
                        @foreach($subcategories as $subcategory)
                        <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="brand_id">Marca</label>
                    <select class="form-control" id="brand_id" name="brand_id">
                        @foreach($brands as $brand)
                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Outros campos aqui -->
                <button type="submit" class="btn btn-primary">Salvar</button>
            </form>
        </div>
    </div>
</div>
@endsection