@extends('layouts-print.template-print')

@section('page_title', 'Reporte Egreso del Almacen')

@section('content')
    @php
        $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');    
    @endphp

    <table width="100%">
        <tr>
            <td style="width: 25%">
                <?php 
                    $admin_favicon = Voyager::setting('admin.icon_image', '');
                ?>
                @if($admin_favicon == '')
                    <img src="{{ voyager_asset('images/logo-icon-light.png')}}" alt="{{Voyager::setting('admin.title') }}" width="70px">
                @else
                    <img src="{{ Voyager::image($admin_favicon) }}" alt="{{Voyager::setting('admin.title') }}" width="70px">
                @endif
            </td>
            <td style="text-align: center;  width:50%">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    {{Voyager::setting('admin.title') }}
                </h3>
                <h4 style="margin-bottom: 0px; margin-top: 5px">
                    REPORTE DE EGRESO DE ITEMS DEL ALMACEN
                </h4>
                {{-- <small style="margin-bottom: 0px; margin-top: 5px">
                    @if ($start == $finish)
                        {{ date('d', strtotime($start)) }} de {{ $months[intval(date('m', strtotime($start)))] }} de {{ date('Y', strtotime($start)) }}
                    @else
                        {{ date('d', strtotime($start)) }} de {{ $months[intval(date('m', strtotime($start)))] }} de {{ date('Y', strtotime($start)) }} Al {{ date('d', strtotime($finish)) }} de {{ $months[intval(date('m', strtotime($finish)))] }} de {{ date('Y', strtotime($finish)) }}
                    @endif
                </small> --}}
            </td>
            <td style="text-align: right; width:25%">
                <h3 style="margin-bottom: 0px; margin-top: 5px">
                    <div id="qr_code">
                        {{-- {!! QrCode::size(80)->generate('Total Cobrado: Bs'.number_format($amountTotal,2, ',', '.').', Recaudado en Fecha '.date('d', strtotime($start)).' de '.strtoupper($months[intval(date('m', strtotime($start)))] ).' de '.date('Y', strtotime($start))); !!} --}}
                    </div>
                    <small style="font-size: 8px; font-weight: 100">Impreso por: {{ Auth::user()->name }} <br> {{ date('d/m/Y h:i:s a') }}</small>
                </h3>
            </td>
        </tr>
    </table>
    
    <table style="width: 100%; font-size: 10px" border="1" class="print-friendly" cellspacing="0" cellpadding="2">
        <thead>
            <tr>    
                <th style="text-align: center">N&deg;</th>
                <th style="text-align: center; width:5px">CATEGORIAS</th>
                <th style="text-align: center">ITEMS/PRODUCTOS</th>
                <th style="text-align: center; width:5px">DISPENSACION</th>
                <th style="text-align: center">DETALLE</th>
                <th style="text-align: center; width:100px">CANT. <br>DISPENSADA</th>
            </tr>
        </thead>
        <tbody>
            @php
                $count = 1;
            @endphp
            @forelse ($egres->egresInventoryDetails as $item)
                <tr>
                    <td style="text-align: center">{{ $count }}</td>
                    <td>{{ $item->itemInventory->category->name }}</td>
                    <td>{{ $item->itemInventory->name }}</td>
                    <td>{{ $item->itemInventory->dispensingType }}</td>
                    <td>{{ $item->itemInventory->observation?$item->itemInventory->observation:'Sin Descripción' }}</td>
                    <td style="text-align: right">{{ number_format($item->quantity,2, ',','.') }}</td>                                                                          
                </tr>
                @php
                    $count++;                                 
                @endphp
            @empty
                <tr style="text-align: center">
                    <td colspan="6">No se encontraron registros.</td>
                </tr>
            @endforelse
            <br>
            <tr>
  
                <td colspan="6" style="text-align: left"><small style="font-size: 10px">Detalle: </small> <strong>{{ $egres->observation?$egres->observation:'Sin Descripción' }}</strong></td>
            </tr>
    </tbody>
        


@endsection
@section('css')
    <style>
        table, th, td {
            border-collapse: collapse;
        }
        /* @media print { div{ page-break-inside: avoid; } }  */
        
        /* Para evitar que se corte la impresion */
        table.print-friendly tr td, table.print-friendly tr th {
            page-break-inside: avoid;
        }
          
    </style>
@stop
