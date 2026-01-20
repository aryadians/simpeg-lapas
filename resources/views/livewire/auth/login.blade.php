<div class="w-full max-w-md p-6">
    <div class="text-center mb-8">
        <div class="h-16 w-16 bg-gradient-to-tr from-indigo-500 to-purple-500 rounded-2xl mx-auto flex items-center justify-center shadow-lg shadow-indigo-500/50 mb-4">
            <span class="text-3xl">👮‍♂️</span>
        </div>
        <h2 class="text-3xl font-bold text-white tracking-tight">SIMPEG LAPAS</h2>
        <p class="text-indigo-200 mt-2">Silakan login untuk masuk ke sistem</p>
    </div>

    <div class="bg-white/10 backdrop-blur-lg border border-white/20 rounded-3xl p-8 shadow-2xl">
        <form wire:submit="login" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-indigo-100 mb-2">Email Dinas</label>
                <input wire:model="email" type="email" 
                       class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                       placeholder="nama@lapas.go.id">
                @error('email') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-indigo-100 mb-2">Password</label>
                <input wire:model="password" type="password" 
                       class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                       placeholder="••••••••">
                @error('password') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <button type="submit" 
                    class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold py-3.5 rounded-xl shadow-lg shadow-indigo-600/30 transform transition hover:-translate-y-1 active:scale-95">
                MASUK
                <span wire:loading class="ml-2 animate-spin">⏳</span>
            </button>
        </form>
    </div>
</div>