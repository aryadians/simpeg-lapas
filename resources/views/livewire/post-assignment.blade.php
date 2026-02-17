<div class="min-h-screen bg-gray-50 text-gray-800 font-sans" x-data="{
    dropTarget: null
}">
    <style>
        :root {
            --glow-color: rgb(124 58 237 / 0.5);
        }
        .card-3d {
            transition: transform 0.1s ease-out;
            will-change: transform;
            transform-style: preserve-3d;
        }
        .roster-card.dragging {
            opacity: 0.4;
            transform: scale(0.9) !important;
        }
        .drop-zone-active {
            outline: 3px dashed var(--glow-color);
            outline-offset: 4px;
            background-color: rgba(139, 92, 246, 0.05);
            border-radius: 2rem;
        }
    </style>

    <div class="p-4 sm:p-6 lg:p-8 max-w-screen-2xl mx-auto pb-20">
        <!-- ======================================= -->
        <!-- HEADER -->
        <!-- ======================================= -->
        <header class="mb-10 animate__animated animate__fadeInDown relative">
            <div class="p-8 bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden relative">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-teal-50 rounded-full blur-3xl opacity-60"></div>
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Sector <span class="text-teal-600">Deploy</span></h1>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mt-1">Plotting Pos Jaga & Penempatan Personel</p>
                    </div>
                    
                    <div class="flex items-center gap-3 bg-gray-50 p-1.5 rounded-2xl border border-gray-100 shadow-inner">
                        <input type="date" wire:model.live="selectedDate" class="bg-white border-gray-200 text-gray-900 text-[10px] font-black uppercase tracking-widest rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent px-4 py-2.5 shadow-sm transition-all">
                        <select wire:model.live="selectedShift" class="bg-white border-gray-200 text-gray-900 text-[10px] font-black uppercase tracking-widest rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent px-4 py-2.5 shadow-sm transition-all">
                            <option value="">All Operational Shifts</option>
                            @foreach ($shifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </header>

        <!-- ======================================= -->
        <!-- UNASSIGNED STAFF SHELF -->
        <!-- ======================================= -->
        <div class="mb-12 drop-zone rounded-[2.5rem] p-2"
             wire:key="unassigned-drop-zone"
             :class="{ 'drop-zone-active': $wire.draggedRosterId && dropTarget === 'unassigned' }"
             @dragover.prevent @dragenter.prevent="dropTarget = 'unassigned'"
             @dragleave.prevent="dropTarget = null"
             @drop.prevent="$wire.assignPost(null).then(() => { dropTarget = null })">

            <div class="bg-white rounded-[2.5rem] shadow-xl border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex flex-col sm:flex-row justify-between items-center gap-6 bg-gray-50/30">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 rounded-xl bg-teal-600 flex items-center justify-center text-white shadow-lg shadow-teal-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-gray-900 uppercase tracking-tighter">Personnel Pool</h2>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Available for Deployment</p>
                        </div>
                    </div>
                    
                    <div class="relative group w-full sm:w-72">
                         <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search available..." class="pl-10 pr-6 py-3 w-full border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-transparent bg-white shadow-sm transition-all duration-300 font-bold text-xs uppercase tracking-widest placeholder-gray-300">
                    </div>
                </div>

                <div class="flex gap-6 p-8 overflow-x-auto no-scrollbar min-h-[200px] bg-white">
                    @forelse ($unassignedRosters as $roster)
                        <div wire:key="roster-{{ $roster->id }}" draggable="true"
                             @dragstart="$wire.set('draggedRosterId', {{ $roster->id }}); event.target.classList.add('dragging')"
                             @dragend="event.target.classList.remove('dragging')"
                             x-data="{}"
                             @mousemove="
                                const rect = $el.getBoundingClientRect();
                                const x = event.clientX - rect.left;
                                const y = event.clientY - rect.top;
                                const { width, height } = rect;
                                const rotateX = ((y / height) - 0.5) * -20;
                                const rotateY = ((x / width) - 0.5) * 20;
                                $el.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.05)`;
                             "
                             @mouseleave="$el.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale(1)'"
                             class="card-3d roster-card flex-shrink-0 w-64 p-6 bg-white rounded-3xl shadow-lg border border-gray-100 cursor-grab hover:border-teal-500 hover:shadow-teal-500/10 transition-all duration-300 relative group">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-2xl bg-gray-50 flex items-center justify-center font-black text-gray-400 text-lg group-hover:bg-teal-50 group-hover:text-teal-600 transition-colors duration-500 shadow-inner uppercase">
                                    {{ strtoupper(substr($roster->user->name, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="font-black text-gray-800 text-sm truncate uppercase tracking-tight">{{ $roster->user->name }}</p>
                                    <div class="flex items-center gap-1.5 mt-1">
                                        <span class="w-1.5 h-1.5 rounded-full {{ str_contains($roster->shift->name, 'Malam') ? 'bg-indigo-500' : (str_contains($roster->shift->name, 'Pagi') ? 'bg-amber-500' : 'bg-sky-500') }}"></span>
                                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $roster->shift->name }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="w-full flex flex-col items-center justify-center py-10 opacity-20 grayscale">
                            <span class="text-5xl mb-3">👤</span>
                            <p class="text-[10px] font-black uppercase tracking-[0.3em]">No Personnel Idle</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- ======================================= -->
        <!-- POSTS GRID -->
        <!-- ======================================= -->
        <div class="space-y-8">
            <div class="flex items-center gap-4 mb-8">
                <div class="h-px flex-1 bg-gray-200"></div>
                <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.4em]">Operational Sectors</h2>
                <div class="h-px flex-1 bg-gray-200"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8">
                @foreach ($posts as $post)
                    <div wire:key="post-{{ $post->id }}"
                         class="drop-zone rounded-[2.5rem] p-1"
                         :class="{ 'drop-zone-active': $wire.draggedRosterId && dropTarget === 'post-{{ $post->id }}' }"
                         @dragover.prevent @dragenter.prevent="dropTarget = 'post-{{ $post->id }}'"
                         @dragleave.prevent="dropTarget = null"
                         @drop.prevent="$wire.assignPost({{ $post->id }}).then(() => { dropTarget = null })">

                        <div class="card-3d bg-white rounded-[2.5rem] shadow-xl border border-gray-100 h-full flex flex-col overflow-hidden transition-all duration-500 group/post hover:border-indigo-200 hover:shadow-indigo-500/10">
                            <div class="p-8 border-b border-gray-50 bg-gray-50/30 group-hover/post:bg-white transition-colors duration-500">
                                <div class="flex justify-between items-center mb-1">
                                    <h3 class="font-black text-xl text-gray-900 uppercase tracking-tighter">{{ $post->name }}</h3>
                                    <span class="text-[9px] font-black text-indigo-500 bg-indigo-50 px-2 py-0.5 rounded-md border border-indigo-100 uppercase tracking-widest shadow-sm">{{ $post->code }}</span>
                                </div>
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Active Post Assignment</p>
                            </div>
                            
                            <div class="p-6 space-y-3 flex-grow min-h-[200px] flex flex-col">
                                @php $assignedToPost = $assignedRosters->get($post->id, collect()); @endphp
                                @forelse ($assignedToPost as $roster)
                                    <div wire:key="roster-in-post-{{ $roster->id }}" draggable="true"
                                         @dragstart="$wire.set('draggedRosterId', {{ $roster->id }}); event.target.classList.add('dragging')"
                                         @dragend="event.target.classList.remove('dragging')"
                                         class="roster-card bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100/50 flex justify-between items-center cursor-grab hover:bg-white hover:shadow-md hover:border-indigo-300 transition-all duration-300 group/card">
                                        <div class="flex items-center gap-3">
                                            <div class="h-8 w-8 rounded-xl bg-white flex items-center justify-center font-black text-[10px] text-indigo-600 shadow-sm border border-indigo-50 uppercase">
                                                {{ strtoupper(substr($roster->user->name, 0, 2)) }}
                                            </div>
                                            <p class="font-black text-gray-800 text-[11px] uppercase tracking-tight">{{ $roster->user->name }}</p>
                                        </div>
                                        <button wire:click="removePost({{ $roster->id }})" class="h-6 w-6 rounded-lg bg-white/50 text-rose-400 hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center opacity-0 group-hover/card:opacity-100 active:scale-90 shadow-sm border border-rose-100/50">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                        </button>
                                    </div>
                                @empty
                                    <div class="flex-grow flex flex-col items-center justify-center py-10 opacity-20 grayscale">
                                        <div class="h-16 w-16 rounded-full border-4 border-dashed border-gray-300 flex items-center justify-center mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                                        </div>
                                        <p class="text-[9px] font-black uppercase tracking-[0.3em]">Drop Zone</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
