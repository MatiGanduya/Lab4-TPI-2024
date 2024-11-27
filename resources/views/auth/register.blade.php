<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($user) ? 'Actualizar Usuario' : 'Registro' }}</title>
    <!-- Agregar Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
                :root {
            --color-primary: #2C3E50; /* Azul oscuro */
            --color-accent: #1ABC9C; /* Verde menta */
            --color-background: #2C3E50; /* Gris claro */
            --color-text: #2C3E50; /* Gris oscuro */
            --color-text-light: #95A5A6; /* Gris claro */
            --color-button: #3498DB; /* Azul claro */
        }

        body {
            background-color: var(--color-background);
        }
        /* Colores personalizados */
        .text-primary {
            color: #4c6ef5; /* Azul primario */
        }
        .text-accent {
            color: #f59e0b; /* Amarillo */
        }
        .btn-primary {
            background-color: #4c6ef5; /* Azul primario */
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #3b5bf0; /* Azul más oscuro */
        }
        .input-focus:focus {
            border-color: #4c6ef5; /* Azul primario */
            outline: none;
        }
    </style>
</head>
<body class=" flex justify-center items-center min-h-screen">
    <form method="POST" action="{{ isset($user) ? route('user.update') : route('register') }}" class="bg-white p-8 rounded-lg shadow-md w-full max-w-lg">
        @csrf
        @if(isset($user))
            @method('POST') <!-- Cambia a PUT si prefieres -->
        @endif

        <!-- Name -->
        <div class="mb-4">
            <label for="name" class="block text-lg font-medium text-gray-700">{{ __('Name') }}</label>
            <input id="name" class="block mt-1 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 input-focus" type="text" name="name"
                   value="{{ old('name', $user->name ?? '') }}" required autofocus autocomplete="name" />
            @error('name')
                <p class="mt-2 text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- DNI -->
        <div class="mb-4">
            <label for="dni" class="block text-lg font-medium text-gray-700">{{ __('Dni') }}</label>
            <input id="dni" class="block mt-1 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 input-focus" type="text" name="dni"
                   value="{{ old('dni', $user->dni ?? '') }}" required autofocus autocomplete="dni" />
            @error('dni')
                <p class="mt-2 text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Phone Number -->
        <div class="mb-4">
            <label for="number_phone" class="block text-lg font-medium text-gray-700">{{ __('Telefono (opcional)') }}</label>
            <input id="number_phone" class="block mt-1 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 input-focus" type="tel" name="number_phone"
                   value="{{ old('number_phone', $user->number_phone ?? '') }}" autocomplete="number_phone" />
            @error('number_phone')
                <p class="mt-2 text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Address -->
        <div class="mb-4">
            <label for="email" class="block text-lg font-medium text-gray-700">{{ __('Email') }}</label>
            <input id="email" class="block mt-1 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 input-focus" type="email" name="email"
                   value="{{ old('email', $user->email ?? '') }}" required autocomplete="username" />
            @error('email')
                <p class="mt-2 text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block text-lg font-medium text-gray-700">{{ __('Password') }}</label>
            <input id="password" class="block mt-1 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 input-focus" type="password" name="password"
                   @if(!isset($user)) required @endif autocomplete="new-password" />
            @error('password')
                <p class="mt-2 text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="block text-lg font-medium text-gray-700">{{ __('Confirm Password') }}</label>
            <input id="password_confirmation" class="block mt-1 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 input-focus" type="password" name="password_confirmation"
                   @if(!isset($user)) required @endif autocomplete="new-password" />
            @error('password_confirmation')
                <p class="mt-2 text-red-500 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between mt-4">
            @if(!isset($user))
                <a class="underline text-sm text-primary hover:text-accent" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>
            @endif

            <button type="submit" class="btn-primary">
                {{ isset($user) ? __('Update') : __('Register') }}
            </button>
        </div>
    </form>
</body>
</html>
