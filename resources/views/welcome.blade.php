<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Peta Monitoring Telepon</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
            @tailwind base;
            @tailwind components;
            @tailwind utilities;
        </style>
    @endif

    <!-- Custom Background Animation -->
    <style>
        body {
            background: radial-gradient(-45deg, #2c3e50, #3498db, #2ecc71, #9b59b6);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            font-family: 'Instrument Sans', sans-serif;
        }

        @keyframes gradientBG {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }
    </style>
</head>
<body class="text-white flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col text-center">
    <x-application-logo class="block w-64 h-svw fill-current text-white mb-4 animate-fade-in" />

    <h1 class="text-2xl font-bold mb-2 text-black animate-fade-in">Peta Monitoring Telepon</h1>

    @if (Route::has('login'))
        <nav class="flex items-center justify-center gap-4 mt-4">
            @auth
            <div class= text-blue-500 font-semibold px-4 py-2 rounded shadow-md transition transform duration-300 ease-in-out hover:-translate-y-1 hover:scale-110 hover:bg-indigo-500 drop-shadow-lg">You are logged in as {{ Auth::user()->name }}</div>
                <button class="bg-blue-500 text-white font-semibold px-4 py-2 rounded shadow-md
         transition transform duration-300 ease-in-out
         hover:-translate-y-1 hover:scale-110 hover:bg-indigo-500 drop-shadow-lg">
                    <a href="{{ url('/dashboard') }}">
                       Go To Dashboard
                    </a>
                </button>

            @else
                <a href="{{ route('login') }}"
                   class="px-5 py-2 bg-cyan-600 border border-white text-white hover:bg-blue hover:text-black transition rounded">
                    Log in
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="px-5 py-2 bg-cyan-600 border border-white text-white hover:bg-white hover:text-black transition rounded">
                        Register
                    </a>
                @endif
            @endauth
        </nav>
    @endif

</body>
</html>
