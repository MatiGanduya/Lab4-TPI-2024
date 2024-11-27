@extends('layouts.layout')

@section('content')
    @if(session('success'))
        <div id="success-notification" class="notification">
            <span id="close-notification" class="close-btn">&times;</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="container h-100 d-flex align-items-center justify-content-center position-relative">
        <div class="text-center">
            {{-- Mensaje de bienvenida para usuarios autenticados --}}
            @auth
                <h2 class="position-absolute top-0 start-0 mt-4" style="margin-left: 55px;">Bienvenido, {{ auth()->user()->name }}!</h2>
            @endauth

            <div class="d-grid gap-3">
                <a href="{{ route('servicios.index') }}" class="btn btn-primary btn-lg">Solicitar un turno</a>
                <a href="{{ route('turnos.index') }}" class="btn btn-primary btn-lg">Mis turnos</a>
                @if (!Auth::check() || (Auth::user() && Auth::user()->user_type !== 'employee'))
                <a href="{{ route('empresa.indexEmpresa') }}" class="btn btn-primary btn-lg">Mi empresa y servicios</a>
                @endif
                @if (Auth::user() && Auth::user()->user_type !== 'client')
                <a href="{{ route('disponibilidad.indexDisponibilidad') }}" class="btn btn-primary btn-lg">Mi disponibilidad</a>
                @endif
            </div>
        </div>
    </div>
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

