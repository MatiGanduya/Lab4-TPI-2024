@extends('layouts.layout')

@section('content')
    @if(session('success'))
        <div id="success-notification" class="alert alert-success alert-dismissible fade show" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            {{ session('success') }}
        </div>
    @endif

    <!-- Header con el mensaje de bienvenida -->
    @auth
        <header class="custom-header">
            <div class="container">
                <h2>{{ __('Bienvenido, ') }}{{ auth()->user()->name }}!</h2>
            </div>
        </header>
    @endauth

    <!-- Contenido principal -->
    <div class="container-fluid d-flex align-items-center justify-content-center vh-100 px-4">
        <div class="text-center">
            <div class="d-grid gap-3">
                <a href="{{ route('servicios.index') }}" class="custom-btn">
                    {{ __('Solicitar un turno') }}
                </a>
                <a href="{{ route('turnos.index') }}" class="custom-btn">
                    {{ __('Mis turnos') }}
                </a>
                @if (!Auth::check() || (Auth::user() && Auth::user()->user_type !== 'employee'))
                    <a href="{{ route('empresa.indexEmpresa') }}" class="custom-btn">
                        {{ __('Mi empresa y servicios') }}
                    </a>
                @endif
                @if (Auth::user() && Auth::user()->user_type !== 'client')
                    <a href="{{ route('disponibilidad.indexDisponibilidad') }}" class="custom-btn">
                        {{ __('Mi disponibilidad') }}
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Estilos personalizados -->
    <style>
        /* Colores personalizados */
        :root {
            --primary-color: #0c64c2; /* Azul */
            --secondary-color: #6c757d; /* Gris */
            --accent-color: #28a745; /* Verde */
            --background-color: #2C3E50; /* Fondo claro */
            --header-background: #343a40; /* Fondo oscuro para el header */
            --text-color: #ffffff; /* Texto blanco */
        }

        /* Header personalizado */
        .custom-header {
            background-color: var(--header-background);
            color: var(--text-color);
            padding: 1rem 0;
        }

        /* Botones personalizados */
        .custom-btn {
            background-color: var(--primary-color);
            color: var(--text-color);
            border: none;
            padding: 12px 30px;
            font-size: 18px;
            text-transform: uppercase;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            display: inline-block;
            width: 100%;
        }

        .custom-btn:hover {
            background-color: var(--accent-color);
            cursor: pointer;
        }

        .custom-btn:focus {
            outline: none;
            box-shadow: 0 0 0 2px var(--accent-color);
        }

        /* Estilos de la página */
        body {
            background-color: var(--background-color);
            font-family: Arial, sans-serif;
        }

        /* Contenido principal */
        .container-fluid {
            padding-left: 0;
            padding-right: 0;
        }

        .text-center {
            max-width: 500px;
            margin: 0 auto;
        }

        /* Notificaciones de éxito */
        .alert-success {
            background-color: var(--accent-color);
            color: var(--text-color);
        }
    </style>
@endsection

@push('scripts')
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                console.log("Script cargado y ejecutado");

                var notification = document.getElementById("success-notification");
                var closeButton = document.getElementById("close-notification");

                if (closeButton && notification) {
                    console.log("Notificación y cruz encontrados");

                    closeButton.addEventListener('click', function() {
                        console.log("Cruz clickeada");
                        notification.classList.add("hide");
                    });

                    // Cerrar la notificación automáticamente después de 4 segundos
                    setTimeout(function() {
                        console.log("Cerrando notificación automáticamente");
                        notification.classList.add("hide");
                    }, 4000);
                } else {
                    console.log("Elementos no encontrados");
                }
            });
        </script>
    @endif
@endpush

