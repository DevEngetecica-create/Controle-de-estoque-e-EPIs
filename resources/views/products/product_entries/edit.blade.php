@extends('dashboard')
@section('title', 'Editar Entrada de Produto')
@section('content')

<div class="card">
    <div class="card-body">
        <h3 class="page-title">Editar Entrada de Produto</h3>
        <form method="POST" action="{{ route('product_entries.update', $entry->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="product_name">Nome do Produto</label>
                <input type="text" class="form-control" id="product_name" name="product_name" value="{{ $entry->product_name }}" required>
            </div>
            <div class="form-group">
                <label for="quantity">Quantidade</label>
                <input type="number" class="form-control" id="quantity" name="quantity" value="{{ $entry->quantity }}" required>
            </div>
            <div class="form-group">
                <label for="unit_price">Valor Unitário</label>
                <input type="number" step="0.01" class="form-control" id="unit_price" name="unit_price" value="{{ $entry->unit_price }}" required>
            </div>
            <div class="form-group">
                <label for="total_price">Valor Total</label>
                <input type="number" step="0.01" class="form-control" id="total_price" name="total_price" value="{{ $entry->total_price }}" required>
            </div>
            <div class="form-group">
                <label for="invoice_number">Número da Nota Fiscal</label>
                <input type="text" class="form-control" id="invoice_number" name="invoice_number" value="{{ $entry->invoice_number }}" required>
            </div>
            <div class="form-group">
                <label for="invoice_file">Arquivo da Nota Fiscal</label>
                <input type="file" class="form-control" id="invoice_file" name="invoice_file">
                @if($entry->invoice_file)
                    <a href="{{ asset('storage/' . $entry->invoice_file) }}" target="_blank">Ver Arquivo</a>
                @endif
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>
    </div>
</div>

@endsection
