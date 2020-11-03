<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <x-jet-validation-errors class="mb-4" />
        
        <div class="mb-4 text-sm text-gray-600">
            {{ __('Thanks for signing up! Before getting started, enter the verification code sent to your phone number. If you didn\'t receive the text message, we will gladly send you another.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ __('A new verification code has been sent to the phone number you provided during registration.') }}
            </div>
        @endif

        <form method="POST" action="{{ route('verification.verify') }}">
            @csrf
            <div class="mt-4 pb-2">
                <x-jet-label for="verification_code" value="{{ __('Verification Code') }}" />
                <x-jet-input id="verification_code" class="block mt-1 w-full" type="text" name="verification_code" required autofocus inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code" />
            </div>
            <div>
                <x-jet-button type="submit">
                    {{ __('Submit') }}
                </x-jet-button>
            </div>
        </form>

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <x-jet-button type="submit">
                        {{ __('Resend Verification Code') }}
                    </x-jet-button>
                </div>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900">
                    {{ __('Logout') }}
                </button>
            </form>
        </div>
    </x-jet-authentication-card>
</x-guest-layout>
