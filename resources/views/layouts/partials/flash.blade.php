<div x-data="toastManager()" 
     @add-toast.window="addToast($event.detail)"
     class="fixed top-5 right-5 z-[9999] flex flex-col gap-3 w-full max-w-sm pointer-events-none">
    
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.visible"
             x-transition:enter="transform ease-out duration-300 transition"
             x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-full"
             x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="pointer-events-auto w-full bg-white shadow-lg rounded-xl overflow-hidden ring-1 ring-black ring-opacity-5 relative"
             @mouseenter="pauseToast(toast.id)"
             @mouseleave="resumeToast(toast.id)">
            
            <div class="p-4 flex items-start">
                <div class="flex-shrink-0">
                    {{-- Success Icon --}}
                    <svg x-show="toast.type === 'success'" class="h-6 w-6 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{-- Error Icon --}}
                    <svg x-show="toast.type === 'error'" class="h-6 w-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{-- Warning Icon --}}
                    <svg x-show="toast.type === 'warning'" class="h-6 w-6 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    {{-- Info Icon --}}
                    <svg x-show="toast.type === 'info'" class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3 w-0 flex-1 pt-0.5">
                    <p class="text-sm font-medium text-gray-900" x-text="toast.title"></p>
                    <p class="mt-1 text-sm text-gray-500" x-text="toast.message"></p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button @click="removeToast(toast.id)" class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <span class="sr-only">Tutup</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>
            
            {{-- Progress bar --}}
            <div class="h-1 bg-gray-100 w-full relative overflow-hidden">
                <div class="absolute top-0 left-0 h-full transition-all duration-100 ease-linear"
                     :class="{
                         'bg-green-400': toast.type === 'success',
                         'bg-red-400': toast.type === 'error',
                         'bg-yellow-400': toast.type === 'warning',
                         'bg-blue-400': toast.type === 'info'
                     }"
                     :style="`width: ${toast.progress}%`"></div>
            </div>
        </div>
    </template>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('toastManager', () => ({
        toasts: [],
        addToast(toast) {
            toast.id = Date.now() + Math.random().toString(36).substr(2, 9);
            toast.visible = true;
            toast.progress = 100;
            toast.duration = toast.duration || 5000;
            toast.remaining = toast.duration;
            toast.interval = null;
            toast.lastTick = Date.now();
            
            if (!toast.title) {
                if (toast.type === 'success') toast.title = 'Berhasil';
                if (toast.type === 'error') toast.title = 'Terjadi Kesalahan';
                if (toast.type === 'warning') toast.title = 'Peringatan';
                if (toast.type === 'info') toast.title = 'Informasi';
            }

            this.toasts.push(toast);
            this.startTimer(toast);
        },
        startTimer(toast) {
            toast.lastTick = Date.now();
            toast.interval = setInterval(() => {
                const now = Date.now();
                const delta = now - toast.lastTick;
                toast.lastTick = now;
                toast.remaining -= delta;
                toast.progress = (toast.remaining / toast.duration) * 100;

                if (toast.remaining <= 0) {
                    this.removeToast(toast.id);
                }
            }, 50);
        },
        pauseToast(id) {
            const toast = this.toasts.find(t => t.id === id);
            if (toast && toast.interval) {
                clearInterval(toast.interval);
                toast.interval = null;
            }
        },
        resumeToast(id) {
            const toast = this.toasts.find(t => t.id === id);
            if (toast) {
                this.startTimer(toast);
            }
        },
        removeToast(id) {
            const toast = this.toasts.find(t => t.id === id);
            if (toast) {
                clearInterval(toast.interval);
                toast.visible = false;
                setTimeout(() => {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }, 300);
            }
        },
        init() {
            @if(session('success'))
                this.addToast({ type: 'success', message: {!! json_encode(session('success')) !!} });
            @endif
            @if(session('error'))
                this.addToast({ type: 'error', message: {!! json_encode(session('error')) !!} });
            @endif
            @if(session('warning'))
                this.addToast({ type: 'warning', message: {!! json_encode(session('warning')) !!} });
            @endif
            @if(session('info'))
                this.addToast({ type: 'info', message: {!! json_encode(session('info')) !!} });
            @endif
            @if(session('status'))
                this.addToast({ type: 'info', message: {!! json_encode(session('status')) !!} });
            @endif
        }
    }));
});
</script>
