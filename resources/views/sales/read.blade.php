@extends('voyager::master')

@section('page_title', 'Ver Productos en Ventas')

@section('page_header')
    <h1 class="page-title">
        <i class="fa-solid fa-cart-shopping"></i> Ventas &nbsp;
        <a href="{{ route('sales.index') }}" class="btn btn-warning">
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
                                <h3 class="panel-title">Tipo de venta</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>Para {{ $sale->typeSale }} </p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-4">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Nombre Cliente</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $sale->person_id? $sale->person->first_name.' '.$sale->person->middle_name.' '.$sale->person->paternal_surname.' '.$sale->person->maternal_surname : 'Sin Cliente' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-4">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">C.I. Cliente</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $sale->person_id? $sale->person->ci : 'Sin Cliente' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-4">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Dirección Cliente</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{ $sale->person_id? ($sale->person->address?$sale->person->address:'Sin Dirección') : 'Sin Cliente' }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-4">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Total Recibido</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>Bs. {{ number_format($sale->amountReceived, 2, ',', '.') }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-4">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Total de Cambio</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>Bs. {{ number_format($sale->amountChange, 2, ',', '.') }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-4">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Total venta</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>Bs. {{ number_format($sale->amount, 2, ',', '.') }}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>

                        <div class="col-md-4">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Fecha de venta</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{date('d/m/Y h:i:s a', strtotime($sale->dateSale))}} <small>{{\Carbon\Carbon::parse($sale->dateSale)->diffForHumans()}}</p>
                            </div>
                            <hr style="margin:0;">
                        </div>
                        <div class="col-md-4">
                            <div class="panel-heading" style="border-bottom:0;">
                                <h3 class="panel-title">Detalle / Observación</h3>
                            </div>
                            <div class="panel-body" style="padding-top:0;">
                                <p>{{$sale->observation?$sale->observation:'Sin observación'}}</p>
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
                                    Detalles de la venta
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
                                                <th style="text-align: center; width:15%">Precio</th>
                                                <th style="width:15%">Cantidad</th>
                                                <th style="text-align: center; width:15%">Subtotal</th>

                                            </tr>
                                        </thead>
                                        <tbody>    
                                            @php
                                                $i=1;
                                                $amountTotal=0;
                                            @endphp 
                                            @forelse ($sale->saleDetails as $value)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>{{$value->itemSale->name}}</td>
                                                    <td style="text-align: right">     
                                                        {{number_format($value->price, 2, ',', '.')}}
                                                    </td>
                                                    <td style="text-align: right">    
                                                        {{number_format($value->quantity, 2, ',', '.')}}
                                                    </td>
                                                    <td style="text-align: right">    
                                                        {{number_format($value->amount, 2, ',', '.')}}
                                                    </td>
                                                </tr>
                                                @php
                                                    $i++;
                                                    $amountTotal+=$value->amount;
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
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" style="text-align: right">Total</td>
                                                <td style="text-align: right">Bs. {{number_format($amountTotal, 2, ',', '.')}}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <h4>
                                    Metodo de Pagos
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
                                                <th style="text-align: center">Metodo de Pago</th>
                                                <th style="width:15%">Cantidad</th>
                                            </tr>
                                        </thead>
                                        <tbody>    
                                            @php
                                                $i=1;
                                                $amountTotalPayment=0;
                                            @endphp 
                                            @forelse ($sale->saleTransactions as $value)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>{{$value->paymentType}}</td>
                                                    <td style="text-align: right">    
                                                        {{number_format($value->amount, 2, ',', '.')}}
                                                    </td>
                                                </tr>
                                                @php
                                                    $i++;
                                                    $amountTotalPayment+=$value->amount;
                                                @endphp
                                            @empty
                                                <tr>
                                                    <td colspan="3">
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
                                            <tr>
                                                <td colspan="2" style="text-align: right">Total</td>
                                                <td style="text-align: right">Bs. {{number_format($amountTotalPayment, 2, ',', '.')}}</td>
                                            </tr>
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