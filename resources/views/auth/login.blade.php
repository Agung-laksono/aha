<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            {{-- Logo removed for a cleaner look --}}
        </x-slot>

        <div class="mb-10 text-center">
            <h2 class="text-3xl font-black text-gray-900 dark:text-white tracking-tighter uppercase italic">
                Selamat Datang
            </h2>
            <p class="text-gray-500 dark:text-gray-400 font-bold">Silakan masuk ke akun Anda</p>
        </div>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ $value }}
            </div>
        @endsession

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div>
                <x-label for="email" value="{{ __('Email atau Nama') }}"
                    class="font-bold text-gray-700 dark:text-gray-300 ml-1 mb-1" />
                <x-input id="email"
                    class="block w-full py-4 px-6 bg-white/50 dark:bg-gray-800/50 border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-orange-500 focus:border-orange-500 transition-all shadow-sm"
                    type="text" name="email" :value="old('email')" required autofocus
                    placeholder="Masukkan email atau nama" />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}"
                    class="font-bold text-gray-700 dark:text-gray-300 ml-1 mb-1" />
                <x-input id="password"
                    class="block w-full py-4 px-6 bg-white/50 dark:bg-gray-800/50 border-gray-200 dark:border-gray-700 rounded-2xl focus:ring-orange-500 focus:border-orange-500 transition-all shadow-sm"
                    type="password" name="password" required autocomplete="current-password"
                    placeholder="Masukkan password" />
            </div>

            <div class="flex items-center justify-between mt-6">
                <label for="remember_me" class="flex items-center cursor-pointer group">
                    <x-checkbox id="remember_me" name="remember"
                        class="w-5 h-5 rounded-lg border-gray-300 dark:border-gray-700 text-orange-600 focus:ring-orange-500 shadow-sm transition-all" />
                    <span
                        class="ms-3 text-sm font-bold text-gray-500 dark:text-gray-400 group-hover:text-gray-700 dark:group-hover:text-gray-200 transition-colors">{{ __('Ingat saya') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm font-bold text-gray-500 dark:text-gray-400 hover:text-orange-600 dark:hover:text-orange-500 transition-colors underline underline-offset-4"
                        href="{{ route('password.request') }}">
                        {{ __('Lupa password?') }}
                    </a>
                @endif
            </div>

            <div class="pt-2">
                <x-button
                    class="w-full py-5 px-8 bg-gray-900 dark:bg-white text-white dark:text-gray-900 hover:bg-black dark:hover:bg-gray-100 rounded-2xl font-black text-xl shadow-2xl shadow-black/20 dark:shadow-white/10 transition-all active:scale-95 flex items-center justify-center gap-2">
                    <span>{{ __('Masuk Sekarang') }}</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>