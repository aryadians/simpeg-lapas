<div class="min-h-screen bg-gray-50 text-gray-800 font-sans" x-data="{
    dropTarget: null
}">
    <style>
        :root {
            --glow-color: rgb(124 58 237 / 0.5);
        }
        .card-3d, .header-3d {
            transition: transform 0.1s ease-out;
            will-change: transform;
            transform-style: preserve-3d;
        }
        .roster-card.dragging {
            opacity: 0.4;
            transform: scale(0.9) !important;
        }
        .drop-zone-active {
            outline: 2px dashed var(--glow-color);
            outline-offset: 4px;
            background-color: rgba(139, 92, 246, 0.05);
        }
        .horizontal-scroll {
            scrollbar-width: thin;
            scrollbar-color: theme('colors.gray.300') theme('colors.gray.100');
        }
    </style>

    <div class="p-4 sm:p-6 lg:p-8 max-w-screen-2xl mx-auto">
        <!-- ======================================= -->
        <!-- HEADER -->
        <!-- ======================================= -->
        <div x-data="{ rotateX: 0, rotateY: 0 }"
             @mousemove="
                const rect = $el.getBoundingClientRect();
                const x = event.clientX - rect.left;
                const y = event.clientY - rect.top;
                const { width, height } = rect;
                const rotateX = ((y / height) - 0.5) * -15;
                const rotateY = ((x / width) - 0.5) * 15;
                $el.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.03)`;
             "
             @mouseleave="$el.style.transform = 'perspective(1000px) rotateX(0) rotateY(0) scale(1)'"
             class="header-3d my-12" style="transition: transform 0.3s ease;">
            <h1 class="text-5xl font-bold py-2 text-transparent bg-clip-text bg-gradient-to-r from-violet-600 to-teal-500 text-center">
                Plotting Pos Jaga
            </h1>
            <p class="mt-2 text-gray-500 text-center">Hover over elements and drag and drop to assign posts.</p>
        </div>


        <!-- ======================================= -->
        <!-- FILTERS -->
        <!-- ======================================= -->
        <div class="mb-8 flex justify-center items-center space-x-4 p-3 bg-white rounded-xl border border-gray-200 shadow-sm">
            <div>
                <label for="selectedDate" class="sr-only">Tanggal</label>
                <input type="date" id="selectedDate" wire:model.live="selectedDate" class="bg-gray-100 border-gray-300 text-gray-700 rounded-lg p-2 focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
            </div>
            <div>
                <label for="selectedShift" class="sr-only">Shift</label>
                <select id="selectedShift" wire:model.live="selectedShift" class="bg-gray-100 border-gray-300 text-gray-700 rounded-lg p-2 focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
                    <option value="">Semua Shift</option>
                    @foreach ($shifts as $shift)
                        <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- ======================================= -->
        <!-- UNASSIGNED STAFF SHELF -->
        <!-- ======================================= -->
        <div class="mb-12 drop-zone rounded-lg"
             wire:key="unassigned-drop-zone"
             :class="{ 'drop-zone-active': $wire.draggedRosterId && dropTarget === 'unassigned' }"
             @dragover.prevent @dragenter.prevent="dropTarget = 'unassigned'"
             @dragleave.prevent="dropTarget = null"
             @drop.prevent="$wire.assignPost(null).then(() => { dropTarget = null })">

            <h2 class="text-2xl font-semibold mb-4 text-violet-800">Pegawai Tersedia</h2>
            
            <!-- Search Input -->
            <div class="relative mb-4 px-4">
                <span class="absolute inset-y-0 left-0 flex items-center pl-7">
                    <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
                    </svg>
                </span>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama pegawai..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-violet-500 focus:border-violet-500">
            </div>

            <div class="flex space-x-4 p-4 bg-gray-100 rounded-lg min-h-[160px] overflow-x-auto horizontal-scroll border border-gray-200">
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
                            const rotateX = ((y / height) - 0.5) * -25;
                            const rotateY = ((x / width) - 0.5) * 25;
                            $el.style.transform = `perspective(800px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.05, 1.05, 1.05)`;
                         "
                         @mouseleave="$el.style.transform = 'perspective(800px) rotateX(0) rotateY(0) scale3d(1, 1, 1)'"
                         class="card-3d roster-card flex-shrink-0 w-64 p-4 bg-white rounded-xl shadow-md hover:shadow-xl border border-gray-200 cursor-grab">
                        <p class="font-bold text-lg text-gray-800">{{ $roster->user->name }}</p>
                        <span class="text-sm text-teal-800 bg-teal-100 px-2 py-1 rounded-full">{{ $roster->shift->name }}</span>
                    </div>
                @empty
                    <div class="w-full flex items-center justify-center text-gray-500">
                        <p>Tidak ada pegawai yang tersedia.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- ======================================= -->
        <!-- POSTS GRID -->
        <!-- ======================================= -->
        <div>
            <h2 class="text-2xl font-semibold mb-4 text-violet-800">Pos Penjagaan</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($posts as $post)
                    <div wire:key="post-{{ $post->id }}"
                         class="drop-zone rounded-xl"
                         :class="{ 'drop-zone-active': $wire.draggedRosterId && dropTarget === 'post-{{ $post->id }}' }"
                         @dragover.prevent @dragenter.prevent="dropTarget = 'post-{{ $post->id }}'"
                         @dragleave.prevent="dropTarget = null"
                         @drop.prevent="$wire.assignPost({{ $post->id }}).then(() => { dropTarget = null })"
                         x-data="{}"
                         @mousemove="
                            const el = $el.querySelector('.card-3d');
                            const rect = el.getBoundingClientRect();
                            const x = event.clientX - rect.left;
                            const y = event.clientY - rect.top;
                            const { width, height } = rect;
                            const rotateX = ((y / height) - 0.5) * -10;
                            const rotateY = ((x / width) - 0.5) * 10;
                            el.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
                         "
                         @mouseleave="$el.querySelector('.card-3d').style.transform = 'perspective(1000px) rotateX(0) rotateY(0)'">

                        <div class="card-3d bg-white rounded-xl shadow-lg hover:shadow-2xl border border-gray-200 h-full flex flex-col">
                            <div class="p-4 border-b border-gray-200">
                                <h3 class="font-bold text-xl text-gray-900">{{ $post->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $post->code }}</p>
                            </div>
                            <div class="p-4 space-y-3 flex-grow min-h-[150px]">
                                @php $assignedToPost = $assignedRosters->get($post->id, collect()); @endphp
                                @forelse ($assignedToPost as $roster)
                                    <div wire:key="roster-in-post-{{ $roster->id }}" draggable="true"
                                         @dragstart="$wire.set('draggedRosterId', {{ $roster->id }}); event.target.classList.add('dragging')"
                                         @dragend="event.target.classList.remove('dragging')"
                                         class="roster-card bg-violet-50 p-3 rounded-lg border border-violet-200 flex justify-between items-center cursor-grab">
                                        <p class="font-semibold text-violet-800">{{ $roster->user->name }}</p>
                                        <button wire:click="removePost({{ $roster->id }})" class="text-red-500 hover:text-red-400 font-bold text-lg">&times;</button>
                                    </div>
                                @empty
                                    <div class="flex items-center justify-center h-full text-gray-400">
                                        <p>Drop pegawai disini</p>
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
