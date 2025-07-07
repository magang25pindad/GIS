<x-guest-layout>
    <div class="flex items-center justify-center w-full">
        <div class="flex flex-col md:flex-row bg-white shadow-xl rounded-lg overflow-hidden max-w-4xl w-full my-12">

            {{-- LEFT: Logo --}}
            <div class="hidden md:flex w-full md:w-1/2 bg-gray-50 items-center justify-center p-8 animate-slide-in-left">
                <img src="https://pindad.com/assets/img/theme-1/logo.png" alt="Logo Pindad" class="w-60 h-auto transition-transform duration-700 hover:scale-105 opacity-90" />
            </div>

            {{-- RIGHT: Login Form --}}
            <div class="w-full md:w-1/2 p-8 animate-fade-in">
                <h2 class="text-3xl font-bold mb-6 text-center">Selamat Datang di GIS</h2>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block font-semibold mb-1">Email</label>
                        <input id="email" name="email" type="email" required autofocus class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block font-semibold mb-1">Password</label>
                        <input id="password" name="password" type="password" required class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox" class="mr-2">
                        <label for="remember_me" class="text-sm">Ingat Saya</label>
                    </div>
                    <div class="text-right text-sm text-blue-600 hover:underline mb-4">
                        <a href="{{ route('password.request') }}">Lupa sandi?</a>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition duration-300">
                        Login
                    </button>
                    <div class="mt-4 text-center text-sm text-gray-600">
                        Belum punya akun?
                    <a href="{{ route('register') }}" class="text-blue-600 hover:underline font-medium">Daftar sekarang</a>
                </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
