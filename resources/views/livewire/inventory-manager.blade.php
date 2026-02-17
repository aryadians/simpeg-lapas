<div class="p-6 bg-gray-50 min-h-screen font-sans">
    <div class="max-w-7xl mx-auto">
        {{-- Header --}}
        <header class="mb-10 animate__animated animate__fadeInDown relative">
            <div class="flex flex-col lg:flex-row justify-between items-center gap-6 p-8 bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden relative">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-indigo-50 rounded-full blur-3xl opacity-60"></div>
                
                <div class="relative z-10 text-center lg:text-left">
                    <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Inventory <span class="text-indigo-600">Vault</span></h1>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mt-1">Manajemen Aset & Perlengkapan Jaga</p>
                </div>

                <button wire:click="openModal()" class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-lg shadow-indigo-500/30 transform transition-all active:scale-95 flex items-center gap-2 uppercase tracking-widest text-xs relative z-10">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" stroke-width="1"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" /></svg>
                    <span>Register Item</span>
                </button>
            </div>
        </header>

        {{-- Table --}}
        <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden animate__animated animate__fadeInUp">
            <div class="overflow-x-auto no-scrollbar">
                <table class="w-full text-sm text-left">
                    <thead class="text-[10px] text-gray-400 uppercase tracking-[0.2em] bg-gray-50/50 border-b border-gray-100">
                        <tr>
                            <th scope="col" class="px-8 py-5 font-black">Asset Identity</th>
                            <th scope="col" class="px-8 py-5 font-black">Status</th>
                            <th scope="col" class="px-8 py-5 font-black">Custodian</th>
                            <th scope="col" class="px-8 py-5 font-black">Timeline</th>
                            <th scope="col" class="px-8 py-5 font-black text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($inventories as $item)
                            <tr wire:key="{{ $item->id }}" class="hover:bg-indigo-50/30 transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-xl bg-gray-100 text-gray-400 flex items-center justify-center group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-all duration-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                        </div>
                                        <div>
                                            <p class="font-black text-gray-800 uppercase tracking-tight">{{ $item->name }}</p>
                                            <p class="text-[10px] text-gray-400 font-mono uppercase tracking-widest mt-0.5">{{ $item->serial_number ?: 'NO-SERIAL' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    @if ($item->status == 'available')
                                        <span class="px-3 py-1 text-[9px] font-black uppercase tracking-widest text-emerald-700 bg-emerald-50 border border-emerald-100 rounded-lg shadow-sm">AVAILABLE</span>
                                    @elseif ($item->status == 'checked_out')
                                        <span class="px-3 py-1 text-[9px] font-black uppercase tracking-widest text-amber-700 bg-amber-50 border border-amber-100 rounded-lg shadow-sm">CHECKED OUT</span>
                                    @else
                                        <span class="px-3 py-1 text-[9px] font-black uppercase tracking-widest text-gray-500 bg-gray-100 border border-gray-200 rounded-lg shadow-sm uppercase">{{ $item->status }}</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6">
                                    @if($item->holder)
                                        <div class="flex items-center gap-2">
                                            <div class="h-6 w-6 rounded-md bg-indigo-100 text-indigo-600 flex items-center justify-center font-black text-[10px] uppercase">{{ substr($item->holder->name, 0, 1) }}</div>
                                            <span class="font-bold text-gray-700 text-xs">{{ $item->holder->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-gray-300 font-black text-[10px] uppercase tracking-widest">—</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6">
                                    @if($item->checked_out_at)
                                        <div class="space-y-1">
                                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Due: {{ $item->due_at ? $item->due_at->format('d M') : 'NONE' }}</p>
                                            <p class="text-[10px] font-bold text-gray-600 font-mono uppercase">{{ $item->checked_out_at->format('H:i') }} | {{ $item->checked_out_at->format('d/m/y') }}</p>
                                        </div>
                                    @else
                                        <span class="text-gray-300 font-black text-[10px] uppercase tracking-widest">—</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex justify-end gap-2">
                                        @if ($item->status == 'available')
                                            <button wire:click="openCheckoutModal({{ $item->id }})" class="h-9 px-4 rounded-xl bg-indigo-50 text-indigo-600 font-black text-[9px] uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all duration-300 border border-indigo-100">Checkout</button>
                                        @elseif ($item->status == 'checked_out')
                                            <button wire:click="confirmCheckin({{ $item->id }})" class="h-9 px-4 rounded-xl bg-emerald-50 text-emerald-600 font-black text-[9px] uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition-all duration-300 border border-emerald-100">Checkin</button>
                                        @endif
                                        <button wire:click="openHistoryModal({{ $item->id }})" class="h-9 w-9 rounded-xl bg-gray-50 text-gray-400 flex items-center justify-center hover:bg-white hover:text-indigo-600 hover:shadow-md transition-all border border-transparent hover:border-gray-100"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center opacity-30 grayscale">
                                        <span class="text-6xl mb-4">📦</span>
                                        <p class="text-[10px] font-black text-gray-900 uppercase tracking-[0.3em]">No Assets Registered</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($inventories->hasPages())
            <div class="p-8 border-t border-gray-50 bg-gray-50/30">
                {{ $inventories->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- Add Item Modal --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div wire:click="closeModal" class="absolute inset-0 bg-black/60 backdrop-blur-sm animate__animated animate__fadeIn animate__faster"></div>
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg relative animate__animated animate__zoomIn animate__faster overflow-hidden">
            <form wire:submit="store">
                <div class="p-8 border-b border-gray-50 bg-gray-50/50">
                    <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tighter text-center">Register <span class="text-indigo-600">Asset</span></h2>
                </div>
                <div class="p-8 space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Asset Designation</label>
                        <input wire:model="name" type="text" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-bold" placeholder="Item Name (e.g. Radio HT)">
                        @error('name') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest px-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Serial Authority</label>
                        <input wire:model="serial_number" type="text" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-bold font-mono" placeholder="SN-XXXXXXX">
                        @error('serial_number') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest px-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Condition Statement</label>
                        <textarea wire:model="description" rows="3" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none font-medium" placeholder="Current asset status..."></textarea>
                    </div>
                </div>
                <div class="p-8 bg-gray-50/50 border-t border-gray-50 flex items-center gap-3">
                    <button type="button" wire:click="closeModal" class="flex-1 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-gray-600 transition-colors">Dismiss</button>
                    <button type="submit" class="flex-[2] py-4 text-white bg-indigo-600 hover:bg-indigo-700 rounded-2xl font-black shadow-lg shadow-indigo-500/30 transform transition active:scale-95 uppercase tracking-[0.2em] text-xs">Confirm Entry</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Checkout Modal --}}
    @if($isCheckoutModalOpen)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
         <div wire:click="closeCheckoutModal" class="absolute inset-0 bg-black/60 backdrop-blur-sm animate__animated animate__fadeIn animate__faster"></div>
         <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg relative animate__animated animate__zoomIn animate__faster overflow-hidden">
            <form wire:submit="checkout">
                <div class="p-8 border-b border-gray-50 bg-gray-50/50">
                    <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tighter text-center">Asset <span class="text-amber-600">Assignment</span></h2>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest text-center mt-1">{{ $selectedItem?->name }}</p>
                </div>
                <div class="p-8 space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Designate Custodian</label>
                        <select wire:model="selectedUser" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-bold">
                            <option value="">Select Staff</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                        @error('selectedUser') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest px-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Return Expiration</label>
                        <input wire:model="due_at" type="datetime-local" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-bold">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Handover Notes</label>
                        <textarea wire:model="notes" rows="3" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition resize-none font-medium" placeholder="Notes..."></textarea>
                    </div>
                </div>
                <div class="p-8 bg-gray-50/50 border-t border-gray-50 flex items-center">
                    <button type="submit" class="w-full py-4 text-white bg-amber-600 hover:bg-amber-700 rounded-2xl font-black shadow-lg shadow-amber-500/30 transform transition active:scale-95 uppercase tracking-[0.2em] text-xs">Confirm Assignment</button>
                </div>
            </form>
        </div>
    </div>
    @endif

     {{-- History Modal --}}
    @if($isHistoryModalOpen)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
         <div wire:click="closeHistoryModal" class="absolute inset-0 bg-black/60 backdrop-blur-sm animate__animated animate__fadeIn animate__faster"></div>
         <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-2xl relative animate__animated animate__zoomIn animate__faster overflow-hidden">
            <div class="p-8 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tighter">Audit <span class="text-indigo-600">Trail</span></h2>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mt-1">Asset: {{ $selectedItem?->name }}</p>
                </div>
                <button wire:click="closeHistoryModal" class="w-10 h-10 rounded-xl bg-white border border-gray-100 text-gray-400 flex items-center justify-center hover:bg-gray-100 transition-all">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <div class="p-8 max-h-[60vh] overflow-y-auto no-scrollbar">
                <div class="space-y-8">
                    @forelse($selectedItem->logs as $log)
                        <div class="flex gap-6 relative group">
                            @if(!$loop->last)
                                <div class="absolute left-6 top-10 bottom-[-2rem] w-0.5 bg-gray-100 group-hover:bg-indigo-100 transition-colors"></div>
                            @endif
                            <div class="flex-shrink-0 relative z-10">
                                @if($log->action == 'checked_out')
                                    <span class="h-12 w-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shadow-sm border border-amber-100">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 5l7 7-7 7M5 5l7 7-7 7" /></svg>
                                    </span>
                                @else
                                    <span class="h-12 w-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shadow-sm border border-emerald-100">
                                         <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" /></svg>
                                    </span>
                                @endif
                            </div>
                            <div class="flex-1 pb-4">
                                <p class="text-sm font-black text-gray-800 uppercase tracking-tight">
                                    {{ $log->action == 'checked_out' ? 'Asset Outbound' : 'Asset Inbound' }}
                                </p>
                                <div class="flex items-center gap-2 mt-1">
                                    <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">{{ $log->user->name }}</p>
                                    <span class="text-[10px] text-gray-300">•</span>
                                    <p class="text-[10px] font-bold text-gray-400 font-mono">{{ $log->action_at->format('d M Y, H:i') }}</p>
                                </div>
                                @if($log->notes)
                                    <div class="mt-3 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                                        <p class="text-xs text-gray-600 font-medium italic leading-relaxed">"{{ $log->notes }}"</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10 opacity-30 grayscale">
                            <span class="text-4xl mb-2 block">📄</span>
                            <p class="text-[10px] font-black text-gray-900 uppercase tracking-widest">No Logs Available</p>
                        </div>
                    @endforelse
                </div>
            </div>
             <div class="p-8 bg-gray-50/50 border-t border-gray-50 text-center">
                <button type="button" wire:click="closeHistoryModal" class="text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-indigo-600 transition-colors">End Review</button>
            </div>
        </div>
    </div>
    @endif

</div>
