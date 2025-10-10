<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Venta #{{ $sale->ticket }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            width: 80mm; /* Ancho para impresora de 80mm */
            margin: 0;
            padding: 0;
        }
        .ticket {
            width: 100%;
            max-width: 80mm;
        }
        .header {
            text-align: center;
            margin-bottom: 5px;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
        }
        .title-name {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }
        .restaurant-info {
            font-size: 10px;
            margin-bottom: 5px;
        }
        .ticket-info {
            display: flex;
            justify-content: left;
            margin-bottom: 5px;
            border-bottom: 1px dashed #000;
            padding-bottom: 3px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .items-table th {
            text-align: left;
            border-bottom: 1px dashed #000;
            padding: 2px 0;
        }
        .items-table td {
            padding: 2px 0;
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
            font-weight: 900;
            font-size: 12px;
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 10px;
            border-top: 1px dashed #000;
            padding-top: 10px;
        }
        .barcode {
            text-align: center;
            margin: 10px 0;
        }
        .qr-container {
            text-align: center;
            margin: 5px 0;
        }
        .payment-method {
            margin-top: 5px;
            font-weight: 900;
        }
        .hide-print {
            text-align: right;
            padding: 10px 0px;
        }
        .btn-print {
            padding: 5px 10px;
            margin-left: 5px;
            cursor: pointer;
        }
        .client-info {
            margin-bottom: 5px;
            border-bottom: 1px dashed #000;
            padding-bottom: 3px;
            font-size: 11px;
        }
        
        /* Estilos para impresión */
        @media print {
            .hide-print, .btn-print {
                display: none;
            }
            body {
                margin: 0;
            }
            html, body {
                height: auto; /* Ajusta la altura al contenido para evitar páginas extra */
                overflow: hidden; /* Oculta cualquier desbordamiento */
            }
        }
        
        /* Estilos para pantalla */
        @media screen {
            body {
                width: auto;
                max-width: 80mm;
                margin: 0 auto;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="hide-print">
        <button class="btn-print" onclick="window.close()">Cancelar (Esc)</button>
        <button class="btn-print" onclick="window.print()">Imprimir (Enter)</button>
    </div>
    
    <div class="ticket">
        <div class="header">
            <div class="title-name" style="margin-top: 10px;">TICKET #{{$sale->ticket}}</div>
            <div class="title-name" style="text-transform: uppercase;">{{$sale->typeSale}}</div>
        </div>
        
        <!-- Información del cliente y cajero -->
        <div class="client-info">
            <b>Fecha:</b> {{date('d/m/Y h:i:s a', strtotime($sale->dateSale))}}<br>
        </div>
        
        <!-- Detalles de los productos -->
        <table class="items-table">
            <thead>
                <tr>
                    <th class="quantity">CANT</th>
                    <th>DESCRIPCIÓN</th>
                    <th class="price">PRECIO</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total=0;
                @endphp
                @foreach ($sale->saleDetails as $item)
                    <tr>
                        <td class="quantity">{{ (float)$item->quantity == (int)$item->quantity? (int)$item->quantity:(float)$item->quantity }}</td>
                        <td>{{$item->itemSale->name}}</td>
                        <td class="price">{{ number_format($item->amount, 2, ',', '.') }}</td>
                    </tr>
                    @php
                        $total+=$item->amount;
                    @endphp
                @endforeach
            </tbody>
        </table>
        
        <!-- Total y método de pago -->
        <div class="total">
            TOTAL: {{ number_format($total, 2, ',', '.') }}
        </div>
        
        <!-- Pie de página -->
        <div class="footer">
            ¡Gracias por su preferencia!<br>
            soluciondigital.dev
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Imprimir automáticamente al cargar la página
            window.print();
        });
        
        // Control de teclado para impresión y cierre
        document.body.addEventListener('keypress', function(e) {
            switch (e.key) {
                case 'Enter':
                case 'p':
                case 'P':
                    window.print();
                    break;
                case 'Escape':
                    window.close();
                    break;
                default:
                    break;
            }
        });

        // Cerrar la ventana después de imprimir (o si se cancela la impresión)
        window.onafterprint = function() {
            window.close();
        }
    </script>
</body>
</html>