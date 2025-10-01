@extends('voyager::master')

@section('page_title', 'Añadir Egresos')

@section('page_header')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body" style="padding: 0px">
                        <div class="col-md-8" style="padding: 0px">
                            <h1 class="page-title">
                                <i class="fa-solid fa-cart-shopping"></i> Añadir Egresos
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
        <form id="form-egres" action="{{ route('egres-inventories.store') }}" method="post">
            @csrf
            <div class="row">                
                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body" style="padding: 10px 0px">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="select-product_id">Buscar producto</label>
                                    <select class="form-control" id="select-product_id"></select>
                                </div>
                            </div>
                            <div class="col-md-12" style="height: 300px; max-height: 300px; overflow-y: auto">
                                <div class="table-responsive">
                                    <table id="dataTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 30px">N&deg;</th>
                                                <th>Item</th>
                                                <th style="text-align: center; width: 35%">Detalles</th>
                                                <th style="text-align: center; width:10%">Stock</th>
                                                <th style="text-align: center; width:12%">Cant Dispensar</th>
                                                <th style="width: 30px"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-body">
                                            <tr id="tr-empty">
                                                <td colspan="6" style="height: 240px">
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
                                <label for="input-dni">Detalle / Observación</label>
                                <textarea name="observation" id="observation" class="form-control" rows="3"></textarea>
                            </div>

                           
                            <div class="form-group col-md-12 text-center">
                                <button type="button" id="btn-submit" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-confirm">Registrar Salidas <i class="voyager-basket"></i></button>
                               
                                <a href="{{ route('egres-inventories.index') }}" >Volver a la lista</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class="modal fade" data-backdrop="static" id="modal-confirm" role="dialog">
                <div class="modal-dialog modal-primary">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" style="color:rgb(255, 255, 255) !important"><i class="fa-solid fa-cart-shopping"></i> ¿Estás seguro que quieres registrar?</h4>
                        </div>
                        <div class="modal-body">
                            <div class="text-center" style="text-transform: uppercase;">
                                <div style="font-size: 5em; color: #62a8ea; margin-bottom: 15px;">
                                    <i class="fa-solid fa-cart-shopping"></i>
                                </div>
                                <h4 style="margin-top: 0; color: #0c0c0c;">
                                    <strong>¿CONFIRMAR REGISTRO?</strong>
                                </h4>
                            </div>
                            <label class="checkbox-inline">
                                <input type="checkbox" required>Confirmar..!
                            </label>
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


@stop

@section('css')

@stop

@section('javascript')

    <script>
        var productSelected;

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
                            "timeOut": "2000",
                            "extendedTimeOut": "1000",
                            "showEasing": "swing",
                            "hideEasing": "linear",
                            "showMethod": "fadeIn",
                            "hideMethod": "fadeOut"
                        };
        $(document).ready(function(){

            $('#form-egres').submit(function(e){
                $('.btn-confirm').val('Guardando...');
                $('.btn-confirm').attr('disabled', true);
            });
            $('#select-product_id').select2({
                placeholder: '<i class="fa fa-search"></i> Buscar...',
                escapeMarkup : function(markup) {
                    return markup;
                },
                language: {
                    inputTooShort: function (data) {
                        return `Por favor ingrese ${data.minimum - data.input.length} o más caracteres`;
                    },
                    noResults: function () {
                        return `<i class="far fa-frown"></i> No hay resultados encontrados`;
                    }
                },
                quietMillis: 250,
                minimumInputLength: 1,
                ajax: {
                    // url: "{{ url('admin/ajax/personList') }}",        
                    url: "{{ url('admin/egres-inventories/stock/ajax') }}",  
                    processResults: function (data) {
                        let results = [];
                        data.map(data =>{
                            results.push({
                                ...data,
                                disabled: false
                            });
                        });
                        return {
                            results
                        };
                    },
                    cache: true
                },
                templateResult: formatItemResult,
                templateSelection: (opt) => {
                    productSelected = opt;
                    return opt.id?opt.name:'<i class="fa fa-search"></i> Buscar... ';
                }
            }).change(function(){
                if($('#select-product_id option:selected').val()){
                    let product = productSelected;
                    // alert(product.id)
                    let image = "{{ asset('images/default.jpg') }}";
                    if(product.image) {
                        const lastDotIndex = product.image.lastIndexOf('.');
                        const baseName = lastDotIndex !== -1 ? product.image.substring(0, lastDotIndex) : product.image;
                        image = `${window.storagePath}${baseName}-cropped.webp`;
                    }


                    if($('.table').find(`#tr-item-${product.id}`).val() === undefined){
                        $('#table-body').append(`
                            <tr class="tr-item" id="tr-item-${product.id}">
                                <td class="td-item"></td>
                                <td>
                                    <input type="hidden" name="products[${product.id}][id]" value="${product.id}"/>
                                    <input type="hidden" name="products[${product.id}][dispensingType]" value="${product.dispensingType}"/>
                                    <div style="display: flex; align-items: center;">
                                        <div style="margin-right: 15px; flex-shrink: 0;">
                                            <img src="${image}" width="60px" style="border-radius: 4px;"/>
                                        </div>
                                        <div style="flex-grow: 1;">
                                            <div style="font-size: 14px; font-weight: bold; margin-bottom: 1px;">${product.name}</div>
                                            <div style="margin-bottom: 0px;"><small>Stock: ${product.total_stock}</small></div>
                                            <div style="color: #666;"><b>Dispensación:</b> ${product.dispensingType}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <textarea name="products[${product.id}][observation]" class="form-control" rows="2"></textarea>
                                </td>
                                <td style="text-align: right; vertical-align: middle;">
                                    <h4 class="label-stock" id="label-stock-${product.id}" style="margin: 0;">${product.total_stock}</h4>
                                </td>
                                <td width="100px" style="vertical-align: middle;">
                                    <input type="number" name="products[${product.id}][quantity]" ${product.dispensingType=='Entera'? 'step="1" min="1" ':'step="0.1" min="0.1"'} style="text-align: right" class="form-control" id="input-quantity-${product.id}" value="1" max="${product.total_stock}" required/>
                                </td>
                                <td width="50px" class="text-right" style="vertical-align: middle;">
                                    <button type="button" onclick="removeTr(${product.id})" class="btn btn-link">
                                        <i class="voyager-trash text-danger"></i>
                                    </button>
                                </td>
                            </tr>
                        `);

                        setNumber();
                        $(`#select-price-${product.id}`).select2({tags: true});
                        
                        
                        toastr.success(`+1 ${product.name}`, 'Producto agregado');
                    }else{
                        toastr.info('EL producto ya está agregado', 'Información')
                    }

                    $('#select-product_id').val('').trigger('change');
                }
            });
        });

        function formatItemResult(option){
            if (option.loading) {
                return '<span class="text-center"><i class="fas fa-spinner fa-spin"></i> Buscando...</span>';
            }
            let image = "{{ asset('images/default.jpg') }}";
            // if(option.image){
            //     image = "{{ asset('storage') }}/"+option.image.replace('.', '-medium.');
            // }

            if (option.image) {
                const lastDotIndex = option.image.lastIndexOf('.');
                const baseName = lastDotIndex !== -1 ? option.image.substring(0, lastDotIndex) : option.image;
                image = `${window.storagePath}${baseName}-cropped.webp`;
            }

                // Mostrar las opciones encontradas
            return $(`<div style="display: flex">
                        <div style="margin: 0px 10px">
                            <img src="${image}" width="60px" />
                        </div>
                        <div>
                            <b style="font-size: 16px">${option.name}<br>
                            <small>Stock: ${option.total_stock}</small><br>
                            <span><b>Descripción</b>: ${option.observation?option.observation:'Sin Detalles'}</span>
                        </div>
                    </div>`);
        }

        function setNumber(){

            var length = 0;
            $(".td-item").each(function(index) {
                $(this).text(index +1);
                length++;
            });

            if(length > 0){
                $('#tr-empty').css('display', 'none');
            }else{
                $('#tr-empty').fadeIn('fast');
            }
        }
        function removeTr(id){
            $(`#tr-item-${id}`).remove();
            $('#select-product_id').val("").trigger("change");
            setNumber();
            toastr.info('Producto eliminado del carrito', 'Eliminado');

            // getTotal();
        }

       

      
    </script>
@stop