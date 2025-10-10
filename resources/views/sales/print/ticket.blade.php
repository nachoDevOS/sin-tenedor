<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket de Venta</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- Estilos de Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            width: 80mm; /* Ancho para impresora de 80mm */
            margin: 0;
            padding: 5px;
            background-color: #fff;
        }
        .ticket {
            width: 100%;
            max-width: 80mm;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        .title-name {
            font-weight: bold;
            font-size: 15px;
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
            border-bottom: 1px dashed #000;
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
            font-size: 12px;
            margin-top: 10px;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }
        .footer {
            text-align: center;
            margin-top: 15px;
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
            margin-bottom: 10px;
            border-bottom: 1px dashed #000;
            padding-bottom: 5px;
        }
        .client-info table {
            width: 100%;
        }
        .border {
            border: solid 1px black;
        }
        .border-bottom {
            border-bottom: 1px solid rgb(90, 90, 90);
            padding: 20px 0px;
        }
        .signature-section {
            margin-top: 15px;
            font-size: 10px;
        }
        .signature-section table {
            width: 100%;
        }
        .signature-section th {
            text-align: center;
        }
        
        /* Estilos para impresión */
        @media print {
            .hide-print, .btn-print {
                display: none;
            }
            .show-print {
                display: block;
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
        <button class="btn-print" onclick="window.close()">Cancelar <i class="fa fa-close"></i></button>
        <button class="btn-print" onclick="window.print()">Imprimir <i class="fa fa-print"></i></button>
    </div>
    
    <div class="ticket">
        <div class="header">
            <div class="title-name">TICKET #{{$sale->ticket}}</div>
            <div class="title-name">{{$sale->typeSale}}</div>
           
        </div>
        
     
        
        <!-- Información de la venta -->
        <div class="ticket-info">
            <div>FECHA: {{date('d/m/Y h:i:s a', strtotime($sale->dateSale))}}</div>
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
            soluciondigital.dev<br>
        </div>
    </div>

    <!-- jQuery y Toastr JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Configuración de Toastr
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "3000"
            };
            
            // Mostrar mensaje de éxito al cargar el ticket
            toastr.success('Ticket generado correctamente');
        });
        
        // Control de teclado para impresión y cierre
        document.body.addEventListener('keypress', function(e) {
            switch (e.key) {
                case 'Enter':
                    window.print();
                    break;
                case 'Escape':
                    window.close();
                    break;
                default:
                    break;
            }
        });
    </script>
</body>
</html>