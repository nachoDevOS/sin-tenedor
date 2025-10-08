@extends('layouts-print.template-print')

@section('page_title', 'Reporte Egreso del Almacen')

@section('content')
    @php
        $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');    
    @endphp

    <div class="report-header">
        <div class="logo-container">
            <?php 
                $admin_favicon = Voyager::setting('admin.icon_image'); 
            ?>
            @if($admin_favicon == '')
                <img src="{{ asset('images/icon.png')}}" alt="{{Voyager::setting('admin.title') }}" width="70px">
            @else
                <img src="{{ Voyager::image($admin_favicon) }}" alt="{{Voyager::setting('admin.title') }}" width="70px">
            @endif
        </div>
        
        <div class="title-container">
            <h1 class="report-title">{{Voyager::setting('admin.title') }}</h1>
            <h2 class="report-subtitle">REPORTE DE EGRESO DE ITEMS DEL ALMACEN</h2>
            {{-- <p class="report-date">
                @if ($start == $finish)
                    {{ date('d', strtotime($start)) }} de {{ $months[intval(date('m', strtotime($start)))] }} de {{ date('Y', strtotime($start)) }}
                @else
                    {{ date('d', strtotime($start)) }} de {{ $months[intval(date('m', strtotime($start)))] }} de {{ date('Y', strtotime($start)) }} Al 
                    {{ date('d', strtotime($finish)) }} de {{ $months[intval(date('m', strtotime($finish)))] }} de {{ date('Y', strtotime($finish)) }}
                @endif
            </p> --}}
        </div>
        
        <div class="qr-container">
            <div id="qr_code">
                {{-- {!! QrCode::size(80)->generate('Total Cobrado: Bs'.number_format($amountTotal,2, ',', '.').', Recaudado en Fecha '.date('d', strtotime($start)).' de '.strtoupper($months[intval(date('m', strtotime($start)))] ).' de '.date('Y', strtotime($start))); !!} --}}
            </div>
            <p class="print-info">Impreso por: {{ Auth::user()->name }}<br>{{ date('d/m/Y h:i:s a') }}</p>
        </div>
    </div>
    
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

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .logo-container img {
            max-height: 80px;
        }
        
        .title-container {
            text-align: center;
            flex-grow: 1;
        }
        
        .report-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 5px;
            color: #2c3e50;
        }
        
        .report-subtitle {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
            color: #7f8c8d;
        }
        
        .report-date {
            font-size: 14px;
            color: #95a5a6;
        }
        
        .qr-container {
            text-align: right;
        }
        
        .print-info {
            font-size: 10px;
            color: #95a5a6;
            margin-top: 5px;
        }

        @media print {
            .report-header {
                border-bottom: 1px solid #ddd;
            }
            
            .sales-table th {
                background-color: #34495e !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
            }
        }


        table, th, td {
            border-collapse: collapse;
        }
        /* @media print { div{ page-break-inside: avoid; } }  */
        
        /* Para evitar que se corte la impresion */
        table.print-friendly tr td, table.print-friendly tr th {
            page-break-inside: avoid;
        }

        @media print {
            body {
                font-size: 10px;
            }
        }
          
    </style>
@stop
