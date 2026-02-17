<div class="min-h-screen bg-gray-50 p-4 sm:p-6 lg:p-8 font-sans">
    <div class="max-w-3xl mx-auto">
        
        {{-- BAGIAN 1: PROFILE HEADER --}}
        <header class="mb-8 p-8 bg-white rounded-3xl shadow-xl border border-gray-100/80 animate__animated animate__fadeInDown relative overflow-hidden">
            <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-50 rounded-full blur-3xl opacity-60"></div>
            <div class="flex flex-col md:flex-row items-center gap-8 relative z-10">
                <div class="h-24 w-24 rounded-3xl bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center font-black text-white text-4xl shadow-2xl shadow-indigo-500/40 transform -rotate-3">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="text-center md:text-left">
                    <h1 class="text-4xl font-black text-gray-900 tracking-tighter">
                        {{ auth()->user()->name }}
                    </h1>
                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 mt-2">
                        <span class="text-xs font-black text-gray-400 font-mono bg-gray-50 px-2 py-1 rounded-md border border-gray-100 uppercase tracking-widest">{{ auth()->user()->nip }}</span>
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-full border border-indigo-100">
                            {{ auth()->user()->role === 'admin' ? 'SYSTEM ADMIN' : 'FIELD OFFICER' }}
                        </span>
                    </div>
                </div>
            </div>
        </header>

        <div class="space-y-8">
            {{-- BAGIAN 2: INFORMASI AKUN --}}
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100/80 animate__animated animate__fadeInUp overflow-hidden">
                <form wire:submit="updateProfile">
                    <div class="p-8 border-b border-gray-50 bg-gray-50/30">
                        <h2 class="text-xl font-black text-gray-800 uppercase tracking-tight">Personal Data</h2>
                        <p class="text-xs font-bold text-gray-400 mt-1 uppercase tracking-widest">Update your primary identity information.</p>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Full Identity Name</label>
                            <input wire:model="name" type="text" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-bold">
                            @error('name') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest px-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Email Electronic Address</label>
                            <input wire:model="email" type="email" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-bold">
                            @error('email') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest px-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="p-6 bg-gray-50/50 border-t border-gray-50 flex justify-end">
                        <button type="submit" class="px-8 py-3.5 text-white bg-indigo-600 hover:bg-indigo-700 rounded-2xl font-black shadow-lg shadow-indigo-500/20 transform transition-all active:scale-95 uppercase tracking-widest text-xs">
                            <span wire:loading.remove wire:target="updateProfile">Save Changes</span>
                            <div wire:loading wire:target="updateProfile"><svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>
                        </button>
                    </div>
                </form>
            </div>

            {{-- BAGIAN 3: UBAH PASSWORD --}}
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100/80 animate__animated animate__fadeInUp animate__delay-1s overflow-hidden">
                <form wire:submit="updatePassword">
                    <div class="p-8 border-b border-gray-50 bg-gray-50/30">
                        <h2 class="text-xl font-black text-gray-800 uppercase tracking-tight">Security Access</h2>
                        <p class="text-xs font-bold text-gray-400 mt-1 uppercase tracking-widest">Update your authentication credentials.</p>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Current Password Access</label>
                            <input wire:model="current_password" type="password" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" placeholder="••••••••">
                            @error('current_password') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest px-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">New Secure Password</label>
                                <input wire:model="password" type="password" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" placeholder="••••••••">
                                @error('password') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest px-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-1">Verify New Access</label>
                                <input wire:model="password_confirmation" type="password" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" placeholder="••••••••">
                            </div>
                        </div>
                    </div>
                    <div class="p-6 bg-gray-50/50 border-t border-gray-50 flex justify-end">
                        <button type="submit" class="px-8 py-3.5 text-white bg-indigo-600 hover:bg-indigo-700 rounded-2xl font-black shadow-lg shadow-indigo-500/20 transform transition-all active:scale-95 uppercase tracking-widest text-xs">
                            <span wire:loading.remove wire:target="updatePassword">Update Security</span>
                            <div wire:loading wire:target="updatePassword"><svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>