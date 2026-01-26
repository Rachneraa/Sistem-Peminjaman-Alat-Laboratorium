<!-- Confirm Modal Component -->
<div id="confirmModal" class="hidden fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-gray-800 border border-gray-700 rounded-xl shadow-2xl max-w-md w-full transform transition-all">
        <!-- Modal Header -->
        <div class="flex items-center justify-between p-6 border-b border-gray-700">
            <div class="flex items-center gap-3">
                <div id="confirmIcon" class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 id="confirmTitle" class="text-lg font-bold text-white">Konfirmasi</h3>
            </div>
            <button onclick="closeConfirmModal()" class="text-gray-400 hover:text-gray-300 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <p id="confirmMessage" class="text-gray-300 mb-6"></p>
            
            <div class="flex justify-end gap-3">
                <button onclick="closeConfirmModal()" id="confirmCancelBtn" class="btn btn-secondary px-6">
                    Batal
                </button>
                <button onclick="confirmAction()" id="confirmOkBtn" class="btn px-6 inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Ya, Lanjutkan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let confirmCallback = null;

function showConfirmModal(options) {
    const {
        title = 'Konfirmasi',
        message,
        type = 'warning', // warning, danger, info, success
        okText = 'Ya, Lanjutkan',
        cancelText = 'Batal',
        okClass = 'btn-danger',
        onConfirm = null
    } = options;

    // Set title and message
    document.getElementById('confirmTitle').textContent = title;
    document.getElementById('confirmMessage').textContent = message;
    document.getElementById('confirmOkBtn').textContent = okText;
    document.getElementById('confirmCancelBtn').textContent = cancelText;

    // Set icon and colors based on type
    const iconEl = document.getElementById('confirmIcon');
    const okBtn = document.getElementById('confirmOkBtn');
    
    // Remove previous classes
    iconEl.className = 'w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0';
    okBtn.className = 'btn px-6 inline-flex items-center gap-2';

    switch(type) {
        case 'danger':
            iconEl.classList.add('bg-red-500/20', 'text-red-400');
            okBtn.classList.add('btn-danger');
            break;
        case 'success':
            iconEl.classList.add('bg-green-500/20', 'text-green-400');
            okBtn.classList.add('bg-green-600', 'text-white', 'hover:bg-green-700');
            break;
        case 'info':
            iconEl.classList.add('bg-blue-500/20', 'text-blue-400');
            okBtn.classList.add('bg-blue-600', 'text-white', 'hover:bg-blue-700');
            break;
        default: // warning
            iconEl.classList.add('bg-amber-500/20', 'text-amber-400');
            okBtn.classList.add('bg-amber-600', 'text-white', 'hover:bg-amber-700');
    }

    // Set callback
    confirmCallback = onConfirm;

    // Show modal
    document.getElementById('confirmModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeConfirmModal() {
    document.getElementById('confirmModal').classList.add('hidden');
    document.body.style.overflow = '';
    confirmCallback = null;
}

function confirmAction() {
    if (confirmCallback) {
        confirmCallback();
    }
    closeConfirmModal();
}

// Close modal on background click and ESC key
document.addEventListener('DOMContentLoaded', function() {
    const confirmModal = document.getElementById('confirmModal');
    if (confirmModal) {
        confirmModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeConfirmModal();
            }
        });
    }

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (confirmModal && !confirmModal.classList.contains('hidden')) {
                closeConfirmModal();
            }
        }
    });
});
</script>

