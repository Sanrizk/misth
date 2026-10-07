<div x-data="confirmDialog()" 
     @confirm.window="show($event.detail)"
     x-cloak
     x-show="isOpen"
     class="relative z-[9999]" 
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true">
  
  <div x-show="isOpen"
       x-transition:enter="ease-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0"
       class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity"></div>

  <div class="fixed inset-0 z-10 overflow-y-auto">
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
      <div x-show="isOpen"
           @click.away="cancel()"
           x-transition:enter="ease-out duration-300"
           x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
           x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
           x-transition:leave="ease-in duration-200"
           x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
           x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
           class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
        
        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-10 sm:w-10"
                 :class="{
                    'bg-red-100': type === 'danger',
                    'bg-yellow-100': type === 'warning',
                    'bg-blue-100': type === 'info'
                 }">
              {{-- Danger Icon --}}
              <svg x-show="type === 'danger'" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
              {{-- Warning Icon --}}
              <svg x-show="type === 'warning'" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
              {{-- Info Icon --}}
              <svg x-show="type === 'info'" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
              </svg>
            </div>
            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
              <h3 class="text-base font-semibold leading-6 text-gray-900" id="modal-title" x-text="title"></h3>
              <div class="mt-2">
                <p class="text-sm text-gray-500" x-text="message"></p>
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
          <button type="button" 
                  @click="confirm()" 
                  class="inline-flex w-full justify-center rounded-md px-3 py-2 text-sm font-semibold text-white shadow-sm sm:ml-3 sm:w-auto"
                  :class="{
                     'bg-red-600 hover:bg-red-500': type === 'danger',
                     'bg-yellow-600 hover:bg-yellow-500': type === 'warning',
                     'bg-blue-600 hover:bg-blue-500': type === 'info'
                  }"
                  x-text="confirmText"></button>
          <button type="button" 
                  @click="cancel()" 
                  class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                  x-text="cancelText"></button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('confirmDialog', () => ({
        isOpen: false,
        title: '',
        message: '',
        confirmText: 'Konfirmasi',
        cancelText: 'Batal',
        type: 'danger',
        onConfirm: null,
        
        show(detail) {
            this.title = detail.title || 'Konfirmasi';
            this.message = detail.message || 'Apakah Anda yakin?';
            this.confirmText = detail.confirmText || 'Konfirmasi';
            this.cancelText = detail.cancelText || 'Batal';
            this.type = detail.type || 'danger';
            this.onConfirm = detail.onConfirm;
            this.isOpen = true;
        },
        confirm() {
            this.isOpen = false;
            if (this.onConfirm) {
                this.onConfirm();
            }
        },
        cancel() {
            this.isOpen = false;
        }
    }));
});
</script>

