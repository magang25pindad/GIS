<x-guest-layout>
    <div class="flex items-center justify-center w-full">
        <div class="flex flex-col md:flex-row bg-white shadow-xl rounded-lg overflow-hidden max-w-4xl w-full my-12">

            {{-- LEFT: Logo --}}
            <div class="hidden md:flex w-full md:w-1/2 bg-gray-50 items-center justify-center p-8 animate-slide-in-left">
                <img src="https://pindad.com/assets/img/theme-1/logo.png" alt="Logo Pindad" class="w-60 h-auto transition-transform duration-700 hover:scale-105 opacity-90" />
            </div>

            {{-- RIGHT: Register Form --}}
            <div class="w-full md:w-1/2 p-8 animate-fade-in">
                <h2 class="text-3xl font-bold mb-6 text-center">Buat Akun Baru</h2>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block font-semibold mb-1">Nama Lengkap</label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block font-semibold mb-1">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block font-semibold mb-1">Password</label>
                        <input id="password" name="password" type="password" required class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block font-semibold mb-1">Konfirmasi Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Action -->
                    <div class="flex items-center justify-between">
                        <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-blue-600 underline">Sudah punya akun?</a>

                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                            Daftar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
