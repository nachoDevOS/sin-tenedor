<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('page_title')</title>
    <!-- Favicon -->
    <?php $admin_favicon = Voyager::setting('admin.icon_image', ''); ?>
    @if($admin_favicon == '')
        <link rel="shortcut icon" href="{{ asset('images/icon.png') }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{ Voyager::image($admin_favicon) }}" type="image/png">
    @endif
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> --}}
    <style>
        body{
            margin: 0px auto;
            font-family: Arial, sans-serif;
        }
        .btn-print{
            padding: 5px 10px
        }
        #watermark {
            width: 100%;
            position: fixed;
            top: 300px;
            opacity: 0.1;
            z-index:  -1;
            text-align: center
        }
        #watermark img{
            position: relative;
            width: 200px;
        }
        #label-location{
            display: none;
        }
        @media print{
            .hide-print{
                display: none
            }
            .content{
                padding: 0px 0px
            }
            #location-id{
                display: none;
            }
            #label-location{
                display: inline;
            }
        }
        @media print and (min-width: 700px) and (orientation: landscape) {
            #watermark {
                top: 200px;
            }
        }
    </style>
    @yield('css')
</head>
<body>

    @isset($type_render)
        @if ($type_render != 3 && $type_render != 'excel')
            <div id="watermark">
                <img src="{{ asset('images/icon.png') }}" /> 
            </div>
        @endif
    @endisset
    
    <div class="content">
        @yield('content')
    </div>

    <script>
        document.body.addEventListener('keypress', function(e) {
            switch (e.key) {
                case 'Enter':
                    window.print();
                    break;
                case 'Escape':
                    window.close();
                default:
                    break;
            }
        });
    </script>

    {{-- <script type="text/javascript" src="{{ voyager_asset('js/app.js') }}"></script> --}}
    <script>
        
    </script>
    @yield('script')
</body>
</html>