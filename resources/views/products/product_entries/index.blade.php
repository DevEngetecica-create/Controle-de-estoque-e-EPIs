@extends('layouts.app')

@section('title', 'Entradas de Produtos no Estoque')
@section('content')

<div class="card">
    <div class="card-body">
        <h3 class="page-title">Entradas de Produtos</h3>
        <form method="GET" action="{{ route('product_entries.index') }}">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Pesquisar...">
            <button type="submit">Pesquisar</button>
        </form>
        <a href="{{ route('product_entries.create') }}" class="btn btn-primary">Adicionar Produto</a>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome do Produto</th>
                    <th>Quantidade</th>
                    <th>Valor Unitário</th>
                    <th>Valor Total</th>
                    <th>Número da Nota Fiscal</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($entries as $entry)
                    <tr>
                        <td>{{ $entry->id }}</td>
                        <td>{{ $entry->product_name }}</td>
                        <td>{{ $entry->quantity }}</td>
                        <td>{{ $entry->unit_price }}</td>
                        <td>{{ $entry->total_price }}</td>
                        <td>{{ $entry->invoice_number }}</td>
                        <td>
                            <a href="{{ route('product_entries.show', $entry->id) }}" class="btn btn-info">Ver</a>
                            <a href="{{ route('product_entries.edit', $entry->id) }}" class="btn btn-warning">Editar</a>
                            <form action="{{ route('product_entries.destroy', $entry->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Excluir</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $entries->links() }}
    </div>
</div>

@endsection