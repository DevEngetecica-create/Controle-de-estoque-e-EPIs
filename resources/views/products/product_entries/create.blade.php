@extends('layouts.app')

@section('title', 'Adicionar Entrada de Produto')
@section('content')

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <h3 class="page-title">Adicionar Entrada de Produto</h3>
            <form method="POST" action="{{ route('product_entries.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-2">
                        <div class="form-group">
                            <label for="category_id">Categoria</label>
                            <select class="form-control" id="category_id" name="category_id">
                                <option value="">Selecionar</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="subcategory_id">Subcategoria</label>
                            <select class="form-control" id="subcategory_id" name="subcategory_id">
                                <option value="">Selecionar</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="brands_id">Marca</label>
                            <select class="form-control" id="brands_id" name="brands_id">
                                <option value="">Selecionar</option>
                                @foreach($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-3">
                        <h4 class="text-center">Dados da Nota Fiscal</h4>
                        <table class="table" id="invoice_table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome do Produto</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                <tr>
                                    <td>{{ $product->id }}</td>
                                    <td>{{ $product->name }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div id="pagination">
                            {{ $products->links() }}
                        </div>
                    </div>

                    <div class="col-2">
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
                        <span id="adicionar" class="btn btn-primary">Salvar</span>
                    </div>

                    <div class="col-2">
                        <table class="table" id="tabela_produtos">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome do Produto</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Itens serão adicionados aqui -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        var filters = {
            brand_id: '',
            category_id: '',
            subcategory_id: ''
        };

        // Preencher subcategorias ao selecionar uma categoria
        $('#category_id').change(function() {
            var categoryId = $(this).val();
            filters.category_id = categoryId;
            if (categoryId) {
                $.ajax({
                    url: '/api/subcategories/' + categoryId,
                    type: 'GET',
                    success: function(response) {
                        $('#subcategory_id').empty().append('<option value="">Selecionar</option>');
                        response.forEach(function(subcategory) {
                            $('#subcategory_id').append('<option value="' + subcategory.id + '">' + subcategory.name + '</option>');
                        });
                    },
                    error: function(xhr) {
                        alert('Erro ao buscar subcategorias');
                    }
                });
            } else {
                $('#subcategory_id').empty().append('<option value="">Selecionar</option>');
            }
        });

        // Filtrar produtos ao selecionar uma marca
        $('#brands_id').change(function() {
            var brandId = $(this).val();
            filters.brand_id = brandId;
            var categoryId = $('#category_id').val();
            filters.category_id = categoryId;
            var subcategoryId = $('#subcategory_id').val();
            filters.subcategory_id = subcategoryId;

            filterProducts();
        });

        // Função para filtrar produtos
        function filterProducts(page = 1) {
            $.ajax({
                url: '/api/products',
                type: 'GET',
                data: {
                    brand_id: filters.brand_id,
                    category_id: filters.category_id,
                    subcategory_id: filters.subcategory_id,
                    page: page
                },
                success: function(response) {
                    var tableBody = $('#invoice_table tbody');
                    tableBody.empty();
                    response.products.forEach(function(product) {
                        var row = '<tr>' +
                            '<td>' + product.id + '</td>' +
                            '<td>' + product.name + '</td>' +
                            '</tr>';
                        tableBody.append(row);
                    });
                    // Atualizar a paginação
                    $('#pagination').html(response.pagination);
                },
                error: function(xhr) {
                    alert('Erro ao buscar produtos');
                }
            });
        }

        // Paginação
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            filterProducts(page);
        });

        // Adicionar produto à tabela
        $('#adicionar').click(function() {
            var productId = $('#product_id').val();
            var quantity = $('#quantity').val();
            var unitPrice = $('#unit_price').val();
            var totalPrice = $('#total_price').val();
            var productName = $('#product_id option:selected').text();

            if (productId && quantity && unitPrice && totalPrice) {
                var row = '<tr>' +
                    '<td>' + productId + '</td>' +
                    '<td>' + productName + '</td>' +
                    '<td>' + quantity + '</td>' +
                    '<td>' + unitPrice + '</td>' +
                    '<td>' + totalPrice + '</td>' +
                    '</tr>';
                $('#tabela_produtos tbody').append(row);
            } else {
                alert('Preencha todos os campos para adicionar o produto.');
            }
        });
    });
</script>

@endsection