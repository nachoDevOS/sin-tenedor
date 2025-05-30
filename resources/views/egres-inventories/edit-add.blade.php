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
        <form id="form-sale" action="{{ route('sales.store') }}" method="post">
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
                                <label for="input-dni">Detalle / Observación</label>
                                <textarea name="observation" id="observation" class="form-control" rows="3"></textarea>
                            </div>

                           
                            <div class="form-group col-md-12 text-center">
                                <button type="button" id="btn-submit" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-confirm">Vender <i class="voyager-basket"></i></button>
                               
                                <a href="{{ route('sales.index') }}" >Volver a la lista</a>
                            </div>
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
        $(document).ready(function(){
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
                
                    return opt.id?opt.id:'<i class="fa fa-search"></i> Buscar... ';
                }
            }).change(function(){
                
            });
        });

        function formatItemResult(option){
            if (option.loading) {
                return '<span class="text-center"><i class="fas fa-spinner fa-spin"></i> Buscando...</span>';
            }
            let image = "{{ asset('images/default.jpg') }}";
            if(option.item_inventory.image){
                image = "{{ asset('storage') }}/"+option.item_inventory.image.replace('.', '-medium.');
            }

                // Mostrar las opciones encontradas
            return $(`<div style="display: flex">
                        <div style="margin: 0px 10px">
                            <img src="${image}" width="60px" />
                        </div>
                        <div>
                            <b style="font-size: 16px">${option.item_inventory.name}<br>
                            <small>Stock: ${option.stock}</small><br>
                            <span><b>Descripción</b>: ${option.item_inventory.observation?option.item_inventory.observation:'Sin Detalles'}</span>
                        </div>
                    </div>`);
        }

       

      
    </script>
@stop