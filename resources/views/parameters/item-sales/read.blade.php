@extends('voyager::master')

@section('page_title', 'Ver Productos en Ventas')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-bag"></i> Productos en Ventas &nbsp;
        <a href="{{ route('voyager.item-sales.index') }}" class="btn btn-warning">
            <span class="glyphicon glyphicon-list"></span>&nbsp;
            Volver a la lista
        </a> 
    </h1>
@stop

@section('content')
    <div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Categoría</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $item->category->name }} </p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-4">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Productos/ Items</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $item->name }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-4">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Precio</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ number_format($item->price, 2, ',', '.') }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>

                        <div class="col-md-4">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Tipo de Ventas</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{$item->typeSale}}</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-4">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Observación / Descripción</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{$item->observation??'Sin Detalles'}}</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-4">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Stock Disponible</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{$item->itemSalestocks->where('type','Ingreso')->where('deleted_at', null)->sum('stock')}}</small></p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                    </div>                    
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <h4>
                                    Detalles del Inventario
                                </h4>
                            </div>
                            <div class="col-sm-6 text-right">
                                @if (auth()->user()->hasPermission('browse_item_sales'))
                                    <button class="btn btn-success"                                      
                                        data-target="#modal-register-stock" data-toggle="modal" data-toggle="modal" style="margin: 0px">
                                        <i class="fa-solid fa-plus"></i> Agregar                                  
                                    </button>       
                                @endif                         
                            </div>  
                        </div>
                        <div class="row" id="div-results" style="min-height: 120px">
                            <div class="form-group col-md-12">
                                <div class="table-responsive">
                                    <table id="dataTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width:5px">N&deg;</th>
                                                <th style="text-align: center; width:15%">Cant. Ingresada</th>
                                                <th style="text-align: center; width:15%">Cant. Disponible</th>
                                                <th style="text-align: center">Detalles</th>                     
                                                <th style="text-align: center; width:10%">Estado</th>
                                                <th style="text-align: center; width:10%">Acciones</th>

                                            </tr>
                                        </thead>
                                        <tbody>    
                                            @php
                                                $i=1;
                                            @endphp 
                                            @forelse ($item->itemSalestocks as $value)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td style="text-align: right">                                                    
                                                        {{-- @if ($item->deleted_at)
                                                            <del style="color: red">{{number_format($item->quantity, 2, ',', '.')}}</del>
                                                        @else
                                                            {{number_format($item->quantity, 2, ',', '.')}}
                                                        @endif --}}
                                                        {{number_format($value->quantity, 2, ',', '.')}}
                                                    </td>
                                                    <td style="text-align: right">    
                                                        {{number_format($value->stock, 2, ',', '.')}}
                                                    </td>
                                                
                                                    <td>                                                    
                                                        {{$value->observation}}
                                                    </td>
                                                    <td style="text-align: center">
                                                        @if ($value->stock==0)
                                                            <label class="label label-danger">Stock Agotado</label> 
                                                        @else
                                                            <label class="label label-success">Stock Disponible</label> 
                                                        @endif
                                                    </td>

                                                    <td style="text-align: center">                                                   
                                                        @if ($value->deleted_at)
                                                            <span style="color: red">Eliminado</span>
                                                        @else
                                                            @if ($value->quantity == $value->stock)
                                                                <a href="#" onclick="deleteItem('{{ route('item-sales-stock.destroy', ['id' => $item->id, 'stock'=>$value->id]) }}')" title="Eliminar" data-toggle="modal" data-target="#modal-delete" class="btn btn-sm btn-danger delete">
                                                                    <i class="voyager-trash"></i>
                                                                </a>                         
                                                            @endif

                                                            
                                                        @endif
                                                    </td>
                                                </tr>
                                                @php
                                                    $i++;
                                                @endphp
                                            @empty
                                                <tr>
                                                    <td colspan="6">
                                                        <h5 class="text-center" style="margin-top: 50px">
                                                            <img src="{{ asset('images/empty.png') }}" width="120px" alt="" style="opacity: 0.8">
                                                            <br><br>
                                                            No hay resultados
                                                        </h5>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            {{-- <tr>
                                                <td colspan="4" style="text-align: right">Total</td>
                                                <td style="text-align: right">{{number_format($quantity, 2, ',', '.')}}</td>
                                                <td style="text-align: right">{{number_format($price, 2, ',', '.')}}</td>
                                                <td style="text-align: right">{{number_format($subTotal, 2, ',', '.')}}</td>
                                                <td style="text-align: right">{{number_format($priceSale, 2, ',', '.')}}</td>
                                                <td style="text-align: right">{{number_format($subTotalsale, 2, ',', '.')}}</td>
                                                <td style="text-align: right">{{number_format($stock, 2, ',', '.')}}</td>
                                            </tr> --}}
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <form action="{{ route('item-sales-stock.store', ['id' => $item->id]) }}" class="form-submit" method="POST">
        <div class="modal fade" data-backdrop="static" id="modal-register-stock" role="dialog">
            <div class="modal-dialog modal-success">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" style="color: #ffffff !important"><i class="voyager-plus" ></i> Registrar Stock</h4>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="full_name">Cantidad a Ingresar</label>
                                <input style="text-align: right" type="number" step="1" min="1" name="quantity" class="form-control" value="1" required>
                            </div>
                        </div>    
                        <div class="form-group">
                            <label for="observation">Observación / Detalles</label>
                            <textarea name="observation" class="form-control" rows="3"></textarea>
                        </div>

                        <label class="checkbox-inline">
                            <input type="checkbox" required>Confirmar..!
                        </label>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <input type="submit" class="btn btn-success btn-form-submit" value="Guardar">
                    </div>
                </div>
            </div>
        </div>
    </form>
    @include('partials.modal-delete')
    
@stop

@section('css')
    <style>

    </style>
@stop

@section('javascript')

    <script>
        $(document).ready(function(){   
            $('.form-submit').submit(function(e){
                $('.btn-form-submit').attr('disabled', true);
                $('.btn-form-submit').val('Guardando...');
            });

            $('#delete_form').submit(function(e){
                $('.btn-form-delete').attr('disabled', true);
                $('.btn-form-delete').val('Eliminando...');
            });
        });

        function deleteItem(url){
            $('#delete_form').attr('action', url);
        }
    </script>
    
@stop