<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comanda de Cocina</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            width: 80mm;
            margin: 0;
            padding: 5px;
        }
        .comanda {
            width: 100%;
            max-width: 80mm;
        }
        .header {
            text-align: center;
            margin-bottom: 5px;
            border-bottom: 2px dashed #000;
            padding-bottom: 5px;
        }
        .restaurant-name {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 3px;
        }
        .comanda-info {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            font-weight: bold;
        }
        .section-title {
            font-weight: bold;
            text-align: center;
            background-color: #f0f0f0;
            margin: 5px 0;
            padding: 2px 0;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .items-table th {
            text-align: left;
            border-bottom: 1px solid #000;
            padding: 2px 0;
        }
        .items-table td {
            padding: 2px 0;
            vertical-align: top;
        }
        .items-table .quantity {
            text-align: center;
            width: 15%;
        }
        .items-table .item-name {
            width: 85%;
        }
        .observations {
            margin-top: 5px;
            padding: 3px;
            border: 1px dashed #000;
            font-size: 11px;
        }
        .footer {
            text-align: center;
            margin-top: 5px;
            font-size: 10px;
            border-top: 2px dashed #000;
            padding-top: 5px;
        }
        .timestamp {
            text-align: right;
            font-size: 10px;
            margin-bottom: 3px;
        }
        .urgent {
            color: red;
            font-weight: bold;
            text-align: center;
            animation: blink 1s step-end infinite;
        }
        @keyframes blink {
            50% { opacity: 0; }
        }
    </style>
</head>
<body>
    <div class="comanda">
        <div class="timestamp">Impreso: {{ date('d/m/Y H:i:s') }}</div>
        
        <div class="header">
            <div class="restaurant-name">COMANDA DE COCINA</div>
            <div>** PARA PREPARACIÓN **</div>
        </div>
        
        <div class="comanda-info">
            <div>Orden: #{{ $sale->ticket }}</div>
            <div>Mesa: {{ $sale->table ?? 'N/A' }}</div>
        </div>
        
        <div class="comanda-info">
            <div>Hora: {{ date('H:i', strtotime($sale->dateSale)) }}</div>
            <div>Comensales: {{ $sale->customers ?? 1 }}</div>
        </div>
        
        <div class="comanda-info">
            <div>Registrado por: {{ $sale->register->name }}</div>
            <div>Tipo: {{ ucfirst($sale->type) }}</div>
        </div>
        
        <!-- PLATOS PRINCIPALES -->
        @php
            $mainDishes = $sale->saleDetails->filter(function($item) {
                return optional($item->itemSale->category)->name === 'Platos Principales';
            });
        @endphp
        
        @if($mainDishes->count() > 0)
        <div class="section-title">PLATOS PRINCIPALES</div>
        
        <table class="items-table">
            <tbody>
                @foreach($mainDishes as $item)
                <tr>
                    <td class="quantity">{{ number_format($item->quantity, 0) }}</td>
                    <td class="item-name">
                        <strong>{{ $item->itemSale->name }}</strong>
                        @if($item->observation)
                        <br><em>({{ $item->observation }})</em>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        
        <!-- BEBIDAS -->
        @php
            $drinks = $sale->saleDetails->filter(function($item) {
                return optional($item->itemSale->category)->name === 'Bebidas';
            });
        @endphp
        
        @if($drinks->count() > 0)
        <div class="section-title">BEBIDAS</div>
        
        <table class="items-table">
            <tbody>
                @foreach($drinks as $item)
                <tr>
                    <td class="quantity">{{ number_format($item->quantity, 0) }}</td>
                    <td class="item-name">
                        <strong>{{ $item->itemSale->name }}</strong>
                        @if($item->observation)
                        <br><em>({{ $item->observation }})</em>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        
        <!-- POSTRES -->
        @php
            $desserts = $sale->saleDetails->filter(function($item) {
                return optional($item->itemSale->category)->name === 'Postres';
            });
        @endphp
        
        @if($desserts->count() > 0)
        <div class="section-title">POSTRES</div>
        
        <table class="items-table">
            <tbody>
                @foreach($desserts as $item)
                <tr>
                    <td class="quantity">{{ number_format($item->quantity, 0) }}</td>
                    <td class="item-name">
                        <strong>{{ $item->itemSale->name }}</strong>
                        @if($item->observation)
                        <br><em>({{ $item->observation }})</em>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
        
        @if($sale->observation)
        <div class="observations">
            <strong>Observaciones:</strong> {{ $sale->observation }}
        </div>
        @endif
        
        @if($sale->urgent)
        <div class="urgent">¡URGENTE! Cliente con prisa</div>
        @endif
        
        <div class="footer">
            Hora de entrega estimada: {{ date('H:i', strtotime('+30 minutes', strtotime($sale->dateSale))) }}<br>
            {{ setting('admin.title') }} v2.0
        </div>
    </div>
</body>
</html>