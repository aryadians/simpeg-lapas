<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} | Portal</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-900 overflow-hidden">
    <div class="relative min-h-screen flex items-center justify-center p-6">
        {{-- Animated Background --}}
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-[10%] -left-[10%] w-[40%] h-[40%] bg-indigo-600/20 rounded-full blur-[120px] animate-pulse"></div>
            <div class="absolute -bottom-[10%] -right-[10%] w-[40%] h-[40%] bg-purple-600/20 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>
        </div>

        {{-- Content --}}
        <div class="relative z-10 w-full max-w-xl text-center">
            <div class="inline-flex h-20 w-20 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-[2rem] items-center justify-center text-white font-black text-4xl shadow-2xl shadow-indigo-500/40 mb-8 animate__animated animate__zoomIn">
                {{ substr(config('app.name'), 0, 1) }}
            </div>
            
            <h1 class="text-5xl md:text-6xl font-black text-white tracking-tighter uppercase mb-4 animate__animated animate__fadeInUp">
                {{ config('app.name') }}
            </h1>
            <p class="text-indigo-200/60 text-sm font-black uppercase tracking-[0.4em] mb-12 animate__animated animate__fadeInUp animate__delay-1s">
                Institutional Personnel Intelligence
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center animate__animated animate__fadeInUp animate__delay-2s">
                @auth
                    <a href="{{ url('/') }}" class="px-10 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-xl shadow-indigo-500/30 transition-all active:scale-95 uppercase tracking-widest text-xs">
                        Enter Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="px-10 py-4 bg-white text-indigo-900 font-black rounded-2xl shadow-xl transition-all active:scale-95 uppercase tracking-widest text-xs">
                        Secure Login
                    </a>
                @endauth
            </div>
        </div>

        {{-- Footer --}}
        <div class="absolute bottom-10 left-0 right-0 text-center opacity-20">
            <p class="text-[10px] font-black text-white uppercase tracking-[0.5em]">&copy; {{ date('Y') }} Institutional Perimeter Control</p>
        </div>
    </div>
</body>
</html>
