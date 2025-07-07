<x-guest-layout>
    <div class="flex items-center justify-center w-full">
        <div class="flex flex-col md:flex-row bg-white shadow-xl rounded-lg overflow-hidden max-w-4xl w-full my-12">

            {{-- LEFT: Logo --}}
            <div class="hidden md:flex w-full md:w-1/2 bg-gray-50 items-center justify-center p-8 animate-slide-in-left">
                <img src="https://pindad.com/assets/img/theme-1/logo.png" alt="Logo Pindad" class="w-60 h-auto transition-transform duration-700 hover:scale-105 opacity-90" />
            </div>

            {{-- RIGHT: Forgot Password Form --}}
            <div class="w-full md:w-1/2 p-8 animate-fade-in">
                <h2 class="text-2xl font-bold text-center mb-6">Lupa Password?</h2>

                <p class="text-sm text-gray-600 text-center mb-6">
                    Masukkan email kamu dan kami akan mengirimkan link reset password.
                </p>

                <!-- Status Message -->
                @if (session('status'))
                    <div class="mb-4 text-green-600 text-sm text-center font-semibold">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block font-semibold mb-1">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full border rounded px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                            Kirim Link Reset
                        </button>
                    </div>

                    <div class="text-center mt-4 text-sm text-gray-600">
                        <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Kembali ke Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
