<div class="min-h-screen bg-gray-50 p-6 lg:p-8 font-sans">
    <div class="max-w-4xl mx-auto">
        
        {{-- HEADER --}}
        <header class="mb-10 animate__animated animate__fadeInDown relative">
            <div class="p-8 bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden relative">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-slate-100 rounded-full blur-3xl opacity-60"></div>
                <div class="relative z-10">
                    <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Core <span class="text-indigo-600">Settings</span></h1>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mt-1">System Architecture Configuration</p>
                </div>
            </div>
        </header>

        <form wire:submit.prevent="save" class="space-y-8 animate__animated animate__fadeInUp">
            
            {{-- GENERAL SETTINGS --}}
            <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 bg-gray-50/30">
                    <h3 class="text-lg font-black text-gray-900 uppercase tracking-tighter">General Authority</h3>
                </div>
                <div class="p-8 space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Platform Identity (App Name)</label>
                        <input wire:model="app_name" type="text" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-bold uppercase tracking-tight">
                    </div>
                </div>
            </div>

            {{-- GEOLOCATION SETTINGS --}}
            <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 bg-gray-50/30 flex justify-between items-center">
                    <h3 class="text-lg font-black text-gray-900 uppercase tracking-tighter">Geofencing Perimeter</h3>
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-[9px] font-black tracking-widest uppercase">GPS Active</span>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Office Latitude</label>
                                <input wire:model="office_latitude" type="text" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-mono font-bold">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Office Longitude</label>
                                <input wire:model="office_longitude" type="text" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-mono font-bold">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Allowed Radius (Meters)</label>
                                <div class="relative">
                                    <input wire:model="geofence_radius" type="number" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 pr-12 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-bold font-mono">
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-[10px] font-black text-gray-400">MTR</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-900 rounded-[2rem] p-8 flex flex-col items-center justify-center text-center shadow-inner relative overflow-hidden group">
                            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20"></div>
                            <div class="relative z-10">
                                <div class="h-20 w-20 bg-indigo-500/20 rounded-full flex items-center justify-center mb-4 border border-indigo-500/30 animate-pulse">
                                    <div class="h-4 w-4 bg-indigo-500 rounded-full shadow-[0_0_15px_#6366f1]"></div>
                                </div>
                                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Map Satellite Preview</p>
                                <p class="text-[9px] text-gray-500 mt-2 font-medium uppercase tracking-tight">Perimeter Locked to<br>Institutional Coordinates</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECURITY SETTINGS --}}
            <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 bg-gray-50/30">
                    <h3 class="text-lg font-black text-gray-900 uppercase tracking-tighter">Security Firewall</h3>
                </div>
                <div class="p-8 space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Allowed Admin IP Addresses (Comma separated)</label>
                        <textarea wire:model="allowed_ips" rows="3" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-rose-500 focus:border-transparent transition font-mono font-bold text-xs" placeholder="127.0.0.1, 192.168.1.1"></textarea>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest px-1 italic">Leave empty to disable IP restriction.</p>
                    </div>
                </div>
            </div>

            {{-- SAVE BUTTON --}}
            <div class="flex justify-end pt-4 pb-20">
                <button type="submit" class="px-12 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-lg shadow-indigo-500/30 transform transition-all active:scale-95 uppercase tracking-[0.2em] text-xs">
                    Commit Configurations
                </button>
            </div>

        </form>
    </div>
</div>
