<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

         <!-- apellido -->
         <div class="mt-4">
            <x-input-label for="name" :value="__('Apellido')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="apellido" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

         <!-- DNI -->
         <div class="mt-4">
            <x-input-label for="name" :value="__('Dni')" />
            <x-text-input id="name" class="block mt-1 w-full" type="number" name="Dni" :value="old('Dni')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

         <!-- Pregunta "¿Eres un profesional?" -->
         <div class="mt-4">
            <label for="professional">¿Eres un profesional?</label>
            <input type="checkbox" id="professional" name="professional" value="yes">
        </div>

    <!-- Campo Profesión (oculto inicialmente) -->
    <div class="form-group" id="profession-field" style="display:none;">
        <label for="profession">Profesión</label>
        <x-text-input type="text" id="profession" name="profession" class="form-control"/>
    </div>

    <!-- Campo Dirección (oculto inicialmente) -->
    <div class="form-group" id="address-field" style="display:none;">
        <label for="address">Dirección</label>
        <x-text-input type="text" id="address" name="address" class="form-control"/>
    </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const professionalCheckbox = document.getElementById('professional');
            const professionField = document.getElementById('profession-field');
            const addressField = document.getElementById('address-field');

            professionalCheckbox.addEventListener('change', function () {
                if (professionalCheckbox.checked) {
                    professionField.style.display = 'block';
                    addressField.style.display = 'block';
                } else {
                    professionField.style.display = 'none';
                    addressField.style.display = 'none';
                }
            });
        });
    </script>
</x-guest-layout>
