@extends('voyager::master')

@section('page_title', 'Añadir Venta')

@section('page_header')
    {{-- <div class="container-fluid">
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
    </div> --}}
@stop

@section('content')
    <div class="page-content edit-add container-fluid" id="sale-pos-container">
        <form id="form-sale" action="{{ route('sales.store') }}" method="post">
            @csrf
            <div class="row">
                @if (!$cashier)
                    <div class="col-md-12 col-sm-12">
                        <div class="panel panel-bordered alert alert-warning">
                            <strong><i class="voyager-info-circled"></i> Advertencia:</strong>
                            <p class="mt-1">No puedes realizar ventas porque no tienes una caja abierta. Por favor, dirígete a la sección de <a href="{{ route('cashiers.index') }}">cajas</a> para aperturar una.</p>
                        </div>
                    </div>
                @endif

                {{-- Products Section --}}
                <div class="col-md-7 col-sm-12" id="products-container">
                    <div class="panel panel-bordered">
                        <div class="panel-body" style="padding: 0px">
                            <div class="row">
                                <div class="col-md-12" style="padding: 10px 25px;">
                                    <input type="text" id="input-search-products" class="form-control"
                                        placeholder="Buscar producto...">
                                </div>
                            </div>
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#tab-all" aria-controls="tab-all" role="tab" data-toggle="tab">Todos</a>
                                </li>
                                @foreach ($categories as $category)
                                    <li role="presentation">
                                        <a href="#tab-{{ $category->id }}" aria-controls="tab-{{ $category->id }}"
                                            role="tab" data-toggle="tab">{{ $category->name }}</a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content" style="padding: 15px; height: calc(100vh - 250px); overflow-y: auto;">
                                @php
                                    $all_products = $categories->flatMap(function ($category) {
                                        return $category->itemSales;
                                    });
                                @endphp
                                <!-- Tab Todos los productos -->
                                <div role="tabpanel" class="tab-pane active" id="tab-all">
                                    <div class="row">
                                        @forelse ($all_products as $product)
                                            @include('sales.partials.product-card', [
                                                'product' => $product,
                                            ])
                                        @empty
                                            <div class="col-md-12">
                                                <p class="text-center">No hay productos disponibles.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- Tabs por categoría -->
                                @foreach ($categories as $category)
                                    <div role="tabpanel" class="tab-pane" id="tab-{{ $category->id }}">
                                        <div class="row">
                                            @forelse ($category->itemSales as $product)
                                                @include('sales.partials.product-card', [
                                                    'product' => $product,
                                                ])
                                            @empty
                                                <div class="col-md-12">
                                                    <p class="text-center">No hay productos en esta categoría.</p>
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Cart Section (Desktop) --}}
                <div class="col-md-5 hidden-xs hidden-sm" id="cart-container">
                    <div class="panel panel-bordered">
                        <div class="panel-body"
                            style="padding: 10px 0px; display: flex; flex-direction: column; height: calc(100vh - 140px);">
                            {{-- Cart Items --}}
                            <div id="cart-items" style="height: 35vh; overflow-y: auto; padding: 0 15px;">
                                <div class="table-responsive">
                                    <table id="dataTable" class="table table-hover">
                                        <tbody id="table-body" class="cart-items-list">
                                            <tr id="tr-empty">
                                                <td colspan="4" style="height: 280px">
                                                    <h4 class="text-center text-muted" style="margin-top: 80px">
                                                        <i class="glyphicon glyphicon-shopping-cart"
                                                            style="font-size: 50px"></i> <br><br>
                                                        El carrito está vacío
                                                    </h4>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Cart Summary and Actions --}}
                            <div id="cart-summary" style="padding: 0 15px; flex-grow: 1; overflow-y: auto;">
                                <div class="form-group col-md-12">
                                    <label for="observation">Detalle / Observación</label>
                                    <textarea name="observation" id="observation" class="form-control" rows="2"></textarea>
                                </div>

                                <div class="form-group col-md-12">
                                    <label for="person_id">Cliente</label>
                                    <div class="input-group">
                                        <select name="person_id" id="select-person_id" class="form-control"></select>
                                        <span class="input-group-btn">
                                            <button id="trash-person" class="btn btn-default" title="Quitar Cliente"
                                                style="margin: 0px" type="button">
                                                <i class="voyager-trash"></i>
                                            </button>
                                            <button class="btn btn-primary" title="Nuevo cliente"
                                                data-target="#modal-create-person" data-toggle="modal" style="margin: 0px"
                                                type="button">
                                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                                            </button>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="payment_type">Método de pago</label>
                                    <select name="paymentType" id="select-payment_type" class="form-control select2"
                                        required>
                                        <option value="" disabled selected>Seleccionar método de pago</option>
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Qr"><i class="fa-solid fa-qrcode"></i> Qr/Transferencia
                                        </option>
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
                                    <input type="number" readonly onkeyup="amountRecived()" onchange="amountRecived()"
                                        name="amountReceivedEfectivo" id="input-amountEfectivo" style="text-align: right"
                                        min="0" value="0" step="0.1" class="form-control"
                                        placeholder="Monto Efectivo." required>
                                </div>


                                <div class="form-group col-md-6">
                                    <label for="date">Monto "QR"</label>
                                    <input type="number" readonly name="amountReceivedQr" id="input-amountQr"
                                        style="text-align: right" min="0" value="0" step="0.1"
                                        class="form-control" placeholder="Monto Qr." required>
                                </div>

                                <div class="form-group col-md-6">

                                </div>

                                <div class="form-group col-md-6">
                                    <h3 class="text-right" id="change-message" style="display: none;"><small>Cambio:
                                            Bs.</small> <b id="change-amount">0.00</b></h3>
                                    <h3 class="text-right" id="change-message-error" style="display: none;"><small
                                            style="color: red !important">Monto faltante: Bs. </small><b
                                            id="missing-amount" style="color: red !important">0.00</b></h3>
                                    <h3 class="text-right"><small>Total a cobrar: Bs.</small> <b id="label-total">0.00</b>
                                    </h3>
                                    <input type="hidden" id="amountTotalSale" name="amountTotalSale" value="0">
                                </div>

                                <div class="form-group col-md-12 text-center">
                                    <button type="submit" id="btn-confirm"
                                        class="btn btn-primary btn-block btn-confirm">Vender <i
                                            class="voyager-basket"></i></button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    {{-- Botón flotante para carrito en móviles --}}
    <div id="mobile-cart-button" class="hidden-md hidden-lg">
        <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-cart-mobile">
            <i class="glyphicon glyphicon-shopping-cart"></i> Ver Carrito (<span id="mobile-cart-count">0</span>) - Bs.
            <span id="mobile-cart-total">0.00</span>
        </button>
    </div>

    <!-- Modal Carrito para Móviles -->
    <div class="modal fade" id="modal-cart-mobile" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Carrito de Compras</h4>
                </div>
                <div class="modal-body" id="mobile-cart-content" style="padding: 0;"></div>
            </div>
        </div>
    </div>

    <!-- Modal crear cliente -->
    @include('partials.modal-registerPerson')
@stop

@section('css')
    <style>
        #sale-pos-container {
            height: calc(100vh - 120px);
            overflow: hidden;
        }

        #products-container,
        #cart-container,
        #products-container .panel,
        #cart-container .panel {
            height: calc(100vh - 120px);
            height: 100%;
            margin-bottom: 0;
        }

        .product-card {
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
            background-color: #fff;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .product-card.out-of-stock {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .product-card.out-of-stock::after {
            content: 'Agotado';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(217, 83, 79, 0.8);
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
        }

        .product-card img {
            height: 100px;
            width: 100%;
            object-fit: cover;
        }

        .product-info {
            padding: 10px;
            flex-grow: 1;
        }

        .product-info h5 {
            margin: 5px 0;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-weight: 600;
        }

        .product-info p {
            margin: 0;
            font-weight: bold;
        }

        .nav-tabs {
            background: #f5f5f5;
            padding-left: 15px;
        }

        .form-group {
            margin-bottom: 10px !important;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-control .form-control {
            text-align: center;
            width: 50px;
            border-left: none;
            border-right: none;
            border-radius: 0;
        }

        .cart-item-details {
            display: flex;
            align-items: center;
        }

        .cart-item-details img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 10px;
        }

        #mobile-cart-button {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            z-index: 1050;
            padding: 10px;
            background-color: #f8f8f8;
            border-top: 1px solid #ddd;
        }

        @media (max-width: 991px) {
            #products-container .tab-content {
                height: calc(100vh - 300px);
                /* Ajustar altura para dejar espacio al botón flotante */
            }

            #modal-cart-mobile .panel {
                height: auto;
                /* Anular la altura fija del panel dentro del modal */
                border: none;
                box-shadow: none;
            }
        }

        @keyframes flash-total {
            0% {
                transform: scale(1);
                background-color: transparent;
            }

            50% {
                transform: scale(1.1);
                background-color: #fffbe6;
            }

            100% {
                transform: scale(1);
                background-color: transparent;
            }
        }

        .total-updated {
            display: inline-block;
            padding: 0 5px;
            border-radius: 5px;
            animation: flash-total 0.6s ease-out;
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

        $(document).ready(function() {

            // Inicializar Select2 para el select de tipo de pago con iconos (efectivo y qr)
            $('#select-payment_type').select2({
                templateResult: formatOption,
                templateSelection: formatOption,
                // dropdownParent: $('#modal-cart-mobile'),
                width: '100%'
            });

            // Solución para que el buscador funcione dentro de un modal en móviles
            $('#typeSale').select2({
                // dropdownParent: $('#modal-cart-mobile'),
                width: '100%'
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
                    return $(
                        '<span><i class="fa-solid fa-money-bill-1-wave"></i> + <i class="fa-solid fa-qrcode"></i> ' +
                        option.text + '</span>');
                }
                return option.text;
            }


            // Configurar eventos de clic para los productos (excluyendo los agotados)
            $('.product-card:not(.out-of-stock)').on('click', function() {
                const now = Date.now();
                if (now - lastClickTime < CLICK_DELAY) return;
                lastClickTime = now;

                const productId = $(this).data('product-id');
                addToCart(productId);
            });

            // Configurar eventos del formulario
            $('#form-sale').submit(function(e) {
                $('.btn-confirm').html('Guardando... <i class="fa fa-spinner fa-spin"></i>');
                $('.btn-confirm').attr('disabled', true);
            });

            // Evento para el modal de confirmación cuando se cierra vulve a habilitar el botón
            // $('#modal-confirm').on('hidden.bs.modal', function() {
            //     $(this).find('.btn-confirm').removeAttr('disabled');
            //     $(this).find('.btn-confirm').val('Confirmar venta');
            // });

            // Buscador de productos
            $('#input-search-products').on('keyup', function() {
                let value = $(this).val().toLowerCase();
                $('.product-card-wrapper').filter(function() {
                    let productName = $(this).find('h5').text().toLowerCase();
                    let parentDiv = $(this).parent();
                    let matches = productName.indexOf(value) > -1;
                    parentDiv.toggle(matches);
                });
            });

            // Mover el contenido del carrito al modal en vista móvil
            if ($(window).width() < 992) {
                $('#cart-container .panel-body').appendTo('#mobile-cart-content');
                // Ajustar altura para el modal
                $('#mobile-cart-content #cart-items').css('height', '40vh');
                $('#mobile-cart-content #cart-summary').css('height', 'auto');
            }


        });

        $('#trash-person').on('click', function() {
            // $('#input-dni').val('');
            $('#select-person_id').val(null).trigger('change');

            toastr.success('Cliente eliminado', 'Eliminado');
        });

        // Limpiar los inputs de monto al hacer clic
        $('#input-amountEfectivo').on('click', function() {
            $typeSale = $('#select-payment_type').val();
            if ($typeSale == 'Efectivo' || $typeSale == 'Ambos') {
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
        function salectPaytmentStatus() {
            $typeSale = $('#select-payment_type').val();
            const total = parseFloat($('#amountTotalSale').val()) || 0;
            if ($typeSale === 'Efectivo') {
                $('#input-amountEfectivo').removeAttr('readonly');
                $('#input-amountEfectivo').removeAttr('max');
                $('#input-amountEfectivo').attr('min', total);

                $('#input-amountQr').val(0);
                $('#input-amountQr').attr('readonly', true); // Hacer el campo de solo lectura
                $('#input-amountQr').attr('min',
                    0); // Asignar el valor minimo al monto total de la venta si es por Qr la venta
                $('#input-amountQr').attr('max',
                    0); // Asignar el valor máximo al monto total de la venta si es por Qr la venta
            }
            if ($typeSale === 'Qr') {
                $('#input-amountEfectivo').val(0);
                $('#input-amountEfectivo').attr('readonly', true); // Hacer el campo de solo lectura
                $('#input-amountEfectivo').attr('min', 0); // Asignar el valor minimo al monto total de la venta si es por Qr la venta
                $('#input-amountEfectivo').attr('max',
                    0); // Asignar el valor máximo al monto total de la venta si es por Qr la venta

                $('#input-amountQr').val(total);
                $('#input-amountQr').attr('readonly', true); // Hacer el campo de solo lectura
                $('#input-amountQr').attr('min',
                    total); // Asignar el valor minimo al monto total de la venta si es por Qr la venta
                $('#input-amountQr').attr('max',
                    total); // Asignar el valor máximo al monto total de la venta si es por Qr la venta
            }
            if ($typeSale == 'Ambos') { // Ambos
                $('#input-amountEfectivo').removeAttr('readonly');
                $('#input-amountEfectivo').val(0);
                $('#input-amountEfectivo').attr('min', 0);
                $('#input-amountEfectivo').attr('max', total);

                



                $('#input-amountQr').attr('readonly', true); // Hacer el campo de solo lectura
                $('#input-amountQr').attr('min',0); // Asignar el valor minimo al monto total de la venta si es por Qr la venta
                $('#input-amountQr').attr('max',
                    total); // Asignar el valor máximo al monto total de la venta si es por Qr la venta
            }
        }




        // Función para obtener el stock disponible de un producto
        function getStock(productId) {
            const $productCard = $(`.product-card[data-product-id="${productId}"]`);

            // Si es "Venta Sin Stock", devolver un número grande
            if ($productCard.data('type-sale') === "Venta Sin Stock") {
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
            if (!product) return;

            // Obtener el tipo de venta y stock del producto
            const $productCard = $(`.product-card[data-product-id="${productId}"]`);
            const typeSale = $productCard.data('type-sale');
            const availableStock = typeSale === "Venta Con Stock" ? getStock(productId) : 9999;

            // Validación para productos con stock
            if (typeSale === "Venta Con Stock" && availableStock <= 0) {
                if (!silent) {
                    toastr.error('Producto sin stock disponible', 'Stock agotado');
                }
                return;
            }

            // Si el producto ya está en el carrito
            if (cart[productId]) {
                // Verificar que no exceda el stock (solo para productos con stock)
                if (typeSale === "Venta Con Stock" && cart[productId].quantity >= availableStock) {
                    if (!silent) {
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
            if (!silent) {
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

            if (Object.keys(cart).length === 0) {
                $tableBody.append(`
                    <tr id="tr-empty">
                        <td colspan="4" style="height: 280px">
                            <h4 class="text-center text-muted" style="margin-top: 80px">
                                <i class="glyphicon glyphicon-shopping-cart" style="font-size: 50px"></i> <br><br>
                                El carrito está vacío
                            </h4>
                        </td>
                    </tr>
                `);
            } else {
                let counter = 1;
                let total = 0;

                for (const productId in cart) {
                    const product = cart[productId];
                    const availableStock = product.typeSale === "Venta Con Stock" ? getStock(productId) : 9999;
                    const subtotal = product.price * product.quantity;
                    total += subtotal;

                    let image = product.image ? product.image.replace('.jpg', '-cropped.webp').replace('.png',
                        '-cropped.webp') : '{{ asset('images/default.jpg') }}';

                    $tableBody.append(`
                        <tr class="tr-item" id="tr-item-${productId}">
                            <td style="width:120px">
                                <div class="quantity-control">
                                    <button type="button" class="btn btn-default btn-sm" onclick="updateQuantity(${productId}, -1)">-</button>
                                    <input type="number" name="products[${productId}][quantity]" class="form-control input-quantity" 
                                        value="${product.quantity}" min="1" max="${availableStock}" step="1" required onchange="updateQuantity(${productId}, 0, this.value)">
                                    <button type="button" class="btn btn-default btn-sm" onclick="updateQuantity(${productId}, 1)">+</button>
                                </div>
                            </td>
                            <td class="cart-item-details">
                                <button type="button" onclick="removeFromCart(${productId})" title="Quitar" class="btn btn-link btn-sm remove-item-btn">
                                    <i class="voyager-x"></i>
                                </button>
                                <img src="${image}" alt="${product.name}">
                                <div>
                                    <b>${product.name}</b> <br>
                                    <small class="text-muted">Bs. ${product.price.toFixed(2)}</small>
                                </div>
                                <input type="hidden" name="products[${productId}][id]" value="${productId}">
                                <input type="hidden" name="products[${productId}][name]" value="${product.name}">
                                <input type="hidden" name="products[${productId}][typeSale]" value="${product.typeSale}">
                                <input type="number" name="products[${productId}][price]" class="form-control input-price" readonly
                                    value="${product.price.toFixed(2)}" min="0.01" step="0.01" required style="display:none;">
                            </td>
                            <td class="text-right subtotal" style="width: 80px"><b>${subtotal.toFixed(2)}</b></td>
                        </tr>
                    `);
                }
            }

            // Actualizar el total general
            calculateTotal();
            calculateChange();

            // Actualizar contador del botón flotante
            const itemCount = Object.keys(cart).length;
            $('#mobile-cart-count').text(itemCount);

        }

        function updateQuantity(productId, change, directValue = null) {
            if (!cart[productId]) return;

            let newQuantity;
            if (directValue !== null) {
                newQuantity = parseInt(directValue);
            } else {
                newQuantity = cart[productId].quantity + change;
            }

            const availableStock = cart[productId].typeSale === "Venta Con Stock" ? getStock(productId) : 9999;

            if (newQuantity <= 0) {
                removeFromCart(productId);
                return;
            }

            if (newQuantity > availableStock) {
                toastr.warning(`No hay suficiente stock. Disponible: ${availableStock}`, 'Stock insuficiente');
                newQuantity = availableStock;
            }

            cart[productId].quantity = newQuantity;
            updateCartTable();
        }

        // Función para actualizar subtotal con validación de stock
        function updateSubtotal($row) {
            const productId = $row.attr('id').replace('tr-item-', '');
            const price = parseFloat($row.find('.input-price').val()) || 0;
            const quantity = parseInt($row.find('.input-quantity').val()) || 0;
            const subtotal = price * quantity;

            $row.find('.subtotal').text(subtotal.toFixed(2));

            // Actualizar también en el objeto cart
            if (cart[productId]) {
                cart[productId].price = price;
                cart[productId].quantity = quantity;
            }
        }

        // Función para calcular y mostrar el cambio
        function calculateChange() {
            const input_amountEfectivo = parseFloat($('#input-amountEfectivo').val()) || 0;
            const input_amountQr = parseFloat($('#input-amountQr').val()) || 0;
            const total = parseFloat($('#amountTotalSale').val()) || 0;
            let total_inputs = input_amountEfectivo + input_amountQr;
            if (total_inputs >= total) {
                $('#missing-amount').text('0.00');
                const change = total_inputs - total;
                $('#change-message-error').hide();
                $('#change-message').show();
                $('#change-amount').text(change.toFixed(2));
            } else {
                $('#missing-amount').text((total - total_inputs).toFixed(2));
                $('#change-message').hide();
                $('#change-message-error').show();
            }
        }

        // Función para calcular el total
        function calculateTotal() {
            let total = 0;
            $('.subtotal').each(function() {
                total += parseFloat($(this).text().replace(/,/g, ''));
            });

            const finalTotal = total;

            $('#label-total').text(finalTotal.toFixed(2));
            $('#amountTotalSale').val(finalTotal.toFixed(2));
            $('#mobile-cart-total').text(finalTotal.toFixed(2));

            // Animar el total
            const $totalElements = $('#label-total, #mobile-cart-total');
            $totalElements.addClass('total-updated');
            setTimeout(() => {
                $totalElements.removeClass('total-updated');
            }, 600); // Duración de la animación en ms

            // Calcular el cambio nuevamente
            calculateChange();
            salectPaytmentStatus();
        }

        function removeFromCart(productId) {
            if (cart[productId]) {
                delete cart[productId];
                updateCartTable();
                toastr.info('Producto eliminado del carrito', '', {
                    timeOut: 1000
                });
            }
        }

        function getProductById(productId) {
            const $productCard = $(`.product-card[data-product-id="${productId}"]`);
            if (!$productCard.length) return null;
            return {
                id: productId,
                name: $productCard.find('h5').first().text().trim(),
                price: parseFloat($productCard.find('.text-success').text().replace('Bs. ', '').replace(',', '.')),
                image: $productCard.find('img').attr('src')
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
            let total_inputs = input_amountEfectivo + input_amountQr;

            if (total_inputs < total) {
                toastr.error(`El monto recibido no puede ser menor al total (Bs. ${total.toFixed(2)})`);
                $('#modal-confirm').modal('hide');
                calculateChange();
                e.preventDefault();
                return false;
            }

            // Validar que haya productos en el carrito
            if (Object.keys(cart).length === 0) {
                toastr.error('Debe agregar al menos un producto al carrito');
                $('#modal-confirm').modal('hide');
                e.preventDefault();
                return false;
            }

            return true;
        });
    </script>
@stop
