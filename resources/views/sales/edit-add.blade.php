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
                                                <div class="col-md-3 mb-3">
                                                    <div class="product-card" data-product-id="{{ $product->id }}" data-type-sale="{{ $product->typeSale }}">
                                                        @php
                                                            $image = asset('images/default.jpg');
                                                            if($product->image){
                                                                $image = asset('storage/'.$product->image);
                                                            }
                                                        @endphp
                                                        <img src="{{ $image }}" class="img-responsive" style="height: 100px; width: 100%; object-fit: cover">
                                                        <div class="product-info">
                                                            <h5>{{ $product->name }}</h5>
                                                            <p class="text-success">Bs. {{ number_format($product->price, 2, ',', '.') }}</p>
                                                            @if ($product->typeSale == 'Venta Con Stock')                                                                
                                                                @if ($cantStock==0)
                                                                    Stock: <small style="color: red !important;"> {{ number_format($cantStock, 2,',','.') }}</small>
                                                                @else
                                                                    Stock: <small> {{ number_format($cantStock, 2,',','.') }}</small>
                                                                @endif
                                                            @else
                                                                <small class="type-sale">Venta Sin Stock</small>
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
                                                <div class="col-md-3 mb-3">
                                                    <div class="product-card" data-product-id="{{ $product->id }}" data-type-sale="{{ $product->typeSale }}">
                                                        @php
                                                            $image = asset('images/default.jpg');
                                                            if($product->image){
                                                                $image = asset('storage/'.$product->image);
                                                            }
                                                        @endphp
                                                        <img src="{{ $image }}" class="img-responsive" style="height: 100px; width: 100%; object-fit: cover">
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
                                                                <small class="type-sale">Venta Sin Stock</small>
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
                            <div class="col-md-12" style="height: 350px; max-height: 350px; overflow-y: auto">
                                <div class="table-responsive">
                                    <table id="dataTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 30px">N&deg;</th>
                                                <th>Detalles</th>
                                                <th style="text-align: center; width:15%">Precio</th>
                                                <th style="text-align: center; width:12%">Cantidad</th>
                                                <th style="text-align: center; width:10%">Subtotal</th>
                                                <th style="width: 25px"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-body">
                                            <tr id="tr-empty">
                                                <td colspan="7" style="height: 280px">
                                                    <h4 class="text-center text-muted" style="margin-top: 80px">
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
                                <label for="observation">Detalle / Observación</label>
                                <textarea name="observation" id="observation" class="form-control" rows="3"></textarea>
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
                            {{-- <div class="form-group col-md-12">
                                <label for="input-dni">NIT/CI</label>
                                <input type="text" name="dni" id="input-dni" disabled value="" class="form-control" placeholder="NIT/CI">
                            </div> --}}
 
                            <div class="form-group col-md-6">
                                <label for="payment_type">Método de pago</label>
                                <select name="paymentType" id="select-payment_type" class="form-control select2" required>
                                    <option value="" disabled selected>Seleccionar método de pago</option>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="Qr"><i class="fa-solid fa-qrcode"></i> Qr/Transferencia</option>
                                    <option value="Ambos">Ambos Metodos</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="typeSale">Tipo de venta</label>
                                <select name="typeSale" id="typeSale" class="form-control select2" required>
                                    <option value="" disabled selected>--Seleccione una opción--</option>
                                    <option value="Mesa">Para Mesa</option>
                                    <option value="Llevar">Para LLevar</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="date">Monto "Efectivo"</label>
                                <input type="number" readonly onkeyup="amountRecived()" onchange="amountRecived()" name="amountReceivedEfectivo" id="input-amountEfectivo" style="text-align: right" min="0" value="0" step="0.1" class="form-control" placeholder="Monto Efectivo." required>
                            </div>


                            <div class="form-group col-md-6">
                                <label for="date">Monto "QR"</label>
                                <input type="number" readonly name="amountReceivedQr" id="input-amountQr" style="text-align: right" min="0" value="0" step="0.1" class="form-control" placeholder="Monto Qr." required>
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
            <div class="modal fade" tabindex="-1" data-backdrop="static" id="modal-confirm" role="dialog">
                <div class="modal-dialog modal-primary">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" style="color:rgb(255, 255, 255) !important"><i class="fa-solid fa-cart-shopping"></i> ¿Estás seguro que quieres registrar?</h4>
                        </div>
                        <div class="modal-body">
                         

                            {{-- <div class="form-group">
                                <label for="typeSale">Tipo de venta</label>
                                <select name="typeSale" id="typeSale" class="form-control select2" required>
                                    <option value="" disabled selected>--Seleccione una opción--</option>
                                    <option value="Mesa">Para Mesa</option>
                                    <option value="Llevar">Para LLevar</option>
                                </select>
                            </div> --}}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                            <input type="submit" class="btn btn-primary btn-confirm" id="btn-confirm" value="Confirmar venta">
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
            padding: 5px 0;
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

        let cart = {};
        let lastClickTime = 0;
        const CLICK_DELAY = 300; // 300ms de delay entre clicks

        $(document).ready(function(){

            // Inicializar Select2 para el select de tipo de pago con iconos (efectivo y qr)
            $('#select-payment_type').select2({
                templateResult: formatOption,
                templateSelection: formatOption
            });
            
            function formatOption(option) {
                if (!option.id) return option.text;
                
                if (option.id === 'Efectivo') {
                    return $('<i class="fa-solid fa-money-bill-1-wave"></i> ' + option.text + '</span>');
                }
                if (option.id === 'Qr') {
                    return $('<span><i class="fa-solid fa-qrcode"></i> ' + option.text + '</span>');
                }
                if (option.id === 'Ambos') {
                    return $('<span><i class="fa-solid fa-money-bill-1-wave"></i> + <i class="fa-solid fa-qrcode"></i> ' + option.text + '</span>');
                }
                return option.text;
            }


            // Configurar eventos de clic para los productos
            $('.product-card').on('click', function() {
                const now = Date.now();
                if (now - lastClickTime < CLICK_DELAY) return;
                lastClickTime = now;
                
                const productId = $(this).data('product-id');
                addToCart(productId);
            });

            // Configurar eventos del formulario
            $('#form-sale').submit(function(e){
                $('.btn-confirm').val('Guardando...');
                $('.btn-confirm').attr('disabled', true);           
            });

            // Evento para el modal de confirmación cuando se cierra vulve a habilitar el botón
            $('#modal-confirm').on('hidden.bs.modal', function() {
                $(this).find('.btn-confirm').removeAttr('disabled');
                $(this).find('.btn-confirm').val('Confirmar venta');
            });
        });

        $('#trash-person').on('click', function () {
            // $('#input-dni').val('');
            $('#select-person_id').val('').trigger('change');
                       
            toastr.success('Cliente eliminado', 'Eliminado');
        });

        // Limpiar los inputs de monto al hacer clic
        $('#input-amountEfectivo').on('click', function () {            
            $typeSale = $('#select-payment_type').val();
            if($typeSale == 'Efectivo' || $typeSale == 'Ambos'){
                $('#input-amountEfectivo').val('');
            }
        });
        // $('#input-amountQr').on('click', function () {
        //     $('#input-amountQr').val('');
        // });


        // Cambiar el estado de los inputs de monto según el tipo de pago seleccionado
        $('#select-payment_type').change(function() {
            $typeSale = $(this).val();
            calculateTotal();

            salectPaytmentStatus();

            amountRecived();
        });

        // Función para manejar el cambio en los inputs de monto
        function salectPaytmentStatus()
        {
            $typeSale = $('#select-payment_type').val();
            const total = parseFloat($('#amountTotalSale').val()) || 0;
            if ($typeSale === 'Efectivo') {
                $('#input-amountEfectivo').removeAttr('readonly');
                $('#input-amountEfectivo').removeAttr('max');
                $('#input-amountEfectivo').attr('min', total);

                $('#input-amountQr').val(0);
                $('#input-amountQr').attr('readonly', true); // Hacer el campo de solo lectura
                $('#input-amountQr').attr('min', 0); // Asignar el valor minimo al monto total de la venta si es por Qr la venta
                $('#input-amountQr').attr('max', 0); // Asignar el valor máximo al monto total de la venta si es por Qr la venta
            }
            if ($typeSale === 'Qr') {
                $('#input-amountEfectivo').val(0);
                $('#input-amountEfectivo').attr('readonly', true); // Hacer el campo de solo lectura
                $('#input-amountEfectivo').attr('min', 0); // Asignar el valor minimo al monto total de la venta si es por Qr la venta
                $('#input-amountEfectivo').attr('max', 0); // Asignar el valor máximo al monto total de la venta si es por Qr la venta

                $('#input-amountQr').val(total);
                $('#input-amountQr').attr('readonly', true); // Hacer el campo de solo lectura
                $('#input-amountQr').attr('min', total); // Asignar el valor minimo al monto total de la venta si es por Qr la venta
                $('#input-amountQr').attr('max', total); // Asignar el valor máximo al monto total de la venta si es por Qr la venta
            } 
            if ($typeSale == 'Ambos') { // Ambos
                $('#input-amountEfectivo').removeAttr('readonly');
                $('#input-amountEfectivo').val(0);
                $('#input-amountEfectivo').attr('max', total);



                $('#input-amountQr').attr('readonly', true); // Hacer el campo de solo lectura
                $('#input-amountQr').attr('min', 0); // Asignar el valor minimo al monto total de la venta si es por Qr la venta
                $('#input-amountQr').attr('max', total); // Asignar el valor máximo al monto total de la venta si es por Qr la venta
            }
        }




        // Función para obtener el stock disponible de un producto
        function getStock(productId) {
            const $productCard = $(`.product-card[data-product-id="${productId}"]`);
            
            // Si es "Venta Sin Stock", devolver un número grande
            if($productCard.data('type-sale') === "Venta Sin Stock") {
                return 9999; // Número alto para que siempre se pueda agregar
            }
            
            // Para "Venta Con Stock", obtener el valor real
            const stockText = $productCard.find('small').text().trim();
            const stockMatch = stockText.match(/[\d.]+/);
            return stockMatch ? parseFloat(stockMatch[0]) : 0;
        }

        // Función para agregar productos al carrito con validación de stock
        function addToCart(productId, silent = false) {
            // Obtener información del producto
            const product = getProductById(productId);
            if(!product) return;

            // Obtener el tipo de venta y stock del producto
            const $productCard = $(`.product-card[data-product-id="${productId}"]`);
            const typeSale = $productCard.data('type-sale');
            const availableStock = typeSale === "Venta Con Stock" ? getStock(productId) : 9999;

            // Validación para productos con stock
            if(typeSale === "Venta Con Stock" && availableStock <= 0) {
                if(!silent) {
                    toastr.error('Producto sin stock disponible', 'Stock agotado');
                }
                return;
            }

            // Si el producto ya está en el carrito
            if(cart[productId]) {
                // Verificar que no exceda el stock (solo para productos con stock)
                if(typeSale === "Venta Con Stock" && cart[productId].quantity >= availableStock) {
                    if(!silent) {
                        toastr.warning(`No hay suficiente stock. Disponible: ${availableStock}`, 'Stock insuficiente');
                    }
                    return;
                }
                cart[productId].quantity += 1;
            } else {
                cart[productId] = {
                    id: product.id,
                    name: product.name,
                    price: product.price,
                    quantity: 1,
                    image: product.image,
                    typeSale: typeSale // Guardamos el tipo de venta para referencia
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
                    const availableStock = product.typeSale === "Venta Con Stock" ? getStock(productId) : 9999;
                    const subtotal = product.price * product.quantity;
                    total += subtotal;
                    
                    $tableBody.append(`
                        <tr class="tr-item" id="tr-item-${productId}">
                            <td class="td-item">${counter++}</td>
                            <td>
                                <b>${product.name}</b> <br>
                                ${product.typeSale === "Venta Con Stock" ? `<small class="text-muted">Disponible: ${availableStock}</small>` : ''}
                                <input type="hidden" name="products[${productId}][id]" value="${productId}">
                                <input type="hidden" name="products[${productId}][name]" value="${product.name}">
                                <input type="hidden" name="products[${productId}][typeSale]" value="${product.typeSale}">
                            </td>
                            <td style="text-align: right">
                                <input type="number" name="products[${productId}][price]" class="form-control input-price" readonly
                                    value="${product.price.toFixed(2)}" min="0.01" step="0.01" required>
                            </td>
                            <td>
                                <input type="number" name="products[${productId}][quantity]" class="form-control input-quantity" 
                                    value="${product.quantity}" min="1" max="${availableStock}" step="1" required>
                                    
                            </td>
                            <td class="text-right subtotal">${subtotal.toFixed(2)}</td>
                            <td class="text-right" style="padding: 8px; text-align: right;">
                                <button type="button" onclick="removeFromCart(${productId})"  title="Quitar" style="background-color: transparent; border: none;">
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
                        toastr.warning(`No hay suficiente stock. Disponible: ${maxStock}`, 'Stock insuficiente');
                        $input.val(maxStock);
                    }
                    
                    updateSubtotal($(this).closest('tr'));
                    calculateTotal();
                });
            }
            
            // Actualizar el total general
            calculateTotal();
            calculateChange();
        }

        // Función para actualizar subtotal con validación de stock
        function updateSubtotal($row) {
            const productId = $row.attr('id').replace('tr-item-', '');
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
            const input_amountEfectivo = parseFloat($('#input-amountEfectivo').val()) || 0;
            const input_amountQr = parseFloat($('#input-amountQr').val()) || 0;
            const total = parseFloat($('#amountTotalSale').val()) || 0;
            let total_inputs = input_amountEfectivo+input_amountQr;
            if (total_inputs >= total) {
                const change = total_inputs - total;
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
            $('.subtotal').each(function() {
                total += parseFloat($(this).text());
            });
            
            const finalTotal = total;
            
            $('#label-total').text(finalTotal.toFixed(2));
            $('#amountTotalSale').val(finalTotal.toFixed(2));            
            
            // Calcular el cambio nuevamente
            calculateChange();
            salectPaytmentStatus();
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

        function amountRecived() {
            const input_amountEfectivo = parseFloat($('#input-amountEfectivo').val()) || 0;
            // const input_amountQr = parseFloat($('#input-amountQr').val()) || 0
            $typeSale = $('#select-payment_type').val();
            const total = parseFloat($('#amountTotalSale').val()) || 0;

            if ($typeSale == 'Ambos') { // Ambos
                if (input_amountEfectivo > total) {
                    $('#input-amountQr').val(0);
                } else {
                    $('#input-amountQr').val((total - input_amountEfectivo).toFixed(2));
                }
            }
            calculateChange();
        }

        // Validar antes de enviar el formulario
        $('#form-sale').submit(function(e) {
            const total = parseFloat($('#amountTotalSale').val()) || 0;
            const input_amountEfectivo = parseFloat($('#input-amountEfectivo').val()) || 0;
            const input_amountQr = parseFloat($('#input-amountQr').val()) || 0;
            let total_inputs = input_amountEfectivo+input_amountQr;         

            if (total_inputs < total) {
                toastr.error(`El monto recibido no puede ser menor al total (Bs. ${total.toFixed(2)})`);
                $('#modal-confirm').modal('hide');
                calculateChange();
                e.preventDefault();
                return false;
            }
            
            // Validar que haya productos en el carrito
            if(Object.keys(cart).length === 0) {
                toastr.error('Debe agregar al menos un producto al carrito');
                $('#modal-confirm').modal('hide');
                e.preventDefault();
                return false;
            }
            
            return true;
        });
    </script>
@stop