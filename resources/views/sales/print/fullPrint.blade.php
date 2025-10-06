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
            margin: 0;
            padding: 5px;
        }
        
        /* Estilos específicos para el ticket */
        .ticket {
            width: 100%;
            max-width: 80mm;
            padding: 5px;
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
    </style>
</head>
<body>
    <!-- TICKET DE VENTA -->
    <div class="ticket">
        <div class="header">
            <div class="restaurant-name">{{ setting('admin.title') }}</div>
            <div class="restaurant-info" style="border-bottom: 1px dashed #000; padding-bottom: 5px;">
                Dirección: Calle Principal #123<br>
                {{-- Tel: 555-1234<br> --}}
                {{-- RUC: 12345678901 --}}
            </div>
        </div>
        
        <div class="ticket-info">
            <div>Ticket #: {{$sale->ticket}}</div>
            <div>Fecha: {{date('d/m/Y h:i:s a', strtotime($sale->dateSale))}}</div>
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
        </div>
        
        <div class="footer">
            ¡Gracias por su preferencia! <br>
            soluciondigital.dev
        </div>
    </div>
</body>
</html>