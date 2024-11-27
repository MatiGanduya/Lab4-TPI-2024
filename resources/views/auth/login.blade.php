<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Agregar Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Estilos personalizados -->
    <style>
        /* Colores personalizados según el estilo número 4 */
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

        .bg-primary {
            background-color: var(--color-primary);
        }

        .text-primary {
            color: var(--color-primary);
        }

        .text-accent {
            color: var(--color-accent);
        }

        .btn-primary {
            background-color: var(--color-button);
            color: white;
            border-radius: 0.375rem;
            padding: 0.5rem 1.5rem;
            transition: background-color 0.2s ease;
        }

        .btn-primary:hover {
            background-color: #2980B9;
        }

        .input-focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.4);
        }

        .input-error {
            border-color: #E74C3C;
        }

        .bg-form-container {
            background-color: white;
        }
    </style>
</head>

<body>

    <div class="min-h-screen flex items-center justify-center">
        <!-- Form Container -->
        <div class="w-full max-w-md bg-form-container rounded-lg shadow-lg p-6">

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 text-green-500">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-primary font-semibold">{{ __('Email') }}</label>
                    <input id="email" class="block mt-1 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 input-focus" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                    @error('email')
                        <div class="mt-2 text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <label for="password" class="block text-primary font-semibold">{{ __('Password') }}</label>
                    <input id="password" class="block mt-1 w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 input-focus" type="password" name="password" required autocomplete="current-password" />
                    @error('password')
                        <div class="mt-2 text-red-500 text-sm">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Forgot Password and Login Button -->
                <div class="flex items-center justify-between mt-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-primary hover:text-accent" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <button type="submit" class="ml-3 px-6 py-2 btn-primary">
                        {{ __('Log in') }}
                    </button>
                </div>

                <!-- Register link -->
                <div class="mt-4 text-center">
                    <span class="text-sm text-primary">¿No tienes cuenta?</span>
                    <a href="{{ route('register') }}" class="underline text-sm text-primary hover:text-accent">
                        Regístrate
                    </a>
                </div>
            </form>

        </div>
    </div>

</body>
</html>
