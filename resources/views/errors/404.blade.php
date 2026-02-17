<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 | Sector Not Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@800&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-6">
    <div class="text-center">
        <h1 class="text-[150px] font-black text-gray-200 leading-none select-none">404</h1>
        <div class="relative -mt-20">
            <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">Sector Not Found</h2>
            <p class="text-gray-400 font-bold uppercase tracking-widest text-[10px] mt-2">The requested operational area does not exist.</p>
            <a href="{{ url('/') }}" class="mt-8 inline-block px-8 py-3 bg-indigo-600 text-white font-black text-[10px] uppercase tracking-widest rounded-xl shadow-lg shadow-indigo-500/30 hover:bg-indigo-700 transition-all active:scale-95">Return to Command Center</a>
        </div>
    </div>
</body>
</html>
