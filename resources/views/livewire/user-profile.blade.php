<div class="min-h-screen bg-gray-50 p-4 sm:p-6 lg:p-8 font-sans">
    <div class="max-w-3xl mx-auto">
        
        {{-- BAGIAN 1: PROFILE HEADER --}}
        <header class="mb-8 p-6 bg-white rounded-2xl shadow-lg border border-gray-100/80 animate__animated animate__fadeInDown">
            <div class="flex items-center gap-6">
                <div class="h-20 w-20 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center font-black text-white text-4xl shadow-lg shadow-indigo-500/30">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">
                        {{ auth()->user()->name }}
                    </h1>
                    <p class="text-gray-500 mt-1 font-mono">{{ auth()->user()->nip }}</p>
                    <p class="mt-1 font-semibold text-indigo-600">{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Petugas' }}</p>
                </div>
            </div>
        </header>

        <div class="space-y-8">
            {{-- BAGIAN 2: INFORMASI AKUN --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100/80 animate__animated animate__fadeInUp">
                <form wire:submit="updateProfile">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-xl font-bold text-gray-800">Informasi Akun</h2>
                        <p class="text-sm text-gray-500 mt-1">Perbarui nama dan alamat email Anda.</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-600 mb-1">Nama Lengkap</label>
                            <input wire:model="name" type="text" class="w-full bg-gray-100 border-transparent text-gray-900 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-600 mb-1">Alamat Email</label>
                            <input wire:model="email" type="email" class="w-full bg-gray-100 border-transparent text-gray-900 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition">
                            @error('email') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-b-2xl flex justify-end">
                        <button type="submit" class="px-5 py-2 text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-xl font-bold shadow-md shadow-indigo-500/20 transform transition-all hover:-translate-y-0.5 flex items-center justify-center min-w-[120px]">
                            <span wire:loading.remove wire:target="updateProfile">Simpan Profil</span>
                            <div wire:loading wire:target="updateProfile" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span>Menyimpan...</span>
                            </div>
                        </button>
                    </div>
                </form>
            </div>

            {{-- BAGIAN 3: UBAH PASSWORD --}}
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100/80 animate__animated animate__fadeInUp animate__delay-1s">
                <form wire:submit="updatePassword">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-xl font-bold text-gray-800">Ubah Password</h2>
                        <p class="text-sm text-gray-500 mt-1">Pastikan Anda menggunakan password yang kuat dan unik.</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-600 mb-1">Password Saat Ini</label>
                            <input wire:model="current_password" type="password" class="w-full bg-gray-100 border-transparent text-gray-900 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" placeholder="••••••••">
                            @error('current_password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-600 mb-1">Password Baru</label>
                                <input wire:model="password" type="password" class="w-full bg-gray-100 border-transparent text-gray-900 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" placeholder="••••••••">
                                @error('password') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-600 mb-1">Konfirmasi Password Baru</label>
                                <input wire:model="password_confirmation" type="password" class="w-full bg-gray-100 border-transparent text-gray-900 rounded-lg p-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition" placeholder="••••••••">
                            </div>
                        </div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-b-2xl flex justify-end">
                        <button type="submit" class="px-5 py-2 text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 rounded-xl font-bold shadow-md shadow-indigo-500/20 transform transition-all hover:-translate-y-0.5 flex items-center justify-center min-w-[120px]">
                            <span wire:loading.remove wire:target="updatePassword">Ubah Password</span>
                            <div wire:loading wire:target="updatePassword" class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span>Menyimpan...</span>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>