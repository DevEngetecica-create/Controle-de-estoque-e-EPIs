@extends('dashboard')
@section('title', 'Visualizar Entrada de Produto')
@section('content')

<div class="card">
    <div class="card-body">
        <h3 class="page-title">Visualizar Entrada de Produto</h3>
        <table class="table">
            <tr>
                <th>ID</th>
                <td>{{ $entry->id }}</td>
            </tr>
            <tr>
                <th>Nome do Produto</th>
                <td>{{ $entry->product_name }}</td>
            </tr>
            <tr>
                <th>Quantidade</th>
                <td>{{ $entry->quantity }}</td>
            </tr>
            <tr>
                <th>Valor Unitário</th>
                <td>{{ $entry->unit_price }}</td>
            </tr>
            <tr>
                <th>Valor Total</th>
                <td>{{ $entry->total_price }}</td>
            </tr>
            <tr>
                <th>Número da Nota Fiscal</th>
                <td>{{ $entry->invoice_number }}</td>
            </tr>
            <tr>
                <th>Arquivo da Nota Fiscal</th>
                <td>
                    @if($entry->invoice_file)
                        <a href="{{ asset('storage/' . $entry->invoice_file) }}" target="_blank">Ver Arquivo</a>
                    @endif
                </td>
            </tr>
        </table>
    </div>
</div>

@endsection
