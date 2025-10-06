<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Venta - {{ setting('admin.title') }}</title>
    <style>
        /* Estilos comunes */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            width: 80mm;
            margin: 0 auto;
            padding: 5px;
        }
        
        /* Estilos específicos para el ticket */
        .ticket {
            width: 100%;
            max-width: 80mm;
            padding: 0;
        }
        .ticket .header {
            text-align: center;
            margin-bottom: 5px;
        }
        .ticket .restaurant-name {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 3px;
        }
        .ticket .restaurant-info {
            font-size: 10px;
            line-height: 1.2;
        }
        .ticket .ticket-info {
            font-size: 11px;
            margin-bottom: 5px;
            border-bottom: 1px dashed #000;
            padding-bottom: 3px;
        }
        .ticket .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .ticket .items-table th {
            text-align: left;
            border-bottom: 1px solid #000;
            padding: 2px 0;
            font-size: 11px;
        }
        .ticket .items-table td {
            padding: 2px 0;
            vertical-align: top;
        }
        .ticket .items-table .quantity {
            text-align: center;
            width: 40px;
        }
        .ticket .items-table .price {
            text-align: right;
            width: 60px;
        }
        .ticket .total {
            text-align: right;
            font-weight: bold;
            font-size: 13px;
            margin-top: 5px;
            border-top: 1px solid #000;
            padding-top: 3px;
        }
        .ticket .payment-details {
            margin-top: 5px;
            font-size: 11px;
        }
        .ticket .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 10px;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }
        .ticket .qr-container {
            text-align: center;
            margin-top: 10px;
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
            </div>
        </div>
        
        <div class="ticket-info" style="border-top: 1px dashed #000; padding-top: 5px;">
            <div><b>Ticket:</b> {{$sale->ticket}}</div>
            <div><b>Fecha:</b> {{date('d/m/Y h:i a', strtotime($sale->dateSale))}}</div>
            <div><b>Cajero:</b> {{$sale->register->name}}</div>
            @if ($sale->person)
                <div>
                    <b>Cliente:</b> {{ strtoupper($sale->person->first_name) }} {{ strtoupper($sale->person->paternal_surname) }}
                </div>
            @endif
        </div>
        
        <table class="items-table">
            <thead>
                <tr>
                    <th>Cant</th>
                    <th>Descripción</th>
                    <th class="price">P.U.</th>
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
                        <td class="quantity">{{ rtrim(rtrim(number_format($item->quantity, 2, ',', '.'), '0'), ',') }}</td>
                        <td>{{ $item->itemSale->name }}</td>
                        <td class="price">{{ number_format($item->price, 2, ',', '.') }}</td>
                        <td class="price">{{ number_format($item->amount, 2, ',', '.') }}</td>
                    </tr>
                    @php
                        $total+=$item->amount;
                    @endphp
                @endforeach
            </tbody>
        </table>
        
        <div class="total">
            Total a Pagar: Bs. {{ number_format($total, 2, ',', '.') }}
            Total a Pagar: Bs. {{ number_format($sale->amount, 2, ',', '.') }}
        </div>
        
        <div class="payment-details">
            <b>Método de pago:</b> {{ $sale->paymentType }}<br>
            <b>Recibido:</b> Bs. {{ number_format($sale->amountReceived, 2, ',', '.') }}<br>
            <b>Cambio:</b> Bs. {{ number_format($sale->amountChange, 2, ',', '.') }}
        </div>
        
        <div class="qr-container">
            @php
                $qrContent = "Ticket: {$sale->ticket} | Total: Bs. " . number_format($sale->amount, 2, '.', '') . " | Fecha: " . date('d/m/Y H:i', strtotime($sale->dateSale));
            @endphp
            {!! QrCode::size(70)->generate($qrContent) !!}
            <div style="font-size: 9px; margin-top: 3px;">
                Escanea para verificar
            </div>
        </div>
        
        <div class="footer">
            ¡Gracias por su preferencia! <br>
            soluciondigital.dev
        </div>
    </div>
</body>
</html>