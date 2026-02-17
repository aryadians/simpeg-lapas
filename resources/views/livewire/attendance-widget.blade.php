<div 
    class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-5 md:p-8 relative overflow-hidden"
    x-data="attendanceHandler()"
>
    <!-- Header -->
    <div class="flex justify-between items-center pb-4 border-b border-gray-100 mb-6 text-left">
        <h3 class="text-sm md:text-lg font-black text-gray-900 uppercase tracking-tighter">Shift Presence</h3>
        <p class="text-[9px] md:text-[10px] text-indigo-600 font-black uppercase tracking-widest bg-indigo-50 px-2 py-1 rounded-lg border border-indigo-100">{{ $currentDateDisplay }}</p>
    </div>

    @if(!$todayRoster)
        <div class="text-center py-6">
             <div class="bg-gray-50 rounded-[2rem] p-10 border-2 border-dashed border-gray-200">
                <span class="text-5xl block mb-4 grayscale">🏖️</span>
                <p class="text-gray-400 font-black uppercase tracking-[0.2em] text-[10px]">No Schedule Found</p>
            </div>
        </div>
    @else
        <div class="space-y-6 text-left">
            <!-- Schedule Info -->
            <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 p-6 rounded-[2rem] shadow-lg relative overflow-hidden text-white">
                <p class="text-[9px] font-black text-indigo-200 uppercase tracking-[0.3em]">Duty Assignment</p>
                <h4 class="text-xl font-black uppercase mt-1">{{ $todayRoster->shift->name }}</h4>
                <div class="flex items-center gap-2 mt-2 text-[10px] font-bold text-indigo-100 uppercase">
                    <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    {{ \Carbon\Carbon::parse($todayRoster->shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($todayRoster->shift->end_time)->format('H:i') }}
                </div>
            </div>

            <div class="animate-fade-in-down">
                @if(!$attendance)
                    <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 p-4 rounded-2xl text-[10px] font-black uppercase tracking-widest flex items-center gap-3 shadow-sm mb-4">
                        <div class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span>GPS PERIMETER: AUTHORIZED</span>
                    </div>
                    <button @click="showSelfieModal = true" class="w-full py-5 bg-black text-white font-black rounded-2xl shadow-xl active:scale-95 flex items-center justify-center gap-3 text-sm uppercase tracking-[0.2em]">Commit Inbound</button>
                @elseif(!$attendance->clock_out)
                    <div class="bg-white border border-emerald-100 rounded-[2rem] p-6 shadow-inner text-center bg-gray-50/50 mb-4">
                        <p class="text-[9px] text-emerald-600 font-black uppercase tracking-[0.3em] mb-4">Inbound Verified</p>
                        <div class="flex justify-around items-center">
                            <div class="text-center">
                                <span class="text-gray-400 text-[9px] font-black uppercase tracking-widest">Entry</span>
                                <span class="font-black text-gray-900 font-mono text-xl block leading-none mt-1">{{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i') }}</span>
                            </div>
                            <div class="h-10 w-px bg-gray-200 mx-4"></div>
                            <div class="text-center">
                                <span class="text-gray-400 text-[9px] font-black uppercase tracking-widest">Status</span>
                                <span class="block font-black text-xs mt-1 {{ $attendance->status == 'terlambat' ? 'text-amber-600' : 'text-emerald-600' }} uppercase">{{ $attendance->status }}</span>
                            </div>
                        </div>
                    </div>
                    <button @click="$dispatch('confirm-dialog', { title: 'END SHIFT?', text: 'Selesaikan tugas?', confirm_event: 'clock-out-trigger' })" class="w-full py-5 bg-rose-600 text-white font-black rounded-2xl shadow-lg active:scale-95 flex items-center justify-center gap-3 text-sm uppercase tracking-[0.2em]">Commit Outbound</button>
                @else
                    <div class="bg-gray-900 rounded-[2.5rem] p-8 text-center shadow-xl text-white">
                        <span class="text-5xl block mb-4 animate__animated animate__bounceIn">🎖️</span>
                        <p class="font-black text-xl uppercase tracking-tighter leading-none">Shift Finalized</p>
                        <p class="text-[9px] font-black opacity-40 mt-2 font-mono uppercase tracking-[0.3em]">Status: Deactivated</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Modal Selfie --}}
    <template x-if="showSelfieModal">
        <div class="fixed inset-0 bg-black z-[100] md:bg-black/80 md:backdrop-blur-xl flex items-center justify-center animate__animated animate__fadeIn animate__faster">
            <div class="bg-black md:bg-white md:rounded-[3rem] w-full h-full md:h-auto md:max-w-md md:mx-auto relative flex flex-col overflow-hidden">
                <button @click="showSelfieModal = false; $wire.cancelClockIn()" class="absolute top-6 right-6 z-[110] h-12 w-12 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center text-white md:hidden font-bold">✕</button>
                <div class="p-8 pt-16 md:pt-8 text-center">
                    <h3 class="text-xl font-black text-white md:text-gray-900 uppercase tracking-tighter">Biometric Entry</h3>
                    <p class="text-[10px] font-bold text-gray-500 mt-1 uppercase tracking-widest">Verify identity within perimeter</p>
                </div>
                <div class="flex-1 flex flex-col justify-center p-4 md:p-8 md:bg-gray-50">
                    <div class="relative group mx-auto w-full max-w-sm aspect-square overflow-hidden rounded-[2rem] border-4 border-indigo-600 shadow-2xl">
                        <video x-ref="video" x-show="!isPhotoTaken" class="w-full h-full object-cover" autoplay muted playsinline></video>
                        <img x-ref="photo" x-show="isPhotoTaken" class="w-full h-full object-cover" />
                        <canvas x-ref="canvas" class="hidden"></canvas>
                        <div x-show="!isPhotoTaken" class="absolute inset-0 pointer-events-none overflow-hidden">
                            <div class="absolute inset-x-0 h-1 bg-indigo-500 shadow-[0_0_20px_#6366f1] animate-[scan_2s_infinite] z-20"></div>
                            <div class="absolute inset-0 border-[60px] border-black/40 rounded-full scale-125 z-10"></div>
                        </div>
                    </div>
                </div>
                <div class="p-8 space-y-4 bg-black md:bg-white">
                    <div class="flex justify-center gap-4">
                         <button x-show="!isPhotoTaken" @click="takePhoto()" class="w-full py-4 bg-white md:bg-black text-black md:text-white rounded-2xl font-black text-xs uppercase tracking-[0.3em] active:scale-95 transition-all">Capture Identity</button>
                         <button x-show="isPhotoTaken" @click="retakePhoto()" class="w-full py-4 bg-gray-800 text-white rounded-2xl font-black text-xs uppercase tracking-[0.3em] active:scale-95 transition-all">Retake</button>
                    </div>
                    <button x-show="isPhotoTaken" @click="$wire.confirmClockIn()" class="w-full py-5 bg-indigo-600 text-white font-black rounded-2xl shadow-xl active:scale-95 uppercase tracking-[0.2em] text-xs">Finalize Authorization</button>
                    <button @click="showSelfieModal = false; $wire.cancelClockIn()" class="w-full py-2 text-[10px] font-black text-gray-500 uppercase tracking-widest transition-colors">Dismiss Access</button>
                </div>
            </div>
        </div>
    </template>

    <style> @keyframes scan { 0% { top: 0%; } 50% { top: 100%; } 100% { top: 0%; } } </style>

    <!-- Footer -->
    <div class="pt-4 mt-6 border-t border-gray-100 flex justify-between items-center px-1">
         <p class="text-[8px] text-gray-300 font-black uppercase tracking-widest">Digital Perimeter Security</p>
         <p class="text-[8px] text-indigo-400 font-black font-mono tracking-tighter">{{ $currentTime }}</p>
    </div>
</div>

@push('scripts')
<script>
function attendanceHandler() {
    return {
        isWithinRadius: @entangle('isWithinRadius'),
        locationError: @entangle('locationError'),
        showSelfieModal: @entangle('showSelfieModal'),
        isPhotoTaken: false,
        stream: null,
        init() {
            // Check if user has NOT attended yet
            if (!@json((bool)$attendance)) {
                this.getLocation();
            }
            this.$watch('showSelfieModal', (val) => {
                if (val) { this.startCamera(); } else { this.stopCamera(); }
            });
        },
        getLocation() {
            if (!navigator.geolocation) { this.locationError = 'GPS NOT SUPPORTED'; return; }
            navigator.geolocation.getCurrentPosition(
                (p) => { this.$wire.setUserLocation(p.coords.latitude, p.coords.longitude); },
                (e) => { this.$wire.setUserLocation(0, 0); },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        },
        startCamera() {
            this.isPhotoTaken = false;
            this.$nextTick(() => {
                const v = this.$refs.video;
                if (!v) return;
                navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' }, audio: false })
                    .then(s => { this.stream = s; v.srcObject = s; })
                    .catch(e => { 
                        alert('Kamera tidak dapat diakses.');
                        this.showSelfieModal = false;
                    });
            });
        },
        stopCamera() {
            if (this.stream) { this.stream.getTracks().forEach(t => t.stop()); this.stream = null; }
        },
        takePhoto() {
            const v = this.$refs.video;
            const c = this.$refs.canvas;
            c.width = v.videoWidth; c.height = v.videoHeight;
            c.getContext('2d').drawImage(v, 0, 0);
            const data = c.toDataURL('image/jpeg');
            this.$refs.photo.src = data;
            this.isPhotoTaken = true;
            this.$wire.set('selfie', data);
        },
        retakePhoto() { this.isPhotoTaken = false; this.$wire.set('selfie', null); }
    }
}
</script>
@endpush
