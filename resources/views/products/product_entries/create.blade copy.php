@extends('layouts.app')

@section('title', 'Adicionar Entrada de Produto')
@section('content')

<div class="card">
    <div class="card-body mb-3">
        <h3 class="page-title">Emitir NFe</h3>
        <form method="POST" action="{{ route('api.nfe.emitir') }}" enctype="multipart/form-data">
            @csrf
            <!-- Adicione campos necessários para a emissão da NFe -->
            <button type="submit" class="btn btn-primary">Emitir NFe</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <h3 class="page-title">Adicionar Entrada de Produto</h3>
        <form method="POST" action="{{ route('product_entries.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="invoice_key">Chave da Nota Fiscal</label>
                <input type="text" class="form-control" id="invoice_key" name="invoice_key" required>
            </div>
            <button type="button" id="fetch_invoice" class="btn btn-primary">Buscar Nota Fiscal</button>

            <div class="form-group">
                <label for="product_id">Produto</label>
                <select class="form-control" id="product_id" name="product_id" required>
                    <option value="">Selecione um Produto</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Quantidade</label>
                <input type="number" class="form-control" id="quantity" name="quantity" required>
            </div>
            <div class="form-group">
                <label for="unit_price">Valor Unitário</label>
                <input type="number" step="0.01" class="form-control" id="unit_price" name="unit_price" required>
            </div>
            <div class="form-group">
                <label for="total_price">Valor Total</label>
                <input type="number" step="0.01" class="form-control" id="total_price" name="total_price" required>
            </div>
            <div class="form-group">
                <label for="invoice_file">Arquivo da Nota Fiscal</label>
                <input type="file" class="form-control" id="invoice_file" name="invoice_file">
            </div>
            <button type="submit" class="btn btn-primary">Salvar</button>
        </form>

        <h4 class="mt-5">Dados da Nota Fiscal</h4>
        <table class="table" id="invoice_table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome do Produto</th>
                    <th>Quantidade</th>
                    <th>Valor Unitário</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dados da Nota Fiscal serão inseridos aqui -->
            </tbody>
        </table>
    </div>
</div>

<div class="card mt-5">
    <div class="card-body">
        <h3 class="page-title">Consultar NFe</h3>
        <form id="consultarNFeForm">
            <div class="form-group">
                <label for="chave">Chave da NFe</label>
                <input type="text" class="form-control" id="chave" name="chave" required>
            </div>
            <button type="button" id="consultarNFe" class="btn btn-primary">Consultar NFe</button>
        </form>

        <h4 class="mt-5">Dados da Nota Fiscal</h4>
        <table class="table" id="invoice_table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome do Produto</th>
                    <th>Quantidade</th>
                    <th>Valor Unitário</th>
                    <th>Valor Total</th>
                </tr>
            </thead>
            <tbody>
                <!-- Dados da Nota Fiscal serão inseridos aqui -->
            </tbody>
        </table>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#fetch_invoice').click(function() {
            var invoiceKey = $('#invoice_key').val();
            if (invoiceKey) {
                $.ajax({
                    url: "{{ route('api.invoice.consultar', '') }}/" + invoiceKey,
                    type: 'GET',
                    success: function(response) {

                        console.lgo(response)

                        var tableBody = $('#invoice_table tbody');
                        tableBody.empty();
                        response.products.forEach(function(product) {
                            var row = '<tr>' +
                                '<td>' + product.id + '</td>' +
                                '<td>' + product.name + '</td>' +
                                '<td>' + product.quantity + '</td>' +
                                '<td>' + product.unit_price + '</td>' +
                                '</tr>';
                            tableBody.append(row);
                        });
                    },
                    error: function(xhr) {
                        alert('Erro ao buscar dados da Nota Fiscal');
                    }
                });
            } else {
                alert('Por favor, insira a chave da Nota Fiscal');
            }
        });

        $('#consultarNFe').click(function() {
            var chave = $('#chave').val();
            if (chave) {
                $.ajax({
                    url: "{{ route('api.nfe.consultar', '') }}/" + chave,
                    type: 'GET',
                    success: function(response) {
                        var tableBody = $('#invoice_table tbody');
                        tableBody.empty();
                        if (response.products) {
                            response.products.forEach(function(product) {
                                var row = '<tr>' +
                                    '<td>' + product.id + '</td>' +
                                    '<td>' + product.name + '</td>' +
                                    '<td>' + product.quantity + '</td>' +
                                    '<td>' + product.unit_price + '</td>' +
                                    '<td>' + product.total + '</td>' +
                                    '</tr>';
                                tableBody.append(row);
                            });
                        } else {
                            tableBody.append('<tr><td colspan="5">Nenhum dado encontrado</td></tr>');
                        }
                    },
                    error: function(xhr) {
                        alert('Erro ao buscar dados da Nota Fiscal');
                    }
                });
            } else {
                alert('Por favor, insira a chave da Nota Fiscal');
            }
        });
    });
</script>

@endsection