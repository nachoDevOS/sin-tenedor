
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>{{ Voyager::setting("admin.title") }} - Permiso denegado.</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Favicon -->
        <?php $admin_favicon = Voyager::setting('admin.icon_image', ''); ?>
        @if($admin_favicon == '')
            <link rel="shortcut icon" href="{{ voyager_asset('images/logo-icon-light.png') }}" type="image/png">
        @else
            <link rel="shortcut icon" href="{{ Voyager::image($admin_favicon) }}" type="image/png">
        @endif
    </head>


    <body>
        <div class="d-flex align-items-center justify-content-center vh-100">
            <div class="text-center">
                <h1 class="display-1 fw-bold">403</h1>
                <p class="fs-3"> <span class="text-danger">Aviso!</span> Permiso denegado.</p>
                <p class="lead">
                    Tu usuario no tiene permiso para acceder a esta página.

                </p>
                <img src="{{asset('images/errors/503.gif')}}" width="250" height="200" border="0">
                <br>

                <a href="{{ url('/') }}" class="btn btn-primary">Volver a intentar</a>
            </div>
        </div>
    </body>


</html>
