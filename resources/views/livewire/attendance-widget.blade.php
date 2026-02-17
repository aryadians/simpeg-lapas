<div 
    class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 relative overflow-hidden"
    x-data="attendanceWidget()"
    x-init="init"
>
    <!-- Header -->
    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
        <h3 class="text-lg font-black text-gray-800 tracking-tight uppercase">Presensi</h3>
        <p class="text-[10px] text-indigo-500 font-black uppercase tracking-widest bg-indigo-50 px-2 py-1 rounded-md border border-indigo-100">{{ $currentDateDisplay ?? \Carbon\Carbon::now()->translatedFormat('d M Y') }}</p>
    </div>

    @if(!$todayRoster)
        <!-- State: Off Duty -->
        <div class="pt-6 text-center">
             <div class="bg-gray-50 rounded-2xl p-8 border-2 border-dashed border-gray-200 animate-fade-in-down">
                <span class="text-5xl block mb-3 grayscale">🏖️</span>
                <p class="text-gray-400 font-black uppercase tracking-widest text-xs">Status: OFF</p>
                <p class="text-sm text-gray-500 mt-1 font-medium">Tidak ada jadwal dinas.</p>
            </div>
        </div>
    @else
        <!-- State: Has Roster -->
        <div class="pt-5">
            <!-- Schedule Info -->
            <div class="mb-5 bg-gradient-to-br from-indigo-50 to-blue-50 p-4 rounded-xl border border-indigo-100 shadow-sm relative overflow-hidden text-left">
                <div class="absolute -right-4 -top-4 text-indigo-100 opacity-20 transform rotate-12">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest relative z-10">Jadwal Dinas</p>
                <div class="flex items-center gap-3 mt-2 relative z-10">
                    <div class="h-12 w-12 rounded-2xl bg-white shadow-sm flex items-center justify-center text-indigo-600 font-black text-xl shrink-0 border border-indigo-100">
                        {{ substr($todayRoster->shift->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-lg font-black text-indigo-900 leading-tight">
                            {{ $todayRoster->shift->name }}
                        </p>
                        <p class="text-xs text-indigo-600 font-bold mt-0.5 flex items-center gap-1 text-left">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" /></svg>
                            {{ \Carbon\Carbon::parse($todayRoster->shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($todayRoster->shift->end_time)->format('H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Attendance Actions & Status -->
            <div class="animate-fade-in-down">
                @if(!$attendance)
                    <!-- Geolocation Status -->
                    <div wire:ignore class="mb-4">
                        <div x-show="!locationError && !isWithinRadius" class="bg-amber-50 border border-amber-200 text-amber-700 p-3 rounded-xl text-[10px] font-black uppercase tracking-widest flex items-center gap-3">
                            <div class="flex h-2 w-2 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                            </div>
                            <span>GPS: Memverifikasi...</span>
                        </div>
                         <div x-show="locationError" x-text="locationError" class="bg-rose-50 border border-rose-200 text-rose-700 p-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-sm"></div>
                         <div x-show="isWithinRadius" class="bg-emerald-50 border border-emerald-200 text-emerald-700 p-3 rounded-xl text-[10px] font-black uppercase tracking-widest flex items-center gap-2 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                            <span>GPS: Lock</span>
                        </div>
                    </div>

                    <!-- Action: Clock In -->
                    <button 
                        wire:click="clockIn" 
                        wire:loading.attr="disabled" 
                        x-bind:disabled="!isWithinRadius"
                        class="w-full py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black rounded-2xl shadow-lg shadow-indigo-500/30 transform transition hover:-translate-y-1 hover:shadow-indigo-500/50 active:scale-95 flex items-center justify-center gap-3 text-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none disabled:transform-none"
                    >
                        <svg wire:loading.remove xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                        <span wire:loading.remove class="uppercase tracking-widest">Absen Masuk</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                @elseif(!$attendance->clock_out)
                    <!-- Status: Clocked In, waiting for Clock Out -->
                    <div class="bg-white border border-emerald-100 rounded-2xl p-5 shadow-sm mb-4 bg-gradient-to-br from-white to-emerald-50/30 text-center">
                        <p class="text-[10px] text-emerald-600 mb-3 font-black uppercase tracking-widest text-center">Status: Aktif</p>
                        <div class="flex justify-between items-center text-center">
                            <div class="flex-1 text-center">
                                <span class="text-gray-400 text-[10px] font-black uppercase tracking-widest">In</span>
                                <span class="font-black text-gray-800 font-mono text-2xl block leading-none mt-1">{{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}</span>
                            </div>
                            <div class="h-8 w-px bg-emerald-100 mx-2"></div>
                            <div class="flex-1 text-center">
                                <span class="text-gray-400 text-[10px] font-black uppercase tracking-widest">Mood</span>
                                <span class="block font-black text-xs mt-1 {{ $attendance->status == 'terlambat' ? 'text-amber-600' : 'text-emerald-600' }}">
                                    @if($attendance->status == 'terlambat') AMBER @else ON TIME @endif
                                </span>
                            </div>
                        </div>
                    </div>
                     <button wire:click="clockOut" wire:confirm="Yakin ingin mengakhiri jam dinas sekarang?" class="w-full py-4 bg-rose-500 hover:bg-rose-600 text-white font-black rounded-2xl shadow-lg shadow-rose-500/30 transform transition hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3 text-lg">
                        <svg wire:loading.remove xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        <span class="uppercase tracking-widest">Absen Pulang</span>
                    </button>
                @else
                    <!-- State: Finished -->
                    <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-6 text-center shadow-lg shadow-emerald-500/20 text-white">
                        <span class="text-5xl block mb-3 animate__animated animate__bounceIn">🎖️</span>
                        <p class="font-black text-xl uppercase tracking-tight">Tugas Selesai!</p>
                        <div class="text-[10px] font-black opacity-90 mt-2 font-mono uppercase tracking-[0.2em]">
                            SHIFT COMPLETE
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    <!-- Selfie Modal -->
    @if($showSelfieModal)
    <div x-data="{ show: @entangle('showSelfieModal') }" x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm flex items-center justify-center p-4 z-[60]">
        <div 
            class="bg-white rounded-3xl shadow-2xl w-full max-w-md mx-auto relative overflow-hidden"
            @click.away="$wire.cancelClockIn()"
        >
            <div class="p-6 border-b border-gray-50 text-center">
                <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight">Verifikasi Wajah</h3>
                <p class="text-xs font-bold text-gray-400 mt-1 uppercase tracking-widest">Ambil selfie sebagai bukti kehadiran.</p>
            </div>
            <div class="p-6 bg-gray-50">
                <div class="relative group">
                    <div class="w-full bg-black rounded-2xl overflow-hidden aspect-square shadow-inner border-4 border-white relative">
                        <video x-ref="video" class="w-full h-full object-cover" autoplay muted playsinline></video>
                        <canvas x-ref="canvas" class="hidden"></canvas>
                        <img x-ref="photo" class="hidden w-full h-full object-cover" />
                        
                        <!-- AI Scan Overlay -->
                        <div x-show="!isPhotoTaken" class="absolute inset-0 pointer-events-none overflow-hidden">
                            {{-- Scanning Line --}}
                            <div class="absolute inset-x-0 h-1 bg-indigo-500 shadow-[0_0_15px_rgba(79,70,229,0.8)] animate-[scan_3s_infinite] z-20"></div>
                            
                            {{-- Face Bounds --}}
                            <div class="absolute inset-0 border-[40px] border-black/40 rounded-full scale-110 z-10"></div>
                            
                            {{-- Corners --}}
                            <div class="absolute top-10 left-10 w-8 h-8 border-t-4 border-l-4 border-indigo-500 rounded-tl-lg"></div>
                            <div class="absolute top-10 right-10 w-8 h-8 border-t-4 border-r-4 border-indigo-500 rounded-tr-lg"></div>
                            <div class="absolute bottom-10 left-10 w-8 h-8 border-b-4 border-l-4 border-indigo-500 rounded-bl-lg"></div>
                            <div class="absolute bottom-10 right-10 w-8 h-8 border-b-4 border-r-4 border-indigo-500 rounded-br-lg"></div>
                            
                            <div class="absolute top-4 left-1/2 -translate-x-1/2 px-3 py-1 bg-black/60 backdrop-blur-md rounded-full border border-white/10 flex items-center gap-2">
                                <div class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                                <span class="text-[8px] font-black text-white uppercase tracking-[0.2em]">AI: Detecting Face</span>
                            </div>
                        </div>
                    </div>

                    <style>
                        @keyframes scan {
                            0% { top: 0%; opacity: 0; }
                            10% { opacity: 1; }
                            90% { opacity: 1; }
                            100% { top: 100%; opacity: 0; }
                        }
                    </style>
                    <div class="absolute bottom-4 left-0 right-0 flex justify-center gap-3">
                         <button x-show="!isPhotoTaken" @click="takePhoto()" class="px-6 py-2.5 bg-white/90 backdrop-blur-sm text-indigo-600 rounded-full text-xs font-black shadow-lg hover:bg-white transition uppercase tracking-widest">Capture</button>
                         <button x-show="isPhotoTaken" @click="retakePhoto()" class="px-6 py-2.5 bg-rose-500/90 backdrop-blur-sm text-white rounded-full text-xs font-black shadow-lg hover:bg-rose-600 transition uppercase tracking-widest">Retake</button>
                    </div>
                </div>

                @error('selfie') <span class="text-rose-500 text-[10px] font-bold uppercase mt-4 block text-center tracking-widest">{{ $message }}</span> @enderror
            </div>
            <div class="p-6 flex flex-col gap-3">
                <button 
                    type="button" 
                    class="w-full py-3.5 text-sm font-black text-white bg-indigo-600 rounded-2xl hover:bg-indigo-700 shadow-lg shadow-indigo-500/30 transition-all active:scale-95 disabled:opacity-50 uppercase tracking-widest"
                    wire:click="confirmClockIn"
                    x-bind:disabled="!isPhotoTaken"
                    wire:loading.attr="disabled"
                    wire:target="confirmClockIn"
                >
                    <span wire:loading.remove wire:target="confirmClockIn">Konfirmasi Absen</span>
                    <span wire:loading wire:target="confirmClockIn">Mendaftarkan...</span>
                </button>
                <button 
                    type="button" 
                    class="w-full py-2 text-xs font-black text-gray-400 hover:text-gray-600 transition uppercase tracking-widest"
                    wire:click="cancelClockIn"
                >
                    Batal
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Footer with Server Time -->
    <div class="pt-4 mt-4 border-t border-gray-100 flex justify-between items-center px-1">
         <p class="text-[9px] text-gray-300 font-black uppercase tracking-widest">
            SIMPEG SECURITY
        </p>
         <p class="text-[9px] text-indigo-400 font-black font-mono">
            {{ $currentTime ?? \Carbon\Carbon::now()->format('H:i:s') }}
        </p>
    </div>
</div>

@push('scripts')
<script>
    function attendanceWidget() {
        return {
            isWithinRadius: @entangle('isWithinRadius'),
            locationError: @entangle('locationError'),
            showSelfieModal: @entangle('showSelfieModal'),
            stream: null,
            isPhotoTaken: false,

            init() {
                // Only get location if there is no attendance record yet
                if (!@json($attendance)) {
                    this.getLocation();
                }

                this.$watch('showSelfieModal', (value) => {
                    if (value) {
                        this.startCamera();
                    } else {
                        this.stopCamera();
                    }
                });
            },

            getLocation() {
                if (!navigator.geolocation) {
                    this.locationError = 'GPS NOT SUPPORTED';
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        this.$wire.setUserLocation(position.coords.latitude, position.coords.longitude);
                    },
                    (error) => {
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                this.locationError = "GPS DENIED";
                                break;
                            default:
                                this.locationError = "GPS ERROR";
                                break;
                        }
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            },
            
            startCamera() {
                this.isPhotoTaken = false;
                this.$nextTick(() => {
                    if (this.$refs.photo) this.$refs.photo.classList.add('hidden');
                    if (this.$refs.video) this.$refs.video.classList.remove('hidden');

                    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false })
                        .then(stream => {
                            this.stream = stream;
                            if (this.$refs.video) this.$refs.video.srcObject = stream;
                        })
                        .catch(err => {
                            console.error('Camera access error:', err);
                        });
                });
            },

            stopCamera() {
                if (this.stream) {
                    this.stream.getTracks().forEach(track => track.stop());
                    this.stream = null;
                }
            },
            
            takePhoto() {
                const video = this.$refs.video;
                const canvas = this.$refs.canvas;
                const photo = this.$refs.photo;

                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                canvas.getContext('2d').drawImage(video, 0, 0);

                const dataUrl = canvas.toDataURL('image/jpeg');
                photo.src = dataUrl;
                
                this.isPhotoTaken = true;
                video.classList.add('hidden');
                photo.classList.remove('hidden');

                this.$wire.set('selfie', dataUrl);
            },

            retakePhoto() {
                this.isPhotoTaken = false;
                this.$refs.photo.classList.add('hidden');
                this.$refs.video.classList.remove('hidden');
                this.$wire.set('selfie', null);
            },

            destroy() {
                this.stopCamera();
            }
        }
    }
</script>
@endpush
