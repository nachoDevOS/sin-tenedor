@extends('layouts-print.template-print')

@section('page_title', 'Reporte de Ventas')

@section('content')
    @php
        $months = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');    
        $formatter = new Luecano\NumeroALetras\NumeroALetras();
    @endphp

    <style>
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
        
        .sales-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 12px;
        }
        
        .sales-table th {
            background-color: #34495e;
            color: white;
            padding: 10px;
            text-align: center;
            font-weight: 600;
        }
        
        .sales-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .sales-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        .item-row {
            background-color: #f1f3f5 !important;
        }
        
        .item-row th {
            background-color: #bdc3c7 !important;
            color: #2c3e50;
            padding: 5px;
        }
        
        .total-row {
            font-weight: 600;
            background-color: #eaf2f8 !important;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .currency {
            font-family: 'Courier New', monospace;
            font-weight: 600;
        }
        
        .no-records {
            text-align: center;
            padding: 20px;
            color: #7f8c8d;
            font-style: italic;
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
            
            .item-row th {
                background-color: #bdc3c7 !important;
                -webkit-print-color-adjust: exact;
            }
            
            .total-row {
                background-color: #eaf2f8 !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>

    <div class="report-header">
        <div class="logo-container">
            <?php $admin_favicon = Voyager::setting('admin.icon_image', ''); ?>
            @if($admin_favicon == '')
                <img src="{{ voyager_asset('images/logo-icon-light.png')}}" alt="{{Voyager::setting('admin.title') }}" width="70px">
            @else
                <img src="{{ Voyager::image($admin_favicon) }}" alt="{{Voyager::setting('admin.title') }}" width="70px">
            @endif
        </div>
        
        <div class="title-container">
            <h1 class="report-title">{{Voyager::setting('admin.title') }}</h1>
            <h2 class="report-subtitle">REPORTE DE VENTAS</h2>
            <p class="report-date">
                @if ($start == $finish)
                    {{ date('d', strtotime($start)) }} de {{ $months[intval(date('m', strtotime($start)))] }} de {{ date('Y', strtotime($start)) }}
                @else
                    {{ date('d', strtotime($start)) }} de {{ $months[intval(date('m', strtotime($start)))] }} de {{ date('Y', strtotime($start)) }} Al 
                    {{ date('d', strtotime($finish)) }} de {{ $months[intval(date('m', strtotime($finish)))] }} de {{ date('Y', strtotime($finish)) }}
                @endif
            </p>
        </div>
        
        <div class="qr-container">
            <div id="qr_code">
                {{-- {!! QrCode::size(80)->generate('Total Cobrado: Bs'.number_format($amountTotal,2, ',', '.').', Recaudado en Fecha '.date('d', strtotime($start)).' de '.strtoupper($months[intval(date('m', strtotime($start)))] ).' de '.date('Y', strtotime($start))); !!} --}}
            </div>
            <p class="print-info">Impreso por: {{ Auth::user()->name }}<br>{{ date('d/m/Y h:i:s a') }}</p>
        </div>
    </div>

    @if ($detail == 0)
        <table class="sales-table">
            <thead>
                <tr>
                    <th style="width: 5%">N°</th>
                    <th style="width: 15%">CÓDIGO</th>
                    <th style="width: 32%">CLIENTE</th>
                    <th style="width: 15%">FECHA DE VENTA</th>
                    <th style="width: 8%">TICKET</th>
                    <th style="width: 15%">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $count = 1;
                    $total = 0;
                @endphp
                @forelse ($sales as $item)
                    <tr>
                        <td style="text-align: center">{{ $count }}</td>
                        <td>{{ $item->code }}</td>
                        <td>
                            @if ($item->person)
                                {{ strtoupper($item->person->first_name) }} 
                                {{ $item->person->middle_name ? strtoupper($item->person->middle_name) : '' }} 
                                {{ strtoupper($item->person->paternal_surname) }}  
                                {{ strtoupper($item->person->maternal_surname) }}
                            @else
                                Sin Datos 
                            @endif 
                        </td>
                        <td style="text-align: center">
                            {{date('d/m/Y h:i a', strtotime($item->dateSale))}}
                        </td>
                        <td style="text-align: center">{{ $item->ticket }}</td>
                        <td class="text-right currency">Bs. {{ number_format($item->amount, 2, ',', '.') }}</td>  
                    </tr>
                    @php
                        $count++;     
                        $total += $item->amount;
                    @endphp                                
                @empty
                    <tr>
                        <td colspan="6" class="no-records">No se encontraron registros.</td>
                    </tr>
                @endforelse
                <tr class="total-row">
                    <td colspan="5" class="text-right">
                        <small>Total: {{ $formatter->toInvoice($total, 2, 'Bolivianos') }}</small>
                    </td>
                    <td class="text-right currency">Bs. {{ number_format($total, 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    @else
        <table class="sales-table">
            <thead>
                <tr>
                    <th style="width: 5%; font-size: 11px">N°</th>
                    <th style="width: 15%; font-size: 11px">CÓDIGO</th>
                    <th style="width: 32%; font-size: 11px">CLIENTE</th>
                    <th style="width: 15%; font-size: 11px">FECHA DE VENTA</th>
                    <th style="width: 8%; font-size: 11px">TICKET</th>
                    <th style="width: 15%; font-size: 11px">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $count = 1;
                    $total = 0;
                @endphp
                @forelse ($sales as $item)
                    <tr>
                        <td style="text-align: center; font-size: 11px">{{ $count }}</td>
                        <td style="font-size: 11px">{{ $item->code }}</td>
                        <td style="font-size: 11px">
                            @if ($item->person)
                                {{ strtoupper($item->person->first_name) }} 
                                {{ $item->person->middle_name ? strtoupper($item->person->middle_name) : '' }} 
                                {{ strtoupper($item->person->paternal_surname) }}  
                                {{ strtoupper($item->person->maternal_surname) }}
                            @else
                                Sin Datos 
                            @endif 
                        </td>
                        <td style="text-align: center; font-size: 11px">
                            {{date('d/m/Y h:i a', strtotime($item->dateSale))}}
                        </td>
                        <td style="text-align: center; font-size: 11px">{{ $item->ticket }}</td>
                        <td class="text-right currency" style="font-size: 11px">Bs. {{ number_format($item->amount, 2, ',', '.') }}</td>  
                    </tr>                    
                    <tr class="item-row">
                        <th colspan="2"></th>
                        <th style="font-size: 10px">ITEMS</th>
                        <th style="text-align: center; font-size: 10px">CANTIDAD</th>
                        <th style="text-align: center; font-size: 10px">PRECIO</th>
                        <th style="text-align: center; font-size: 10px">SUBTOTAL</th>
                    </tr>
                    
                    @forelse ($item->saleDetails as $product)
                        <tr>
                            <td colspan="2"></td>
                            <td style="font-size: 11px">{{ $product->itemSale->name }}</td>
                            <td style="font-size: 11px" class="text-right">{{ number_format($product->quantity, 2, ',', '.') }}</td>  
                            <td style="font-size: 11px" class="text-right currency">{{ number_format($product->price, 2, ',', '.') }}</td>  
                            <td style="font-size: 11px" class="text-right currency">Bs. {{ number_format($product->amount, 2, ',', '.') }}</td>  
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="no-records">No se encontraron productos</td>
                        </tr>
                    @endforelse
                    
                    @php
                        $count++;     
                        $total += $item->amount;
                    @endphp   
                    <tr>
                        <td colspan="6"></td>
                    </tr>
                           
                @empty
                    <tr>
                        <td colspan="6" class="no-records">No se encontraron registros.</td>
                    </tr>
                @endforelse
                
                <tr class="total-row">
                    <td colspan="5" class="text-right">
                        <small>Total: {{ $formatter->toInvoice($total, 2, 'Bolivianos') }}</small>
                    </td>
                    <td class="text-right currency">Bs. {{ number_format($total, 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    @endif

@endsection

@section('css')
    <style>
        /* Estilos adicionales para impresión */
        @media print {
            body {
                font-size: 12px;
            }
            
            .report-header {
                margin-bottom: 10px;
            }
            
            .sales-table {
                font-size: 10px;
            }
            
            .sales-table th, 
            .sales-table td {
                padding: 5px 8px;
            }
        }
    </style>
@stop