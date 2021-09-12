<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <img width="150" height="150" src="{{\App\Helper\Helper::URL_LOGO}}">
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Zaboravili ste lozinku? Nema problema. Samo nam recite svoju adresu e -pošte, a mi ćemo vam poslati vezu za poništavanje lozinke koja će vam omogućiti da izaberete novu.') }}
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <x-jet-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="block">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-jet-button>
                    {{ __('Resetuj lozinku') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
