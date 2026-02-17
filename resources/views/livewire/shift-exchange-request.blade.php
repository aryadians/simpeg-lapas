<div class="min-h-screen bg-gray-50 p-6 lg:p-8 font-sans">
    <div class="max-w-6xl mx-auto">
        
        {{-- HEADER --}}
        <header class="mb-10 animate__animated animate__fadeInDown relative">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 p-8 bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden relative">
                <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-teal-50 rounded-full blur-3xl opacity-60"></div>
                <div class="relative z-10 text-center md:text-left">
                    <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Shift <span class="text-teal-600">Exchange</span></h1>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mt-1">Layanan Pertukaran Dinas Pegawai</p>
                </div>
            </div>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Form Request --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-8 sticky top-24">
                    <h3 class="text-lg font-black text-gray-900 uppercase tracking-tighter mb-6">Create Request</h3>
                    
                    <form wire:submit.prevent="submitRequest" class="space-y-5">
                        
                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">My Schedule to Swap</label>
                            <select wire:model="selectedRosterId" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-teal-500 focus:border-transparent transition font-bold text-xs">
                                <option value="">Select Date</option>
                                @foreach($myRosters as $roster)
                                    <option value="{{ $roster->id }}">{{ \Carbon\Carbon::parse($roster->date)->format('d M Y') }} - {{ $roster->shift->name }}</option>
                                @endforeach
                            </select>
                            @error('selectedRosterId') <span class="text-rose-500 text-[9px] font-bold uppercase tracking-widest px-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Swap With (Person)</label>
                            <select wire:model.live="selectedTargetUserId" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-teal-500 focus:border-transparent transition font-bold text-xs">
                                <option value="">Select Colleague</option>
                                @foreach($targetUsers as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('selectedTargetUserId') <span class="text-rose-500 text-[9px] font-bold uppercase tracking-widest px-1 block">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Target Schedule (Optional)</label>
                            <select wire:model="selectedTargetRosterId" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-teal-500 focus:border-transparent transition font-bold text-xs" {{ $targetRosters->isEmpty() ? 'disabled' : '' }}>
                                <option value="">Select Target Date</option>
                                @foreach($targetRosters as $roster)
                                    <option value="{{ $roster->id }}">{{ \Carbon\Carbon::parse($roster->date)->format('d M Y') }} - {{ $roster->shift->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Reason</label>
                            <textarea wire:model="reason" rows="3" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-teal-500 focus:border-transparent transition font-medium text-xs resize-none" placeholder="Alasan tukar..."></textarea>
                            @error('reason') <span class="text-rose-500 text-[9px] font-bold uppercase tracking-widest px-1 block">{{ $message }}</span> @enderror
                        </div>

                        <button type="submit" class="w-full py-4 text-white bg-teal-600 hover:bg-teal-700 rounded-2xl font-black shadow-lg shadow-teal-500/20 transform transition active:scale-95 uppercase tracking-[0.2em] text-xs">
                            Send Request
                        </button>
                    </form>
                </div>
            </div>

            {{-- Lists --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- ADMIN APPROVAL --}}
                @if(Auth::user()->role === 'admin' && count($adminPendingRequests) > 0)
                <div class="bg-indigo-50 rounded-[2rem] p-8 border border-indigo-100">
                    <h3 class="text-lg font-black text-indigo-900 uppercase tracking-tighter mb-4">Need Final Approval</h3>
                    <div class="space-y-4">
                        @foreach($adminPendingRequests as $req)
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-indigo-100">
                            <div class="flex justify-between items-center mb-4">
                                <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-[9px] font-black uppercase tracking-widest">Verified by Staff</span>
                                <span class="text-xs font-bold text-gray-400">{{ $req->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="flex items-center gap-4 text-sm font-bold text-gray-700">
                                <div>{{ $req->requester->name }} <br> <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($req->rosterFrom->date)->format('d M') }} ({{ $req->rosterFrom->shift->name }})</span></div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-300" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                <div>{{ $req->targetUser->name }} <br> <span class="text-xs text-gray-400">{{ $req->rosterTo ? \Carbon\Carbon::parse($req->rosterTo->date)->format('d M') . ' (' . $req->rosterTo->shift->name . ')' : 'No Return Shift' }}</span></div>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-50 flex justify-end gap-2">
                                <button wire:click="rejectRequest({{ $req->id }})" class="px-4 py-2 bg-white text-rose-600 border border-rose-100 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-rose-50">Reject</button>
                                <button wire:click="adminApprove({{ $req->id }})" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-500/30 hover:bg-indigo-700">Authorize Swap</button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- INCOMING REQUESTS --}}
                <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-8">
                    <h3 class="text-lg font-black text-gray-900 uppercase tracking-tighter mb-6">Inbox Requests</h3>
                    <div class="space-y-4">
                        @forelse($incomingRequests as $req)
                        <div class="bg-teal-50/50 p-6 rounded-2xl border border-teal-100">
                            <div class="flex items-start gap-4">
                                <div class="h-12 w-12 rounded-xl bg-teal-100 flex items-center justify-center text-teal-600 font-black text-lg">
                                    {{ substr($req->requester->name, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <p class="font-bold text-gray-800"><span class="text-teal-600">{{ $req->requester->name }}</span> wants to swap.</p>
                                    <p class="text-xs text-gray-500 mt-1">"{{ $req->reason }}"</p>
                                    <div class="mt-3 p-3 bg-white rounded-xl border border-gray-100 text-xs text-gray-600 font-mono">
                                        OFFER: {{ \Carbon\Carbon::parse($req->rosterFrom->date)->format('d M') }} ({{ $req->rosterFrom->shift->name }}) <br>
                                        WANT: {{ $req->rosterTo ? \Carbon\Carbon::parse($req->rosterTo->date)->format('d M') . ' (' . $req->rosterTo->shift->name . ')' : 'Free Shift' }}
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 flex justify-end gap-3">
                                <button wire:click="rejectRequest({{ $req->id }})" class="px-4 py-2 text-rose-600 font-black text-[10px] uppercase tracking-widest hover:bg-rose-50 rounded-lg">Decline</button>
                                <button wire:click="approveRequest({{ $req->id }})" class="px-6 py-2 bg-teal-600 text-white font-black text-[10px] uppercase tracking-widest rounded-xl shadow-lg shadow-teal-500/20 hover:bg-teal-700">Accept</button>
                            </div>
                        </div>
                        @empty
                        <p class="text-center text-gray-400 py-8 text-xs font-bold uppercase tracking-widest">No Incoming Requests</p>
                        @endforelse
                    </div>
                </div>

                {{-- MY HISTORY --}}
                <div class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-8">
                    <h3 class="text-lg font-black text-gray-900 uppercase tracking-tighter mb-6">My History</h3>
                    <div class="space-y-4">
                        @forelse($myRequests as $req)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-2xl border border-gray-100">
                            <div>
                                <p class="text-xs font-bold text-gray-600">To: {{ $req->targetUser->name }}</p>
                                <p class="text-[10px] text-gray-400 uppercase tracking-widest mt-0.5">{{ $req->created_at->format('d M Y') }}</p>
                            </div>
                            <span class="px-3 py-1 text-[9px] font-black uppercase tracking-widest rounded-lg 
                                @if($req->status == 'pending') bg-amber-50 text-amber-600 border border-amber-100 @endif
                                @if($req->status == 'approved_by_target') bg-indigo-50 text-indigo-600 border border-indigo-100 @endif
                                @if($req->status == 'approved_by_admin') bg-emerald-50 text-emerald-600 border border-emerald-100 @endif
                                @if($req->status == 'rejected') bg-rose-50 text-rose-600 border border-rose-100 @endif
                            ">
                                {{ str_replace('_', ' ', $req->status) }}
                            </span>
                        </div>
                        @empty
                        <p class="text-center text-gray-400 py-8 text-xs font-bold uppercase tracking-widest">No History</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
