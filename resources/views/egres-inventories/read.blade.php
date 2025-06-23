@extends('voyager::master')

@section('page_title', 'Ver Egresos del Inventarios')

@section('page_header')
    <h1 class="page-title">
        <i class="fa-solid fa-cart-shopping"></i> Egresos &nbsp;
        <a href="{{ route('egres-inventories.index') }}" class="btn btn-warning">
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
                        <div class="col-md-4">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Código</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $egres->code }} </p>
                            </div>
                            <hr style="margin:0;">
                        </div>  
                        <div class="col-md-4">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Registrado Por</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{$egres->register->name}}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>      
                        <div class="col-md-4">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Fecha de Egreso</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{date('d/m/Y h:i:s a', strtotime($egres->dateEgres))}} <small>{{\Carbon\Carbon::parse($egres->dateEgres)->diffForHumans()}}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        
                        <div class="col-md-12">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Detalle / Observación</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{$egres->observation?$egres->observation:'Sin observación'}}</p>
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
                                    Detalles de Items
                                </h4>
                            </div>
                            <div class="col-sm-6 text-right">
                            </div>  
                        </div>
                        <div class="row" id="div-results" style="min-height: 120px">
                            <div class="form-group col-md-12">
                                <div class="table-responsive">
                                    <table id="dataTable" class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 5%">N&deg;</th>
                                                <th style="text-align: center">Artículo</th>
                                                <th style="text-align: center">Categoría</th>
                                                <th style="text-align: center; width:15%">Detalle</th>
                                                <th style="width:15%">Cantidad</th>
                                            </tr>
                                        </thead>
                                        <tbody>    
                                            @php
                                                $i=1;
                                            @endphp 
                                            @forelse ($egres->egresInventoryDetails as $value)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>{{$value->itemInventory->name}}</td>
                                                    <td>{{$value->itemInventory->category->name}}</td>
                                                    <td>{{$value->observation}}</td>
                                                    <td style="text-align: right">    
                                                        {{number_format($value->quantity, 2, ',', '.')}}
                                                    </td>
                                                </tr>
                                                @php
                                                    $i++;
                                                @endphp
                                            @empty
                                                <tr>
                                                    <td colspan="5">
                                                        <h5 class="text-center" style="margin-top: 50px">
                                                            <img src="{{ asset('images/empty.png') }}" width="120px" alt="" style="opacity: 0.8">
                                                            <br><br>
                                                            No hay resultados
                                                        </h5>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


  
    {{-- @include('partials.modal-delete') --}}
    
@stop

@section('css')
    <style>

    </style>
@stop

@section('javascript')

    <script>
        function deleteItem(url){
            $('#delete_form').attr('action', url);
        }
    </script>
    
@stop