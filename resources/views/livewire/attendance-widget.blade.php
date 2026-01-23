<div 
    class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6 relative overflow-hidden"
    x-data="attendanceWidget()"
    x-init="init"
>
    <!-- Header -->
    <div class="flex justify-between items-center pb-4 border-b border-gray-100">
        <h3 class="text-lg font-bold text-gray-800">Presensi Kehadiran</h3>
        <p class="text-sm text-gray-500 font-mono">{{ $currentDateDisplay ?? \Carbon\Carbon::now()->translatedFormat('d M Y') }}</p>
    </div>

    @if(!$todayRoster)
        <!-- State: Off Duty -->
        <div class="pt-6 text-center">
             <div class="bg-gray-50 rounded-xl p-8 border-2 border-dashed border-gray-200 animate-fade-in-down">
                <span class="text-5xl block mb-3">🏖️</span>
                <p class="text-gray-700 font-bold text-lg">Anda Libur</p>
                <p class="text-sm text-gray-500 mt-1">Tidak ada jadwal dinas hari ini.</p>
            </div>
        </div>
    @else
        <!-- State: Has Roster -->
        <div class="pt-5">
            <!-- Schedule Info -->
            <div class="mb-5 bg-indigo-50 p-4 rounded-xl border border-indigo-100">
                <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-wider">Jadwal Anda Hari Ini</p>
                <div class="flex items-center gap-3 mt-2">
                    <div class="h-10 w-10 rounded-full bg-indigo-200 flex items-center justify-center text-indigo-700 font-bold text-lg shrink-0">
                        {{ substr($todayRoster->shift->name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-base font-extrabold text-indigo-900 leading-tight">
                            {{ $todayRoster->shift->name }}
                        </p>
                        <p class="text-xs text-indigo-600 font-medium">
                            🕒 {{ \Carbon\Carbon::parse($todayRoster->shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($todayRoster->shift->end_time)->format('H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Attendance Actions & Status -->
            <div class="animate-fade-in-down">
                @if(!$attendance)
                    <!-- Geolocation Status -->
                    <div wire:ignore class="mb-4">
                        <div x-show="!locationError && !isWithinRadius" class="bg-yellow-50 border border-yellow-200 text-yellow-700 p-3 rounded-lg text-sm flex items-center gap-2">
                            <svg class="h-5 w-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>Memverifikasi lokasi Anda...</span>
                        </div>
                         <div x-show="locationError" x-text="locationError" class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-lg text-sm"></div>
                         <div x-show="isWithinRadius" class="bg-green-50 border border-green-200 text-green-700 p-3 rounded-lg text-sm flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                            <span>Lokasi terverifikasi. Anda berada di dalam area yang diizinkan.</span>
                        </div>
                    </div>

                    <!-- Action: Clock In -->
                    <button 
                        wire:click="clockIn" 
                        wire:loading.attr="disabled" 
                        x-bind:disabled="!isWithinRadius"
                        class="w-full py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transform transition hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3 text-lg disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none disabled:transform-none"
                    >
                        <svg wire:loading.remove xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                        <span wire:loading.remove>Absen Masuk</span>
                        <span wire:loading>Memproses...</span>
                    </button>
                @elseif(!$attendance->clock_out)
                    <!-- Status: Clocked In, waiting for Clock Out -->
                    <div class="bg-white border-2 border-emerald-500 rounded-xl p-4 shadow-sm mb-4">
                        <p class="text-xs text-emerald-700 mb-2 font-bold uppercase tracking-wide text-center">Telah Absen Masuk</p>
                        <div class="flex justify-between items-center text-center">
                            <div class="w-1/2">
                                <span class="text-gray-500 text-xs">Jam Masuk</span>
                                <span class="font-bold text-gray-800 font-mono text-xl block">{{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}</span>
                            </div>
                            <div class="w-1/2">
                                <span class="text-gray-500 text-xs">Status</span>
                                <span class="block font-bold text-sm mt-1 {{ $attendance->status == 'terlambat' ? 'text-red-600' : 'text-emerald-600' }}">
                                    @if($attendance->status == 'terlambat') ⚠️ TERLAMBAT @else ✅ TEPAT WAKTU @endif
                                </span>
                            </div>
                        </div>
                    </div>
                     <button wire:click="clockOut" wire:confirm="Yakin ingin mengakhiri jam dinas sekarang?" class="w-full py-4 bg-rose-500 hover:bg-rose-600 text-white font-bold rounded-xl shadow-lg shadow-rose-500/30 transform transition hover:-translate-y-1 active:scale-95 flex items-center justify-center gap-3 text-lg">
                        <svg wire:loading.remove xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                        <span>Absen Pulang</span>
                    </button>
                @else
                    <!-- State: Finished -->
                    <div class="bg-emerald-50 rounded-xl p-6 text-center border-2 border-emerald-200">
                        <span class="text-4xl block mb-2">👋</span>
                        <p class="text-emerald-800 font-bold text-lg">Tugas Selesai!</p>
                        <div class="text-sm text-emerald-700 mt-2 font-mono">
                            Masuk: {{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }} | Pulang: {{ \Carbon\Carbon::parse($attendance->clock_out)->format('H:i') }}
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
        class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm flex items-center justify-center p-4 z-50">
        <div 
            class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-auto"
            @click.away="$wire.cancelClockIn()"
        >
            <div class="p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">Ambil Selfie</h3>
                <p class="text-sm text-gray-500 mt-1">Posisikan wajah Anda di dalam area yang tersedia.</p>
            </div>
            <div class="p-6">
                <div class="mb-4">
                    <div class="w-full bg-gray-200 rounded-lg overflow-hidden aspect-square">
                        <video x-ref="video" class="w-full h-full object-cover" autoplay muted playsinline></video>
                        <canvas x-ref="canvas" class="hidden"></canvas>
                        <img x-ref="photo" class="hidden w-full h-full object-cover" />
                    </div>
                    <div class="text-center mt-2">
                         <button x-show="!isPhotoTaken" @click="takePhoto()" class="text-sm text-indigo-600 hover:text-indigo-800 font-bold">Ambil Foto</button>
                         <button x-show="isPhotoTaken" @click="retakePhoto()" class="text-sm text-red-600 hover:text-red-800 font-bold">Ulangi</button>
                    </div>
                </div>

                @error('selfie') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="p-4 bg-gray-50 rounded-b-2xl flex justify-end items-center gap-3">
                <button 
                    type="button" 
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50"
                    wire:click="cancelClockIn"
                >
                    Batal
                </button>
                <button 
                    type="button" 
                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700 disabled:opacity-50"
                    wire:click="confirmClockIn"
                    x-bind:disabled="!isPhotoTaken"
                    wire:loading.attr="disabled"
                    wire:target="confirmClockIn"
                >
                    <span wire:loading.remove wire:target="confirmClockIn">Konfirmasi Absen</span>
                    <span wire:loading wire:target="confirmClockIn">Menyimpan...</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Footer with Server Time -->
    <div class="pt-4 mt-4 border-t border-gray-100 text-right">
         <p class="text-[10px] text-gray-400 font-mono">
            Server Time: {{ $currentTime ?? \Carbon\Carbon::now()->format('H:i:s') }}
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
                console.log('Attendance widget initializing...');

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
                    this.locationError = 'Browser Anda tidak mendukung Geolocation.';
                    return;
                }

                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        this.$wire.setUserLocation(position.coords.latitude, position.coords.longitude);
                    },
                    (error) => {
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                this.locationError = "Anda harus mengizinkan akses lokasi untuk absen.";
                                break;
                            case error.POSITION_UNAVAILABLE:
                                this.locationError = "Informasi lokasi tidak tersedia.";
                                break;
                            case error.TIMEOUT:
                                this.locationError = "Gagal mendapatkan lokasi (timeout).";
                                break;
                            default:
                                this.locationError = "Terjadi kesalahan saat mengambil lokasi.";
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
                this.$refs.photo.classList.add('hidden');
                this.$refs.video.classList.remove('hidden');

                navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false })
                    .then(stream => {
                        this.stream = stream;
                        this.$refs.video.srcObject = stream;
                    })
                    .catch(err => {
                        alert('Could not access camera: ' + err.message);
                    });
            },

            stopCamera() {
                if (this.stream) {
                    this.stream.getTracks().forEach(track => track.stop());
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
                this.$refs.video.classList.add('hidden');
                this.$refs.photo.classList.remove('hidden');

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