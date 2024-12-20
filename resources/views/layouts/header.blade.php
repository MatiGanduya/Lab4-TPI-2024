<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <title>@yield('title', 'My App')</title>
    <style>
        body, html {
            height: 100%;
            margin: 0;
        }
    </style>
</head>
<body>
    <header class="p-3 text-bg-dark">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
                <a href="https://getbootstrap.com/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                    <svg class="bi me-2" width="40" height="32" role="img" aria-label="Bootstrap"><use xlink:href="#bootstrap"></use></svg>
                </a>

                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                   <li><a href="{{ url('/') }}" class="btn btn-outline-light me-2">Home</a></li>
                    <li><a href="{{ Route('servicios.index') }}" class="btn btn-outline-light me-2">Turnos</a></li>
                    @auth
                    @if (!Auth::check() || (Auth::user() && Auth::user()->user_type !== 'employee'))
                    <li><a href="{{ Route('empresa.indexEmpresa') }}" class="btn btn-outline-light me-2">Mi Empresa y Servicios</a></li>
                    @endif
                    @if (Auth::user() && Auth::user()->user_type !== 'client')
                    <li><a href="{{ Route('disponibilidad.indexDisponibilidad') }}" class="btn btn-outline-light me-2">Mi disponibilidad</a></li>
                    @endif
                    @endauth
                </ul>

                <div class="text-end">
                    @auth
                        <!-- Botón de Cerrar Sesión si el usuario está autenticado -->
                        <a href="{{ route('solicitudes.turnos') }}" class="btn btn-outline-light me-2 position-relative">
                            <i class="fas fa-envelope"></i>
                            @if($pendingAppointments > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    •
                                </span>
                            @endif
                        </a>

                        <a href="{{ route('user.edit') }}" class="btn btn-outline-light me-2">
                            <i class="fas fa-user"></i> Editar Perfil
                        </a>

                        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-outline-light me-2">Salir</button>
                        </form>

                    @else
                        <!-- Botones de Login y Sign-up si el usuario no está autenticado -->
                        <a href="{{ route('login') }}" class="btn btn-outline-light me-2">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-warning">Sign-up</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>


