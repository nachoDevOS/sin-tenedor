@extends('voyager::master')

@section('page_title', 'Añadir Venta')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="fa-solid fa-cart-shopping"></i> Añadir Venta
                            </h1>
                        </div>
                        <div class="col-md-4 text-right" style="margin-top: 30px">
                            <a href="{{ route('sales.index') }}" class="btn btn-warning">
                                <i class="voyager-plus"></i> <span>Volver</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="page-content edit-add container-fluid">
        <form id="form-sale" action="{{ route('sales.store') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-7">
                    <div class="panel panel-bordered">
                        <div class="panel-body" style="padding: 0px">
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#tab-all" aria-controls="tab-all" role="tab" data-toggle="tab">Todos</a>
                                </li>
                                @foreach ($categories as $category)
                                    <li role="presentation">
                                        <a href="#tab-{{ $category->id }}" aria-controls="tab-{{ $category->id }}" role="tab" data-toggle="tab">
                                            {{ $category->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
            
                            <div class="tab-content" style="padding: 15px">
                                <!-- Tab Todos los productos -->
                                <div role="tabpanel" class="tab-pane active" id="tab-all">
                                    <div class="row">
                                        @foreach ($categories as $category)
                                            @foreach ($category->itemSales as $product)
                                                @php
                                                    $cantStock = $product->itemSalestocks->sum('stock');
                                                @endphp
                                                <div class="col-md-3 mb-3" @if ($product->typeSale == 'Venta Con Stock' &&  $cantStock==0) style="opacity: 0.5; pointer-events: none;" @endif>
                                                    <div class="product-card" data-product-id="{{ $product->id }}" ondblclick="addToCart({{ $product->id }}, true)">
                                                        @if($product->image)
                                                            <img src="{{ asset('storage/'.$product->image) }}" class="img-responsive" style="height: 100px; width: 100%; object-fit: cover">
                                                        @else
                                                            <div class="text-center" style="height: 100px; background: #eee; display: flex; align-items: center; justify-content: center">
                                                                <i class="voyager-image" style="font-size: 30px"></i>
                                                            </div>
                                                        @endif
                                                        <div class="product-info">
                                                            <h5>{{ $product->name }}</h5>
                                                            <p class="text-success">Bs. {{ number_format($product->price, 2, ',', '.') }}</p>
                                                            @if ($product->typeSale == 'Venta Con Stock')                                                                
                                                                @if ($cantStock==0)
                                                                    Stock: <small style="color: red !important"> {{ number_format($cantStock, 2,',','.') }}</small>
                                                                @else
                                                                    Stock: <small> {{ number_format($cantStock, 2,',','.') }}</small>
                                                                @endif
                                                            @else
                                                                {{$product->typeSale}}
                                                            @endif                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
            
                                <!-- Tabs por categoría -->
                                @foreach ($categories as $category)
                                    <div role="tabpanel" class="tab-pane" id="tab-{{ $category->id }}">
                                        <div class="row">
                                            @foreach ($category->itemSales as $product)
                                                @php
                                                    $cantStock = $product->itemSalestocks->sum('stock');
                                                @endphp
                                                <div class="col-md-3 mb-3" @if ($product->typeSale == 'Venta Con Stock' &&  $cantStock==0) style="opacity: 0.5; pointer-events: none;" @endif>
                                                    <div class="product-card" data-product-id="{{ $product->id }}" ondblclick="addToCart({{ $product->id }}, true)">
                                                        @if($product->image)
                                                            <img src="{{ asset('storage/'.$product->image) }}" class="img-responsive" style="height: 100px; width: 100%; object-fit: cover">
                                                        @else
                                                            <div class="text-center" style="height: 100px; background: #eee; display: flex; align-items: center; justify-content: center">
                                                                <i class="voyager-image" style="font-size: 30px"></i>
                                                            </div>
                                                        @endif
                                                        <div class="product-info">
                                                            <h5>{{ $product->name }}</h5>
                                                            <p class="text-success">Bs. {{ number_format($product->price, 2, ',', '.') }}</p>
                                                            @if ($product->typeSale == 'Venta Con Stock')                                                                
                                                                @if ($cantStock==0)
                                                                    Stock: <small style="color: red !important"> {{ number_format($cantStock, 2,',','.') }}</small>
                                                                @else
                                                                    Stock: <small> {{ number_format($cantStock, 2,',','.') }}</small>
                                                                @endif
                                                            @else
                                                                {{$product->typeSale}}
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="panel panel-bordered">
                        <div class="panel-body" style="padding: 10px 0px">
                            <div class="col-md-12" style="height: 300px; max-height: 300px; overflow-y: auto">
                                <div class="table-responsive">
                                    <table id="dataTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 30px">N&deg;</th>
                                                <th>Detalles</th>
                                                <th style="text-align: center; width:15%">Precio</th>
                                                <th style="text-align: center; width:12%">Cantidad</th>
                                                <th style="text-align: center; width:10%">Subtotal</th>
                                                <th style="width: 30px"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-body">
                                            <tr id="tr-empty">
                                                <td colspan="7" style="height: 240px">
                                                    <h4 class="text-center text-muted" style="margin-top: 50px">
                                                        <i class="glyphicon glyphicon-shopping-cart" style="font-size: 50px"></i> <br><br>
                                                        Lista de venta vacía
                                                    </h4>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                <label for="person_id">Cliente</label>
                                <div class="input-group">
                                    <select name="person_id" id="select-person_id" class="form-control"></select>
                                    <span class="input-group-btn">
                                        <button id="trash-person" class="btn btn-danger" title="Quitar Clientes" data-toggle="modal" style="margin: 0px" type="button">
                                            <i class="voyager-trash"></i>
                                        </button>
                                        <button class="btn btn-primary" title="Nuevo cliente" data-target="#modal-create-person" data-toggle="modal" style="margin: 0px" type="button">
                                            <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="date">NIT/CI</label>
                                <input type="text" name="dni" id="input-dni" disabled value="" class="form-control" placeholder="NIT/CI">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="date">Monto recibido</label>
                                <input type="number" name="amountReceived" id="input-amount" style="text-align: right" min="0" value="0" step="0.1" class="form-control" placeholder="Monto recibo Bs." required>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="date">Fecha de venta</label>
                                <input type="datetime" name="dateSale" value="{{ date('Y-m-d H:m:s') }}" class="form-control" readonly required>
                            </div>
                            <div class="form-group col-md-6">
                            </div>
                            <div class="form-group col-md-6">
                                <h3 class="text-right" id="change-message" style="display: none;"><small>Cambio: Bs.</small> <b id="change-amount">0.00</b></h3>
                                <h3 class="text-right" id="change-message-error" style="display: none;"><small  style="color: red !important">Ingrese un Monto igual o mayor al total de la venta</small></h3>
                                <h3 class="text-right"><small>Total a cobrar: Bs.</small> <b id="label-total">0.00</b></h3>
                                <input type="hidden" id="amountTotalSale" name="amountTotalSale" value="0">
                            </div>
                            <div class="form-group col-md-12 text-center">
                                <button type="button" id="btn-submit" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-confirm">Vender <i class="voyager-basket"></i></button>
                               
                                <a href="{{ route('sales.index') }}" >Volver a la lista</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal de confirmación -->
            <div class="modal fade" data-backdrop="static" id="modal-confirm" role="dialog">
                <div class="modal-dialog modal-primary">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" style="color:rgb(255, 255, 255) !important"><i class="fa-solid fa-cart-shopping"></i> ¿Estás seguro que quieres registrar?</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="payment_type">Método de pago</label>
                                <select name="payment_type" id="select-payment_type" class="form-control" required>
                                    <option value="" disabled selected>Seleccionar método de pago</option>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Qr">Qr/Transferencia</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <input type="submit" class="btn btn-primary btn-confirm" id="btn-confirm" value="Confirmar">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Modal crear cliente -->
    @include('partials.modal-registerPerson')
@stop

@section('css')
    <style>
        .product-card {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            height: 100%;
        }

        .product-card:hover {
            border-color: #3c8dbc;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .product-info {
            padding: 10px 0;
        }

        .product-info h5 {
            margin: 5px 0;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-info p {
            margin: 0;
            font-weight: bold;
        }

        .nav-tabs {
            background: #f5f5f5;
            padding-left: 15px;
        }

        .nav-tabs > li > a {
            border-radius: 0;
            border: none;
            color: #555;
            padding: 12px 20px;
            font-weight: 600;
        }

        .nav-tabs > li.active > a {
            background: #fff;
            color: #3c8dbc;
            border-bottom: 2px solid #3c8dbc;
        }

        .form-group {
            margin-bottom: 10px !important;
        }

        .input-price, .input-quantity {
            width: 80px;
            margin: 0 auto;
            text-align: right;
        }

        .subtotal {
            font-weight: bold;
        }
    </style>
@stop

@section('javascript')
    <script src="{{ asset('js/include/person-select.js') }}"></script>
    <script src="{{ asset('js/include/person-register.js') }}"></script>

    <script>
        // Objeto para almacenar los productos en el carrito
        let cart = {};

        $(document).ready(function(){
            // Configurar eventos de clic para los productos
            $('.product-card').on('click', function() {
                const productId = $(this).data('product-id');
                addToCart(productId);
            });

            // Configurar eventos del formulario
            $('#form-sale').submit(function(e){
                $('.btn-confirm').val('Guardando...');
                $('.btn-confirm').attr('disabled', true);
            });

            $('#input-discount').on('keyup change', function(){
                calculateTotal();
            });
        });

        $('#trash-person').on('click', function () {
            $('#input-dni').val('');
            $('#select-person_id').val('').trigger('change');
             toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-bottom-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "1000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            
            toastr.success('Cliente eliminado', 'Eliminado');
        });

        $('#input-amount').on('click', function () {
            $('#input-amount').val('');
        });

        // Función para obtener el stock disponible de un producto
        function getStock(productId) {
            const $productCard = $(`.product-card[data-product-id="${productId}"]`);
            const stockText = $productCard.find('small').text().trim();
            
            // Extraer el número de stock del texto (ej: "Stock: 5.00")
            const stockMatch = stockText.match(/[\d.]+/);
            return stockMatch ? parseFloat(stockMatch[0]) : 0;
        }

        // Función para agregar productos al carrito con validación de stock
        function addToCart(productId, silent = false) {
            // Obtener información del producto y stock disponible
            const product = getProductById(productId);
            const availableStock = getStock(productId);
            
            if(!product) return;
            
            // Si el producto ya está en el carrito
            if(cart[productId]) {
                // Verificar que no exceda el stock
                if(cart[productId].quantity >= availableStock) {
                    if(!silent) {
                        toastr.error(`No hay suficiente stock. Disponible: ${availableStock}`, 'Stock insuficiente');
                    }
                    return;
                }
                cart[productId].quantity += 1;
            } else {
                // Verificar que haya stock disponible para agregar nuevo producto
                if(availableStock <= 0) {
                    if(!silent) {
                        toastr.error('Producto sin stock disponible', 'Stock agotado');
                    }
                    return;
                }
                
                cart[productId] = {
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    quantity: 1,
                    image: product.image
                };
            }
            
            // Mostrar feedback visual
            if(!silent) {
                showAddToCartFeedback(productId);
            }
            
            // Actualizar la tabla del carrito
            updateCartTable();
        }

        function showAddToCartFeedback(productId) {
            const $productCard = $(`.product-card[data-product-id="${productId}"]`);
            
            $productCard.css('background-color', '#e8f4fc');
            setTimeout(() => {
                $productCard.css('background-color', '');
            }, 300);
            
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": true,
                "positionClass": "toast-bottom-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "300",
                "hideDuration": "1000",
                "timeOut": "1000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
            
            toastr.success(`+1 ${cart[productId].name}`, 'Producto agregado');
        }

        // Función para actualizar la tabla del carrito
        function updateCartTable() {
            const $tableBody = $('#table-body');
            $tableBody.empty();
            
            if(Object.keys(cart).length === 0) {
                $tableBody.append(`
                    <tr id="tr-empty">
                        <td colspan="7" style="height: 240px">
                            <h4 class="text-center text-muted" style="margin-top: 50px">
                                <i class="glyphicon glyphicon-shopping-cart" style="font-size: 50px"></i> <br><br>
                                Lista de venta vacía
                            </h4>
                        </td>
                    </tr>
                `);
            } else {
                let counter = 1;
                let total = 0;
                
                for(const productId in cart) {
                    const product = cart[productId];
                    const availableStock = getStock(productId);
                    const subtotal = product.price * product.quantity;
                    total += subtotal;
                    
                    $tableBody.append(`
                        <tr class="tr-item" id="tr-item-${productId}">
                            <td class="td-item">${counter++}</td>
                            <td>
                                <b>${product.name}</b>
                                <input type="hidden" name="products[${productId}][id]" value="${productId}">
                                <input type="hidden" name="products[${productId}][name]" value="${product.name}">
                            </td>
                            <td style="text-align: right">
                                <input type="number" name="products[${productId}][price]" class="form-control input-price" readonly
                                    value="${product.price.toFixed(2)}" min="0.01" step="0.01" required>
                            </td>
                            <td>
                                <input type="number" name="products[${productId}][quantity]" class="form-control input-quantity" 
                                    value="${product.quantity}" min="1" max="${availableStock}" step="1" required>
                                <small class="text-muted">Disponible: ${availableStock}</small>
                            </td>
                            <td class="text-right subtotal">${subtotal.toFixed(2)}</td>
                            <td class="text-right">
                                <button type="button" onclick="removeFromCart(${productId})" class="btn btn-link">
                                    <i class="voyager-trash text-danger"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                }
                
                // Configurar eventos para actualizar subtotales
                $('.input-quantity').on('change keyup', function() {
                    const $input = $(this);
                    const newQuantity = parseInt($input.val()) || 0;
                    const maxStock = parseInt($input.attr('max')) || 0;
                    
                    if (newQuantity > maxStock) {
                        toastr.error(`No hay suficiente stock. Disponible: ${maxStock}`, 'Stock insuficiente');
                        $input.val(maxStock);
                    }
                    
                    updateSubtotal($(this).closest('tr'));
                    calculateTotal();
                });
            }
            
            // Actualizar el total
            calculateTotal();
        }

        // Función para actualizar subtotal con validación de stock
        function updateSubtotal($row) {
            const productId = $row.attr('id').replace('tr-item-', '');
            const availableStock = getStock(productId);
            const price = parseFloat($row.find('.input-price').val()) || 0;
            const quantity = parseInt($row.find('.input-quantity').val()) || 0;
            const subtotal = price * quantity;
            
            $row.find('.subtotal').text(subtotal.toFixed(2));
            
            // Actualizar también en el objeto cart
            if(cart[productId]) {
                cart[productId].price = price;
                cart[productId].quantity = quantity;
            }
        }

        // Función para calcular y mostrar el cambio
        function calculateChange() {
            const amountReceived = parseFloat($('#input-amount').val()) || 0;
            const total = parseFloat($('#amountTotalSale').val()) || 0;
            
            if (amountReceived >= total) {
                const change = amountReceived - total;
                $('#change-message-error').hide();
                $('#change-message').show();
                $('#change-amount').text(change.toFixed(2));
            }
            else {
                $('#change-message').hide();
                $('#change-message-error').show();
            }
        }

        // Función para calcular el total
        function calculateTotal() {
            let total = 0;
            const discount = parseFloat($('#input-discount').val()) || 0;
            
            $('.subtotal').each(function() {
                total += parseFloat($(this).text());
            });
            
            $('#input-discount').attr('max', total.toFixed(2));
            const finalTotal = total - discount;
            
            $('#label-total').text(finalTotal.toFixed(2));
            $('#amountTotalSale').val(finalTotal.toFixed(2));
            
            // Si el monto recibido actual es menor que el nuevo total, actualizarlo
            const currentAmount = parseFloat($('#input-amount').val()) || 0;
            if (currentAmount < finalTotal) {
                $('#input-amount').val(finalTotal.toFixed(2));
            }
            
            // Calcular el cambio nuevamente
            calculateChange();
        }

        function removeFromCart(productId) {
            if(cart[productId]) {
                delete cart[productId];
                updateCartTable();
                toastr.info('Producto eliminado del carrito', '', {timeOut: 1000});
            }
        }
    
        function getProductById(productId) {
            const $productCard = $(`.product-card[data-product-id="${productId}"]`);
            let productName = $productCard.find('h5').text().trim();
            
            const middle = Math.floor(productName.length / 2);
            if (productName.substring(0, middle) === productName.substring(middle)) {
                productName = productName.substring(0, middle);
            }
            
            return {
                id: productId,
                name: productName,
                price: parseFloat($productCard.find('.text-success').text().replace('Bs. ', '')),
                image: $productCard.find('img').attr('src') || null
            };
        }

        // Evento para el input de monto recibido
        $('#input-amount').on('change keyup', function() {
            const total = parseFloat($('#amountTotalSale').val()) || 0;
            const amount = parseFloat($(this).val()) || 0;
            
            $(this).attr('min', total.toFixed(2));
            calculateChange();
        });

        // Validar antes de enviar el formulario
        $('#form-sale').submit(function(e) {
            const total = parseFloat($('#amountTotalSale').val()) || 0;
            const amountReceived = parseFloat($('#input-amount').val()) || 0;
            
            if (amountReceived < total) {
                toastr.error(`El monto recibido no puede ser menor al total (Bs. ${total.toFixed(2)})`);
                $('#input-amount').val(total.toFixed(2));
                calculateChange();
                e.preventDefault();
                return false;
            }
            
            // Validar que haya productos en el carrito
            if(Object.keys(cart).length === 0) {
                toastr.error('Debe agregar al menos un producto al carrito');
                e.preventDefault();
                return false;
            }
            
            return true;
        });
    </script>
@stop