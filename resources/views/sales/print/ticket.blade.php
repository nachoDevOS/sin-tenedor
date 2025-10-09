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
        }
        .ticket {
            width: 100%;
            max-width: 80mm;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
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
            font-size: 12px;
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
            <div class="title-name">Ticket #{{$sale->ticket}}</div>
            <div class="title-name">{{$sale->typeSale}}</div>
        </div>      
        <table class="items-table">
            <thead>
                <tr>
                    <th colspan="3"></th>
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
        
        <div class="total">
            TOTAL. {{ number_format($total, 2, ',', '.') }}
        </div>
        
        <div class="footer">
            ¡Gracias por su preferencia!<br>
            soluciondigital.dev<br>
        </div>
        <div class="footer">
            <div style="text-align: right">
                <small>{{date('d/m/Y h:i:s a', strtotime($sale->dateSale))}}</small>
            </div>
        </div>
    </div>
</body>

<!-- jQuery y Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Incluir el nuevo archivo JS de impresión -->
<script src="{{ asset('js/printTicket.js') }}"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Llama a la función del archivo externo, pasando los datos de la venta
        printTicket('{{setting("servidores.print")}}',@json($sale), 'manual');
    });
</script>

</html>