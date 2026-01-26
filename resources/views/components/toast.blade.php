<div x-data="{ 
    toasts: [],
    addToast(message, type = 'success') {
        const id = Date.now();
        this.toasts.push({ id, message, type });
        setTimeout(() => this.removeToast(id), 5000);
    },
    removeToast(id) {
        this.toasts = this.toasts.filter(t => t.id !== id);
    }
}" 
x-init="
    window.addEventListener('toast', (e) => {
        addToast(e.detail.message, e.detail.type);
    });
"
class="fixed top-4 right-4 z-50 space-y-2">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="true"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-x-full"
             x-transition:enter-end="opacity-100 transform translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="card border shadow-lg min-w-[300px] fade-in"
             :class="{
                 'border-emerald-500/50 bg-emerald-900/20': toast.type === 'success',
                 'border-red-500/50 bg-red-900/20': toast.type === 'error',
                 'border-blue-500/50 bg-blue-900/20': toast.type === 'info',
                 'border-amber-500/50 bg-amber-900/20': toast.type === 'warning'
             }">
            <div class="card-body flex items-start gap-3"
                 :class="{
                     'text-emerald-300': toast.type === 'success',
                     'text-red-300': toast.type === 'error',
                     'text-blue-300': toast.type === 'info',
                     'text-amber-300': toast.type === 'warning'
                 }">
                <span class="inline-flex h-6 w-6 items-center justify-center rounded-full flex-shrink-0"
                      :class="{
                          'bg-emerald-500/20 text-emerald-400': toast.type === 'success',
                          'bg-red-500/20 text-red-400': toast.type === 'error',
                          'bg-blue-500/20 text-blue-400': toast.type === 'info',
                          'bg-amber-500/20 text-amber-400': toast.type === 'warning'
                      }">
                    <span x-text="toast.type === 'success' ? '✓' : (toast.type === 'error' ? '!' : 'i')"></span>
                </span>
                <div class="flex-1">
                    <p class="text-sm font-medium" x-text="toast.message"></p>
                </div>
                <button @click="removeToast(toast.id)" class="text-gray-400 hover:text-gray-300">×</button>
            </div>
        </div>
    </template>
</div>





