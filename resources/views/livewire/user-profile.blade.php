<div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
    <div class="md:grid md:grid-cols-3 md:gap-6">
        
        {{-- KOLOM 1: JUDUL --}}
        <div class="md:col-span-1">
            <div class="px-4 sm:px-0">
                <h3 class="text-lg font-medium leading-6 text-gray-900">Profil Saya</h3>
                <p class="mt-1 text-sm text-gray-600">
                    Perbarui informasi akun dan amankan akun Anda dengan password yang kuat.
                </p>
                
                <div class="mt-6 flex justify-center md:justify-start">
                    <div class="h-24 w-24 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                        {{ substr(auth()->user()->name, 0, 2) }}
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM 2: FORM --}}
        <div class="mt-5 md:mt-0 md:col-span-2 space-y-6">
            
            {{-- Form Data Diri --}}
            <div class="shadow sm:rounded-md sm:overflow-hidden bg-white">
                <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                            <input wire:model="name" type="text" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input wire:model="email" type="email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button wire:click="updateProfile" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                        Simpan Profil
                    </button>
                </div>
            </div>

            {{-- Form Ganti Password --}}
            <div class="shadow sm:rounded-md sm:overflow-hidden bg-white">
                <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                    <h4 class="text-md font-bold text-gray-800 border-b pb-2">Ganti Password</h4>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password Saat Ini</label>
                        <input wire:model="current_password" type="password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @error('current_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-6 sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700">Password Baru</label>
                            <input wire:model="password" type="password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="col-span-6 sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                            <input wire:model="password_confirmation" type="password" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button wire:click="updatePassword" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none">
                        Update Password
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>