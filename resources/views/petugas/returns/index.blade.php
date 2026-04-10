@extends('layouts.app')

@section('title', 'Verifikasi Pengembalian')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Verifikasi Pengembalian</h1>
        <div class="h-1 w-16 bg-primary mt-2"></div>
    </div>
</div>

@php
    $activeFiltersCount = collect(request()->only(['filter']))->filter()->count();
@endphp

<x-filter-panel :action="route('petugas.returns.index')" :activeFiltersCount="$activeFiltersCount">
    <div class="md:col-span-4">
        <label class="block text-[10px] font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Filter Keterlambatan</label>
        <select name="filter" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all">
            <option value="">Semua (Aktif & Menunggu)</option>
            <option value="terlambat" {{ request('filter') == 'terlambat' ? 'selected' : '' }}>Hanya Peminjaman Terlambat</option>
        </select>
    </div>
</x-filter-panel>

<x-card class="overflow-hidden" :padding="false">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-white/5">
            <thead class="bg-gray-50 dark:bg-panel-dark sticky top-0 z-10 border-b border-gray-200 dark:border-white/5">
                <tr>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Peminjam</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Periode Pinjam</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Daftar Alat</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Estimasi Denda</th>
                    <th class="px-6 py-4 text-left text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Status</th>
                    <th class="px-6 py-4 text-right text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                @forelse($borrowings as $borrowing)
                    @php
                        $tanggalSelesai = $borrowing->tanggal_selesai ?? $borrowing->jatuh_tempo;
                        $isOverdue = $tanggalSelesai && now()->startOfDay()->gt(\Carbon\Carbon::parse($tanggalSelesai)->startOfDay());
                        $estimatedFine = $borrowing->calculateEstimatedFine();
                    @endphp
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition-colors group {{ $isOverdue ? 'bg-red-500/[0.03]' : '' }}" data-borrowing-id="{{ $borrowing->id }}" data-estimated-fine="{{ (int) $estimatedFine['denda'] }}" data-estimated-days="{{ (int) $estimatedFine['terlambat_hari'] }}">
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
                                <div class="flex flex-wrap items-center gap-2">
                                    <div class="text-gray-400 flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-[14px]">event_busy</span>
                                        {{ $borrowing->tanggal_selesai ? $borrowing->tanggal_selesai->format('d/m/Y') : ($borrowing->jatuh_tempo ? $borrowing->jatuh_tempo->format('d/m/Y') : '-') }}
                                    </div>
                                    @if($isOverdue)
                                        <x-badge type="danger" size="xs">Terlambat</x-badge>
                                    @endif
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
                            @if($estimatedFine['denda'] > 0)
                                <div class="text-xs font-bold text-red-500">Rp{{ number_format($estimatedFine['denda'], 0, ',', '.') }}</div>
                                <div class="text-[10px] text-red-400">{{ $estimatedFine['terlambat_hari'] }} Hari</div>
                            @else
                                <span class="text-xs text-accent-green">Rp0</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <x-badge type="warning" size="sm">MENUNGGU PENGEMBALIAN</x-badge>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex flex-col items-end gap-1">
                                <x-button variant="ghost" size="sm" :href="route('petugas.borrowings.show', [$borrowing, 'from' => 'returns'])" icon="visibility" class="text-blue-500">Detail</x-button>
                                <div class="flex gap-1 mt-1">
                                    <x-button variant="ghost" size="sm" type="button" class="text-yellow-500" icon="notifications" onclick="showReminderModal({{ $borrowing->id }}, '{{ route('petugas.borrowings.reminder', $borrowing) }}')" />
                                    @if($estimatedFine['denda'] > 0)
                                        <form method="POST" action="{{ route('petugas.borrowings.fine-notification', $borrowing) }}" class="inline">
                                            @csrf
                                            <x-button variant="ghost" size="sm" type="submit" class="text-orange-500" icon="payments" />
                                        </form>
                                    @endif
                                    <x-button variant="success" size="sm" type="button" class="font-bold flex items-center gap-1" onclick="showReturnModal({{ $borrowing->id }}, '{{ route('petugas.borrowings.return', $borrowing) }}')">
                                        <span class="material-symbols-outlined text-[18px]">assignment_return</span>
                                        Setujui
                                    </x-button>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <x-empty-state 
                                icon="assignment_return"
                                title="Tidak Ada Pengembalian"
                                description="Belum ada pendaftaran pengembalian atau filter tidak sesuai."
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

<!-- Modal Pengingat -->
<div id="reminderModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/10 w-full max-w-md shadow-2xl rounded-xl industrial-border">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-yellow-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-yellow-500">alarm</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider">Kirim Pengingat</h3>
            </div>
            <button onclick="closeReminderModal()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="reminderForm" method="POST">
            @csrf
            <div class="p-6">
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Pesan Pengingat</label>
                    <textarea name="pesan" rows="4" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-yellow-500 focus:border-yellow-500 block p-3 transition-all" placeholder="Pesan pengingat (opsional, kosongkan untuk menggunakan pesan default)..."></textarea>
                    <p class="text-[10px] text-gray-500 dark:text-gray-500 mt-2 uppercase tracking-wide">Jika dikosongkan, akan menggunakan pesan default.</p>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700/50">
                    <button type="button" onclick="closeReminderModal()" class="px-4 py-2 bg-transparent border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-all font-medium text-xs uppercase tracking-wider">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-500 text-white rounded-lg font-medium transition-all text-xs uppercase tracking-wider shadow-lg shadow-yellow-600/20 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">send</span>
                        Kirim
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Pengembalian -->
<div id="returnModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
    <div class="relative bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/10 w-full max-w-md shadow-2xl rounded-xl industrial-border">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-green-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-500">assignment_return</span>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white uppercase tracking-wider">Menyetujui Pengembalian</h3>
            </div>
            <button onclick="closeReturnModal()" class="text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form id="returnForm" method="POST">
            @csrf
            <div class="p-6">
                <!-- Informasi Alat yang Dikembalikan -->
                <div class="mb-6 p-4 bg-gray-50 dark:bg-background-dark/50 rounded-lg border border-gray-200 dark:border-gray-700/50">
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-3">Alat yang Dikembalikan:</p>
                    <div id="returnToolsList" class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
                        <!-- Akan diisi oleh JavaScript -->
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Tanggal Kembali</label>
                    <input type="date" name="tanggal_kembali" value="{{ date('Y-m-d') }}" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all" required>
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Denda Keterlambatan (Otomatis)</label>
                    <input type="text" id="estimatedFineDisplay" readonly class="w-full bg-gray-100 dark:bg-background-dark/70 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg block p-2.5 transition-all" value="Rp 0">
                    <p id="estimatedFineCaption" class="text-[10px] text-gray-500 dark:text-gray-500 mt-1 uppercase tracking-wide">Dihitung otomatis dari keterlambatan.</p>
                </div>
                <div class="mb-4 p-3 rounded-lg border border-gray-200 dark:border-gray-700/50 bg-gray-50 dark:bg-background-dark/40">
                    <label class="inline-flex items-start gap-2 cursor-pointer">
                        <input type="checkbox" name="abaikan_denda" id="abaikanDendaCheckbox" value="1" class="mt-1 rounded border-gray-300 text-primary focus:ring-primary">
                        <span class="text-xs text-gray-700 dark:text-gray-300">
                            Abaikan denda keterlambatan
                            <span class="block text-[10px] text-gray-500 dark:text-gray-500 uppercase tracking-wide">Wajib isi alasan jika denda diabaikan.</span>
                        </span>
                    </label>
                    <div id="abaikanDendaReasonWrap" class="hidden mt-3">
                        <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Alasan Abaikan Denda</label>
                        <textarea name="alasan_abaikan_denda" id="alasanAbaikanDendaInput" rows="2" class="w-full bg-white dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" placeholder="Tuliskan alasan mengapa denda keterlambatan diabaikan..."></textarea>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Denda Kerusakan (Rp)</label>
                    <input type="number" name="denda_kerusakan" min="0" step="1000" value="0" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all" placeholder="0">
                    <p class="text-[10px] text-gray-500 dark:text-gray-500 mt-1 uppercase tracking-wide">Periksa kondisi alat dan masukkan jumlah denda jika ada kerusakan.</p>
                </div>
                <div class="mb-6">
                    <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Keterangan / Catatan Kondisi</label>
                    <textarea name="keterangan" rows="3" class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all" placeholder="Catatan kondisi alat, kerusakan yang ditemukan, dll..."></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700/50">
                    <button type="button" onclick="closeReturnModal()" class="px-4 py-2 bg-transparent border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-all font-medium text-xs uppercase tracking-wider">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary/90 text-white rounded-lg font-medium transition-all text-xs uppercase tracking-wider shadow-lg shadow-primary/20 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">check_circle</span>
                        Setujui & Proses
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function showReminderModal(borrowingId, actionUrl) {
    document.getElementById('reminderForm').action = actionUrl;
    document.getElementById('reminderModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeReminderModal() {
    document.getElementById('reminderModal').classList.add('hidden');
    document.body.style.overflow = '';
}

function showReturnModal(borrowingId, actionUrl) {
    document.getElementById('returnForm').action = actionUrl;

    const fineDisplay = document.getElementById('estimatedFineDisplay');
    const fineCaption = document.getElementById('estimatedFineCaption');
    const abaikanCheckbox = document.getElementById('abaikanDendaCheckbox');
    const reasonWrap = document.getElementById('abaikanDendaReasonWrap');
    const reasonInput = document.getElementById('alasanAbaikanDendaInput');

    if (abaikanCheckbox) abaikanCheckbox.checked = false;
    if (reasonWrap) reasonWrap.classList.add('hidden');
    if (reasonInput) {
        reasonInput.value = '';
        reasonInput.required = false;
    }

    // Ambil data alat dari tabel
    const row = document.querySelector(`tr[data-borrowing-id="${borrowingId}"]`);
    if (row) {
        const estimatedFine = Number(row.dataset.estimatedFine || 0);
        const estimatedDays = Number(row.dataset.estimatedDays || 0);

        if (fineDisplay) {
            fineDisplay.value = `Rp ${estimatedFine.toLocaleString('id-ID')}`;
        }
        if (fineCaption) {
            fineCaption.textContent = estimatedDays > 0
                ? `Terlambat ${estimatedDays} hari. Denda otomatis dihitung sistem.`
                : 'Tidak ada keterlambatan.';
        }

        const toolsCell = row.querySelector('td:nth-child(3)'); // Kolom Daftar Alat (Index 3)
        const toolsList = document.getElementById('returnToolsList');
        if (toolsCell && toolsList) {
            toolsList.innerHTML = toolsCell.innerHTML.replace(/<ul[^>]*>|<\/ul>/g, '').replace(/<li[^>]*>/g, '<div class="py-1 border-b border-gray-700/50 last:border-0 flex items-center gap-2">').replace(/<\/li>/g, '</div>').replace(/<span class="w-1.5 h-1.5 rounded-full bg-gray-500"><\/span>/g, '<span class="material-symbols-outlined text-[16px] text-gray-500">construction</span>');
        }
    }

    document.getElementById('returnModal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeReturnModal() {
    document.getElementById('returnModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Close modals on background click
document.addEventListener('DOMContentLoaded', function() {
    const reminderModal = document.getElementById('reminderModal');
    const returnModal = document.getElementById('returnModal');
    const returnForm = document.getElementById('returnForm');
    const abaikanCheckbox = document.getElementById('abaikanDendaCheckbox');
    const reasonWrap = document.getElementById('abaikanDendaReasonWrap');
    const reasonInput = document.getElementById('alasanAbaikanDendaInput');

    if (abaikanCheckbox && reasonWrap && reasonInput) {
        const syncAbaikanState = () => {
            const active = abaikanCheckbox.checked;
            reasonWrap.classList.toggle('hidden', !active);
            reasonInput.required = active;
            if (!active) {
                reasonInput.value = '';
            }
        };

        abaikanCheckbox.addEventListener('change', syncAbaikanState);
        syncAbaikanState();
    }

    if (returnForm && abaikanCheckbox && reasonInput) {
        returnForm.addEventListener('submit', function (e) {
            if (abaikanCheckbox.checked && !reasonInput.value.trim()) {
                e.preventDefault();
                reasonInput.focus();
                alert('Alasan abaikan denda wajib diisi.');
            }
        });
    }

    if (reminderModal) {
        reminderModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeReminderModal();
            }
        });
    }

    if (returnModal) {
        returnModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeReturnModal();
            }
        });
    }

    // Close modals on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (reminderModal && !reminderModal.classList.contains('hidden')) {
                closeReminderModal();
            }
            if (returnModal && !returnModal.classList.contains('hidden')) {
                closeReturnModal();
            }
        }
    });
});
</script>
@endsection

