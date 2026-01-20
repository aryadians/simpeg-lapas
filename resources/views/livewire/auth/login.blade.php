<div class="w-full max-w-md p-6 animate__animated animate__zoomIn animate__faster">
    <div class="text-center mb-8">
        {{-- Ganti Emoji dengan SVG Icon --}}
        <div class="h-20 w-20 bg-white/10 border border-white/20 rounded-3xl mx-auto flex items-center justify-center shadow-2xl shadow-purple-500/10 mb-5">
            <svg class="h-10 w-10 text-indigo-300" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.286zm0 13.036h.008v.008h-.008v-.008z" />
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-white tracking-tight">SIMPEG LAPAS</h1>
        <p class="text-indigo-200 mt-2 font-light">Selamat datang kembali, silakan login.</p>
    </div>

    {{-- Card Kaca --}}
    <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-3xl p-8 shadow-2xl">
        <form wire:submit="login" class="space-y-6">
            
            {{-- Input Email --}}
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                    <svg class="h-5 w-5 text-indigo-300/50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                    </svg>
                </div>
                <input wire:model="email" type="email" 
                       class="w-full bg-black/20 border border-white/10 rounded-xl px-4 pl-12 py-3 text-white placeholder-gray-400/60 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-300"
                       placeholder="email@lapas.go.id" required>
            </div>
            @error('email') <span class="text-red-400 text-xs -mt-4 block px-1">{{ $message }}</span> @enderror

            {{-- Input Password --}}
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                    <svg class="h-5 w-5 text-indigo-300/50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                    </svg>
                </div>
                <input wire:model="password" type="password" 
                       class="w-full bg-black/20 border border-white/10 rounded-xl px-4 pl-12 py-3 text-white placeholder-gray-400/60 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-300"
                       placeholder="••••••••" required>
            </div>
            @error('password') <span class="text-red-400 text-xs -mt-4 block px-1">{{ $message }}</span> @enderror

            {{-- Tombol Submit --}}
            <button type="submit" 
                    class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-indigo-600/30 transform transition-all duration-300 hover:shadow-indigo-600/50 hover:-translate-y-1 active:scale-95 group">
                <span wire:loading.remove wire:target="login">
                    MASUK KE SISTEM
                </span>
                <div wire:loading wire:target="login" class="flex items-center justify-center">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="ml-2">Memproses...</span>
                </div>
            </button>
        </form>
    </div>
    <div class="text-center mt-6 text-indigo-300/60 text-xs">
        &copy; {{ date('Y') }} Lapas Kelas IIB Purwakarta
    </div>
</div>