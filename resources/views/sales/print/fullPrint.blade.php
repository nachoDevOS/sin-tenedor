<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Impresión Completa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        /* Estilos comunes */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            width: 80mm;
            margin: 0;
            padding: 0;
        }
        
        /* Estilos específicos para el ticket */
        .ticket {
            width: 100%;
            max-width: 80mm;
            padding: 5px;
            page-break-after: always;
        }
        .ticket .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .ticket .restaurant-name {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 5px;
        }
        .ticket .restaurant-info {
            font-size: 10px;
            margin-bottom: 5px;
        }
        .ticket .ticket-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
        }
        .ticket .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .ticket .items-table th {
            text-align: left;
            border-bottom: 1px solid #000;
            padding: 3px 0;
        }
        .ticket .items-table td {
            padding: 3px 0;
        }
        .ticket .items-table .quantity {
            text-align: center;
            width: 15%;
        }
        .ticket .items-table .price {
            text-align: right;
            width: 25%;
        }
        .ticket .total {
            text-align: right;
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        .ticket .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 10px;
        }
        .ticket .barcode {
            text-align: center;
            margin: 10px 0;
        }
        .ticket .qr-container {
            text-align: center;
            margin: 10px 0;
        }
        .ticket .qr-code {
            width: 80px;
            height: 80px;
            margin: 0 auto;
        }
        .ticket .payment-method {
            margin-top: 5px;
            font-weight: bold;
        }
        
        /* Estilos específicos para la comanda */
        .comanda {
            width: 100%;
            max-width: 80mm;
            padding: 5px;
        }
        .comanda .timestamp {
            text-align: right;
            font-size: 10px;
            margin-bottom: 3px;
        }
        .comanda .header {
            text-align: center;
            margin-bottom: 5px;
            border-bottom: 2px dashed #000;
            padding-bottom: 5px;
        }
        .comanda .restaurant-name {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 3px;
        }
        .comanda .comanda-info {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            font-weight: bold;
        }
        .comanda .section-title {
            font-weight: bold;
            text-align: center;
            background-color: #f0f0f0;
            margin: 5px 0;
            padding: 2px 0;
            text-transform: uppercase;
        }
        .comanda .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .comanda .items-table th {
            text-align: left;
            border-bottom: 1px solid #000;
            padding: 2px 0;
        }
        .comanda .items-table td {
            padding: 2px 0;
            vertical-align: top;
        }
        .comanda .items-table .quantity {
            text-align: center;
            width: 15%;
        }
        .comanda .items-table .item-name {
            width: 85%;
        }
        .comanda .observations {
            margin-top: 5px;
            padding: 3px;
            border: 1px dashed #000;
            font-size: 11px;
        }
        .comanda .footer {
            text-align: center;
            margin-top: 5px;
            font-size: 10px;
            border-top: 2px dashed #000;
            padding-top: 5px;
        }
        .comanda .urgent {
            color: red;
            font-weight: bold;
            text-align: center;
            animation: blink 1s step-end infinite;
        }
        @keyframes blink {
            50% { opacity: 0; }
        }
        
        /* Instrucciones para el corte */
        .page-cut {
            display: block;
            height: 0;
            overflow: hidden;
            page-break-after: always;
        }
        @media print {
            .page-cut {
                height: 1px;
                margin: 0;
                padding: 0;
                border-top: 1px dashed #000;
            }
        }
    </style>
</head>
<body>
    <!-- TICKET DE VENTA -->
    <div class="ticket">
        <div class="header">
            <div class="restaurant-name">{{ setting('admin.title') }}</div>
            <div class="restaurant-info">
                Dirección: Calle Principal #123<br>
                Tel: 555-1234<br>
                {{-- RUC: 12345678901 --}}
            </div>
        </div>
        
        <div class="ticket-info">
            <div>Ticket #: {{$sale->ticket}}</div>
            <div>Fecha: {{date('d/m/Y h:i:s a', strtotime($sale->dateSale))}}</div>
        </div>
        
        <div class="ticket-info">
            <div>Codigo: {{$sale->code}}</div>
            <div>Atendido por: {{$sale->register->name}}</div>
        </div>
        
        <table class="items-table">
            <thead>
                <tr>
                    <th>Cant</th>
                    <th>Descripción</th>
                    <th class="price">P.Unit</th>
                    <th class="price">Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total=0;
                @endphp
                @foreach ($sale->saleDetails as $item)
                    <tr>
                        <td class="quantity">{{ number_format($item->quantity, 2, ',', '.') }}</td>
                        <td>{{$item->itemSale->name}}</td>
                        <td class="price">{{ number_format($item->price, 2, ',', '.') }}</td>
                        <td class="price">Bs. {{ number_format($item->amount, 2, ',', '.') }}</td>
                    </tr>
                    @php
                        $total+=$item->amount;
                    @endphp
                @endforeach
            </tbody>
        </table>
        
        <div class="total">
            Total a Pagar: Bs. {{ number_format($total, 2, ',', '.') }}
        </div>
        
        <div class="payment-method">
            Método de pago: Efectivo<br>
            Recibido: Bs. {{ number_format($sale->amountReceived, 2, ',', '.') }}<br>
            Vuelto: Bs. {{ number_format($sale->amountChange, 2, ',', '.') }}
        </div>
        
        <div class="qr-container">
            <div class="qr-code">
                @php
                    $qrContent = "TICKET #{$sale->ticket}\n";
                    $qrContent .= "FECHA: " . date('d/m/Y H:i', strtotime($sale->dateSale)) . "\n";
                    $qrContent .= "PRODUCTOS:\n";
                    foreach ($sale->saleDetails as $item) {
                        $qrContent .= "- {$item->itemSale->name} x{$item->quantity} Bs.{$item->price}\n";
                    }
                    $qrContent .= "TOTAL: Bs." . number_format($total, 2, ',', '.') . "\n";
                @endphp
                {!! QrCode::size(80)->generate($qrContent) !!}
            </div>
            <div>
                Escanea para verificar tu compra <br>
                <small>{{ date('d/M/Y h:i:s a') }}</small>
            </div>
        </div>
        
        <div class="footer">
            ¡Gracias por su visita!<br>
            Vuelva pronto<br>
        </div>
    </div>
    
    <!-- MARCA PARA EL CORTE -->
    <div class="page-cut"></div>
    
    <!-- COMANDA DE COCINA -->
    <div class="comanda">
        <div class="timestamp">Impreso: {{ date('d/m/Y h:i:s a') }}</div>
        
        <div class="header">
            <div class="restaurant-name">COMANDA DE COCINA</div>
            <div>
                ** PARA PREPARACIÓN ** <br>
                <h3 style="border-bottom: 1px solid #000; display: inline-block; padding-bottom: 5px;">Para {{$sale->typeSale}}</h3>
            </div>
        </div>
        
        <div class="comanda-info">
            <div>Orden: # {{ $sale->ticket }}</div>
            <div>Fecha: {{ date('d/m/Y h:i a', strtotime($sale->dateSale)) }}</div>
        </div>
        
        <div class="comanda-info">
            <div>Codigo: {{$sale->code}}</div>
        </div>
        
        <div class="comanda-info">
            <div>Registrado por: {{ $sale->register->name }}</div>
        </div>
        
        <!-- SECCIONES DINÁMICAS POR CATEGORÍA -->
        @foreach($groupedItems as $categoryName => $items)
        <div class="section-title">{{ $categoryName }}</div>
        
        <table class="items-table">
            <tbody>
                @foreach($items as $item)
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
        @endforeach
        
        @if($sale->observation)
        <div class="observations">
            <strong>Observaciones:</strong> {{ $sale->observation }}
        </div>
        @endif
        
        @if($sale->urgent)
        <div class="urgent">¡URGENTE! Cliente con prisa</div>
        @endif
        
        <div class="footer">
            Hora de entrega estimada: {{ date('h:i a', strtotime('+30 minutes', strtotime($sale->dateSale))) }}<br>
            {{ setting('admin.title') }} v1.0
        </div>
    </div>
    
    <!-- INSTRUCCIÓN DE CORTE FINAL PARA IMPRESORAS TÉRMICAS -->
    <div style="text-align: center; margin-top: 10px;">
        <span style="font-size: 8px;">&#x1F4C4;</span> <!-- Símbolo de página -->
        <span style="font-size: 8px; margin: 0 10px;">&#x2702;</span> <!-- Símbolo de tijeras -->
        <span style="font-size: 8px;">&#x1F4C4;</span> <!-- Símbolo de página -->
    </div>
    <div style="page-break-after: always;"></div>
</body>
</html>