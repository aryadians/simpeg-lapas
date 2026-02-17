<div class="w-full max-w-md p-6 animate__animated animate__zoomIn animate__faster">
    <div class="text-center mb-10">
        {{-- Brand Identity --}}
        <div class="inline-flex items-center gap-3 mb-6">
            <div class="h-14 w-14 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center text-white font-black text-2xl shadow-2xl shadow-indigo-500/40">
                S
            </div>
            <div class="text-left">
                <h1 class="text-2xl font-black text-white leading-none tracking-tighter">SIMPEG <span class="text-indigo-400">LAPAS</span></h1>
                <p class="text-[10px] font-black text-indigo-300/60 uppercase tracking-[0.3em] mt-1">Class IIB Jombang</p>
            </div>
        </div>
    </div>

    {{-- Glassmorphism Card --}}
    <div class="bg-white/10 backdrop-blur-2xl border border-white/20 rounded-[2rem] p-8 shadow-2xl relative overflow-hidden">
        {{-- Decorative Blob --}}
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-indigo-500/20 rounded-full blur-3xl"></div>
        
        @if(!$showOtpForm)
        <form wire:submit="login" class="space-y-6 relative z-10">
            
            {{-- Input Email --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-indigo-200 uppercase tracking-widest ml-1">Email Access</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="h-5 w-5 text-indigo-300/40" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                    </div>
                    <input wire:model="email" type="email" 
                           class="w-full bg-black/40 border border-white/10 rounded-2xl px-4 pl-12 py-4 text-white placeholder-white/20 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-300 font-medium"
                           placeholder="name@lapas.go.id" required>
                </div>
                @error('email') <span class="text-rose-400 text-[10px] font-bold uppercase tracking-wide px-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Input Password --}}
            <div class="space-y-2">
                <label class="text-[10px] font-black text-indigo-200 uppercase tracking-widest ml-1">Secure Key</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                        <svg class="h-5 w-5 text-indigo-300/40" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <input wire:model="password" type="password" 
                           class="w-full bg-black/40 border border-white/10 rounded-2xl px-4 pl-12 py-4 text-white placeholder-white/20 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-300 font-medium"
                           placeholder="••••••••" required>
                </div>
                @error('password') <span class="text-rose-400 text-[10px] font-bold uppercase tracking-wide px-1 block">{{ $message }}</span> @enderror
            </div>

            {{-- Tombol Submit --}}
            <div class="pt-2">
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black py-4 rounded-2xl shadow-xl shadow-indigo-600/30 transform transition-all duration-300 hover:shadow-indigo-600/50 hover:-translate-y-1 active:scale-95 group uppercase tracking-widest text-sm">
                    <span wire:loading.remove wire:target="login">
                        Authenticate
                    </span>
                    <div wire:loading wire:target="login" class="flex items-center justify-center">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </button>
            </div>
        </form>
        @else
        <form wire:submit="verifyOtp" class="space-y-6 relative z-10 animate__animated animate__fadeIn">
            <div class="text-center mb-6">
                <h3 class="text-white font-bold text-lg">Two-Factor Authentication</h3>
                <p class="text-indigo-200 text-xs mt-1">Kode verifikasi telah dikirim ke email Anda.</p>
            </div>

            <div class="space-y-2">
                <label class="text-[10px] font-black text-indigo-200 uppercase tracking-widest ml-1">Verification Code</label>
                <input wire:model="otp" type="text" 
                       class="w-full bg-black/40 border border-white/10 rounded-2xl px-4 py-4 text-white placeholder-white/20 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition duration-300 font-medium text-center text-2xl tracking-[0.5em]"
                       placeholder="XXXXXX" maxlength="6" autofocus>
                @error('otp') <span class="text-rose-400 text-[10px] font-bold uppercase tracking-wide px-1 block text-center">{{ $message }}</span> @enderror
            </div>

            <div class="pt-2">
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-black py-4 rounded-2xl shadow-xl shadow-emerald-600/30 transform transition-all duration-300 hover:shadow-emerald-600/50 hover:-translate-y-1 active:scale-95 group uppercase tracking-widest text-sm">
                    <span wire:loading.remove wire:target="verifyOtp">
                        Verify Identity
                    </span>
                    <div wire:loading wire:target="verifyOtp" class="flex items-center justify-center">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </button>
            </div>
        </form>
        @endif
    </div>
    
    <div class="mt-12 flex flex-col items-center gap-4">
        <div class="h-px w-12 bg-white/10"></div>
        <p class="text-indigo-300/40 text-[10px] font-black uppercase tracking-[0.4em]">
            &copy; {{ date('Y') }} Kemenkumham RI
        </p>
    </div>
</div>
