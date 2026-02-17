<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 | Access Denied</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@800&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-900 flex items-center justify-center min-h-screen p-6">
    <div class="text-center">
        <div class="inline-flex h-20 w-20 bg-rose-500/20 rounded-3xl items-center justify-center text-rose-500 mb-8 border border-rose-500/30">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m0-6V9m3.376-4.848a9.27 9.27 0 011.031 1.606m1.302 3.374a9.269 9.27 0 01.191 1.868c0 1.935-.584 3.734-1.587 5.232m-3.302 3.302a9.27 9.27 0 01-5.232 1.587 9.247 9.247 0 01-1.868-.191m-3.374-1.302A9.269 9.269 0 012.944 12c0-1.935.584-3.734 1.587-5.232m3.302-3.302a9.27 9.27 0 015.232-1.587c.642 0 1.266.065 1.868.191zM15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
        </div>
        <h1 class="text-4xl font-black text-white uppercase tracking-tighter">Access Denied</h1>
        <p class="text-rose-500/60 font-black uppercase tracking-[0.3em] text-[10px] mt-4">Security Protocol: Insufficient Clearance Level</p>
        <p class="text-gray-500 text-xs mt-2 max-w-xs mx-auto leading-relaxed">Your current identity credentials do not allow access to this encrypted sector.</p>
        
        <a href="{{ url('/') }}" class="mt-10 inline-block px-8 py-3 bg-white text-gray-900 font-black text-[10px] uppercase tracking-widest rounded-xl transition-all active:scale-95">Re-authenticate Identity</a>
    </div>
</body>
</html>
