<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Venta</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            width: 80mm; /* Ancho para impresora de 80mm */
            margin: 0;
            padding: 5px;
        }
        .ticket {
            width: 100%;
            max-width: 80mm;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
        }
        .restaurant-name {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 5px;
        }
        .restaurant-info {
            font-size: 10px;
            margin-bottom: 5px;
        }
        .ticket-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .items-table th {
            text-align: left;
            border-bottom: 1px solid #000;
            padding: 3px 0;
        }
        .items-table td {
            padding: 3px 0;
        }
        .items-table .quantity {
            text-align: center;
            width: 15%;
        }
        .items-table .price {
            text-align: right;
            width: 25%;
        }
        .total {
            text-align: right;
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
            border-top: 1px solid #000;
            padding-top: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 10px;
        }
        .barcode {
            text-align: center;
            margin: 10px 0;
        }
        .qr-container {
            text-align: center;
            margin: 10px 0;
        }
        .qr-code {
            width: 80px;
            height: 80px;
            margin: 0 auto;
        }
        .payment-method {
            margin-top: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
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
            {{-- <div>Mesa: 5</div> --}}
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
            {{-- Subtotal: S/ 117.00<br>
            IGV (18%): S/ 21.06<br> --}}
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
                    $qrContent .= "ATENDIDO POR: {$sale->register->name}";
                @endphp
                {!! QrCode::size(80)->generate($qrContent) !!}
            </div>
            <div>
                Escanea para verificar tu compra <br>
                <small>{{ date('d/M/Y H:i:s') }}</small>
            </div>
        </div>
        
        <div class="footer">
            ¡Gracias por su visita!<br>
            Vuelva pronto<br>
        </div>
    </div>
</body>
</html>