<x-guest-layout>
    <form method="POST" action="{{ isset($user) ? route('user.update') : route('register') }}">
        @csrf
        @if(isset($user))
            @method('POST') <!-- Cambia a PUT si prefieres -->
        @endif

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                          :value="old('name', $user->name ?? '')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- DNI -->
        <div class="mt-4">
            <x-input-label for="dni" :value="__('Dni')" />
            <x-text-input id="dni" class="block mt-1 w-full" type="text" name="dni"
                          :value="old('dni', $user->dni ?? '')" required autofocus autocomplete="dni" />
            <x-input-error :messages="$errors->get('dni')" class="mt-2" />
        </div>

        <!-- Phone Number -->
        <div class="mt-4">
            <x-input-label for="number_phone" :value="__('Telefono (opcional)')" />
            <x-text-input id="number_phone" class="block mt-1 w-full" type="tel" name="number_phone"
                          :value="old('number_phone', $user->number_phone ?? '')" autocomplete="number_phone" />
            <x-input-error :messages="$errors->get('number_phone')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                          :value="old('email', $user->email ?? '')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                          :required="!isset($user)" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation"
                          :required="!isset($user)" autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            @if(!isset($user))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>
            @endif

            <x-primary-button class="ms-4">
                {{ isset($user) ? __('Update') : __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
