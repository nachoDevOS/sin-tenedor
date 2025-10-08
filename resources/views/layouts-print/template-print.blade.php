<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('page_title') | {{Voyager::setting('admin.title') }}</title>
    <!-- Favicon -->
    <?php $admin_favicon = Voyager::setting('admin.icon_image'); ?>
    @if($admin_favicon == '')
        <link rel="shortcut icon" href="{{ voyager_asset('images/logo-icon-light.png')}}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{ Voyager::image($admin_favicon) }}" type="image/png">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            position: relative;
            background-color: #566573;
        }

        .background {
            width: 100%;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 20px 0;
            position: relative;
        }

        .sheet {
            width: 850px;
            background-color: white;
            padding: 30px 50px;
            position: relative;
            z-index: 1;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.2;
            z-index: 0;
            pointer-events: none;
        }

        .watermark img {
            width: 350px;
            max-width: 100%;
        }

        /* Estilos para botones */
        .button-container {
            text-align: right;
            margin-bottom: 20px;
        }

        .btn-print, .btn-cancel {
            padding: 8px 20px;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-left: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-print {
            background-color: #28a745;
            color: white;
        }

        .btn-print:hover {
            background-color: #218838;
            transform: translateY(-1px);
        }

        .btn-cancel {
            background-color: #dc3545;
            color: white;
        }

        .btn-cancel:hover {
            background-color: #c82333;
            transform: translateY(-1px);
        }

        .no-print {
            display: block;
        }

        @media print {
            body {
                background-color: white !important;
            }
            .no-print {
                display: none !important;
            }
            .watermark {
                display: block !important;
                opacity: 0.1 !important;
                position: absolute !important;
                top: 40% !important;
            }
            .sheet {
                width: 100% !important;
                padding: 10px !important;
                margin: 0 !important;
            }
            .background {
                padding: 0 !important;
                min-height: 100% !important;
                display: block !important;
            }
        }
    </style>
    @yield('css')
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">
        <?php $admin_favicon = Voyager::setting('admin.icon_image', ''); ?>
        @if($admin_favicon == '')
            <img src="{{ voyager_asset('images/logo-icon-light.png') }}" />
        @else
            <img src="{{ Voyager::image($admin_favicon) }}" style="width: 200px;"/>
        @endif
    </div>

    <!-- Contenido principal -->
    <div class="background">
        <div class="sheet">
            <!-- Botones en la parte superior -->
            <div class="button-container no-print">
                <button class="btn-cancel" onclick="window.close()">
                    Cancelar <i class="fa fa-close"></i>
                </button>
                <button class="btn-print" onclick="window.print()">
                    Imprimir <i class="fa fa-print"></i>
                </button>
            </div>

            <!-- Contenido del reporte -->
            <div class="content">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
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

    @yield('script')
</body>
</html>