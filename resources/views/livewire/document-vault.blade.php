<div class="min-h-screen bg-gray-50 p-6 lg:p-8 font-sans">
    <div class="max-w-6xl mx-auto">
        
        {{-- HEADER --}}
        <header class="mb-10 animate__animated animate__fadeInDown relative">
            <div class="flex justify-between items-center gap-6 p-8 bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden relative">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-indigo-50 rounded-full blur-3xl opacity-60"></div>
                <div class="relative z-10">
                    <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase">Digital <span class="text-indigo-600">Vault</span></h1>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-[0.3em] mt-1">Arsip Dokumen Kepegawaian</p>
                </div>
                <button wire:click="openModal" class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-black rounded-2xl shadow-lg shadow-indigo-500/30 transform transition-all active:scale-95 flex items-center gap-2 uppercase tracking-widest text-xs relative z-10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM6.293 6.707a1 1 0 010-1.414l3-3a1 1 0 011.414 0l3 3a1 1 0 01-1.414 1.414L11 5.414V13a1 1 0 11-2 0V5.414L7.707 6.707a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                    <span>Upload File</span>
                </button>
            </div>
        </header>

        {{-- DOCUMENTS GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-6 animate__animated animate__fadeInUp">
            @forelse($documents as $doc)
            <div wire:key="{{ $doc->id }}" class="bg-white rounded-[2rem] p-6 shadow-lg border border-gray-100 hover:shadow-2xl hover:border-indigo-200 transition-all duration-500 group relative">
                <div class="h-16 w-16 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-2xl shadow-inner mb-4">
                    @if(str_contains($doc->mime_type, 'pdf')) PDF 
                    @elseif(str_contains($doc->mime_type, 'image')) IMG
                    @else DOC @endif
                </div>
                
                <h3 class="font-black text-gray-900 text-lg uppercase tracking-tight truncate">{{ $doc->title }}</h3>
                <span class="inline-block mt-1 px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-widest bg-gray-100 text-gray-500">{{ $doc->category }}</span>
                
                <div class="mt-6 pt-4 border-t border-gray-50 flex justify-between items-center">
                    <span class="text-[9px] font-bold text-gray-300 uppercase">{{ $doc->created_at->format('d M Y') }}</span>
                    <div class="flex gap-2">
                        <button wire:click="download({{ $doc->id }})" class="p-2 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white transition-all"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg></button>
                        <button wire:click="delete({{ $doc->id }})" class="p-2 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white transition-all"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-24 text-center">
                <div class="flex flex-col items-center justify-center opacity-30 grayscale">
                    <span class="text-6xl mb-4">🗄️</span>
                    <h3 class="text-xl font-black text-gray-900 uppercase tracking-widest">Vault Empty</h3>
                    <p class="mt-2 text-xs font-bold uppercase tracking-[0.2em]">Securely store your credentials here.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    {{-- UPLOAD MODAL --}}
    @if($isModalOpen)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4">
        <div wire:click="closeModal" class="absolute inset-0 bg-black/60 backdrop-blur-sm animate__animated animate__fadeIn animate__faster"></div>
        <div class="bg-white rounded-[2.5rem] shadow-2xl w-full max-w-lg relative animate__animated animate__zoomIn animate__faster overflow-hidden">
            <form wire:submit="save">
                <div class="p-8 border-b border-gray-50 bg-gray-50/50">
                    <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tighter text-center">Secure <span class="text-indigo-600">Upload</span></h2>
                </div>
                <div class="p-8 space-y-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Document Title</label>
                        <input wire:model="title" type="text" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-bold" placeholder="e.g. SK Kenaikan Pangkat">
                        @error('title') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest px-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">Category Classification</label>
                        <select wire:model="category" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition font-bold text-xs uppercase tracking-widest">
                            <option value="">Select Category</option>
                            <option value="Personal Identity">Personal Identity</option>
                            <option value="Academic Record">Academic Record</option>
                            <option value="Employment History">Employment History</option>
                            <option value="Certification">Certification</option>
                        </select>
                        @error('category') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest px-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest ml-1">File Attachment</label>
                        <input wire:model="file" type="file" class="w-full bg-gray-50 border-gray-200 text-gray-900 rounded-2xl p-4 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition font-bold text-xs">
                        @error('file') <span class="text-rose-500 text-[10px] font-bold uppercase tracking-widest px-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="p-8 bg-gray-50/50 border-t border-gray-50 flex items-center gap-3">
                    <button type="button" wire:click="closeModal" class="flex-1 py-4 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-gray-600 transition-colors">Dismiss</button>
                    <button type="submit" class="flex-[2] py-4 text-white bg-indigo-600 hover:bg-indigo-700 rounded-2xl font-black shadow-lg shadow-indigo-500/30 transform transition active:scale-95 uppercase tracking-[0.2em] text-xs">
                        <span wire:loading.remove wire:target="save">Encrypt & Store</span>
                        <span wire:loading wire:target="save">Uploading...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
