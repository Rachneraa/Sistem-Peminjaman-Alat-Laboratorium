@extends('layouts.app')

@section('title', 'Menyetujui Peminjaman')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Menyetujui Peminjaman</h1>
        <div class="h-1 w-16 bg-primary mt-2"></div>
    </div>
</div>

@php
    $activeFiltersCount = collect(request()->only(['search', 'status']))->filter()->count();
@endphp

<x-filter-panel :action="route('petugas.borrowings.index')" :activeFiltersCount="$activeFiltersCount">
    <div class="md:col-span-2">
        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Cari Peminjam</label>
        <div class="relative group">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Ketik nama atau email..." class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 pl-10 transition-all">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400 dark:text-gray-500">
                <span class="material-symbols-outlined text-[20px]">search</span>
            </div>
        </div>
    </div>

    <div class="md:col-span-2">
        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Status Peminjaman</label>
        <select name="status" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all">
            <option value="">Semua (Menunggu & Disetujui)</option>
            <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu Persetujuan</option>
            <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Sudah Disetujui</option>
        </select>
    </div>
</x-filter-panel>

<!-- Table Peminjaman -->
<x-card class="overflow-hidden" :padding="false">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-white/5">
            <thead class="bg-gray-50 dark:bg-panel-dark sticky top-0 z-10 border-b border-gray-200 dark:border-white/5">
                <tr>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Peminjam</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Periode Pinjam</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Daftar Alat</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                @forelse($borrowings as $borrowing)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-primary text-[18px]">person</span>
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $borrowing->user->name }}</div>
                                    <div class="text-[10px] text-gray-500 font-mono uppercase mt-0.5">ID: #{{ $borrowing->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-xs space-y-1">
                                <div class="text-gray-600 dark:text-gray-300 flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[14px] text-gray-400">calendar_today</span>
                                    {{ $borrowing->tanggal_pinjam->format('d/m/Y') }}
                                </div>
                                <div class="text-gray-400 flex items-center gap-1.5">
                                    <span class="material-symbols-outlined text-[14px]">event_busy</span>
                                    {{ $borrowing->jatuh_tempo ? $borrowing->jatuh_tempo->format('d/m/Y') : '-' }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                @foreach($borrowing->borrowingDetails->take(2) as $detail)
                                    <div class="text-xs text-gray-600 dark:text-gray-300 flex items-center gap-2">
                                        <span class="w-1 h-1 rounded-full bg-primary"></span>
                                        {{ $detail->tool->nama_alat }} ({{ $detail->jumlah }})
                                    </div>
                                @endforeach
                                @if($borrowing->borrowingDetails->count() > 2)
                                    <div class="text-[10px] text-gray-400 italic pl-3">+ {{ $borrowing->borrowingDetails->count() - 2 }} lainnya</div>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <x-badge :type="match($borrowing->status) {
                                'disetujui' => 'info',
                                'dikembalikan' => 'success',
                                'ditolak' => 'danger',
                                default => 'warning'
                            }" size="sm">
                                {{ ucfirst(str_replace('_', ' ', $borrowing->status)) }}
                            </x-badge>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex flex-col items-end gap-1">
                                <x-button variant="ghost" size="sm" :href="route('petugas.borrowings.show', $borrowing)" icon="visibility" class="text-blue-500">Detail</x-button>
                                
                                @if($borrowing->status == 'menunggu')
                                    <div class="flex gap-1 mt-1">
                                        <form method="POST" action="{{ route('petugas.borrowings.approve', $borrowing) }}" class="inline approve-form" data-id="{{ $borrowing->id }}">
                                            @csrf
                                            <input type="hidden" name="jaminan_tipe" value="ktp" class="jaminan-input">
                                                <x-button variant="success" size="sm" type="button" class="font-bold gap-1" onclick="handleApproveBorrowing(this)">
                                                    <span class="material-symbols-outlined text-[18px]">check_circle</span>
                                                    Setujui
                                                </x-button>
                                        </form>
                                            <x-button variant="danger" size="sm" type="button" class="font-bold gap-1" onclick="showRejectModal({{ $borrowing->id }}, '{{ route('petugas.borrowings.reject', $borrowing) }}')">
                                                <span class="material-symbols-outlined text-[18px]">cancel</span>
                                                Tolak
                                            </x-button>
                                    </div>
                                @endif
                                <x-button variant="ghost" size="sm" :href="route('petugas.borrowings.print', $borrowing)" icon="print" class="text-gray-500 mt-1">Print</x-button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <x-empty-state 
                                icon="pending_actions"
                                title="Tidak Ada Peminjaman"
                                description="Belum ada transaksi peminjaman yang masuk atau filter tidak sesuai."
                            />
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($borrowings->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-white/5 bg-gray-50 dark:bg-panel-dark">
            {{ $borrowings->links('vendor.pagination.industrial') }}
        </div>
    @endif
</x-card>

<!-- Modal Tolak -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/10 w-full max-w-md shadow-2xl rounded-xl industrial-border">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-red-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-red-500">block</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider">Tolak Peminjaman</h3>
            </div>
            <button onclick="closeRejectModal()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="p-6">
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Keterangan / Alasan</label>
                    <textarea name="keterangan" rows="4" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-red-500 focus:border-red-500 block p-3 transition-all" required placeholder="Alasan penolakan..."></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700/50">
                    <button type="button" onclick="closeRejectModal()" class="px-4 py-2 bg-transparent border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-all font-medium text-xs uppercase tracking-wider">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white rounded-lg font-medium transition-all text-xs uppercase tracking-wider shadow-lg shadow-red-600/20 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">block</span>
                        Tolak Permintaan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Jaminan -->
<div id="guaranteeModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/10 w-full max-w-md shadow-2xl rounded-xl industrial-border">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-green-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-500">security</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider">Pilih Jaminan</h3>
            </div>
            <button onclick="closeGuaranteeModal()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <div class="p-6">
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Jaminan Sebagai Apa?</label>
                <select id="guaranteeTypeSelect" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all">
                    <option value="ktp">KTP</option>
                    <option value="sim">SIM</option>
                    <option value="kartu_pelajar">Kartu Pelajar</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700/50">
                <button type="button" onclick="closeGuaranteeModal()" class="px-4 py-2 bg-transparent border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-all font-medium text-xs uppercase tracking-wider">Batal</button>
                <button type="button" onclick="submitApproveWithGuarantee()" class="px-4 py-2 bg-green-600 hover:bg-green-500 text-white rounded-lg font-medium transition-all text-xs uppercase tracking-wider shadow-lg shadow-green-600/20 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">check_circle</span>
                    Setujui
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let activeApproveForm = null;

function showRejectModal(borrowingId, actionUrl) {
    document.getElementById('rejectForm').action = actionUrl;
    document.getElementById('rejectModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function handleApproveBorrowing(button) {
    activeApproveForm = button.closest('form');
    document.getElementById('guaranteeTypeSelect').value = 'ktp';
    document.getElementById('guaranteeModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeGuaranteeModal() {
    document.getElementById('guaranteeModal').classList.add('hidden');
    document.body.style.overflow = '';
    activeApproveForm = null;
}

function submitApproveWithGuarantee() {
    if (!activeApproveForm) {
        closeGuaranteeModal();
        return;
    }

    const selectedGuarantee = document.getElementById('guaranteeTypeSelect').value;
    const guaranteeInput = activeApproveForm.querySelector('.jaminan-input');
    if (guaranteeInput) {
        guaranteeInput.value = selectedGuarantee;
    }

    activeApproveForm.submit();
}

// Close modal on background click
document.getElementById('rejectModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});

document.getElementById('guaranteeModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeGuaranteeModal();
    }
});
</script>
@endsection

