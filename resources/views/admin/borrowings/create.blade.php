@extends('layouts.app')

@section('title', 'Tambah Peminjaman')

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Tambah Peminjaman</h1>
            <div class="h-1 w-16 bg-primary mt-2"></div>
        </div>

        <div
            class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-5 sm:p-8 industrial-border">
            <form method="POST" action="{{ route('admin.borrowings.store') }}" id="borrowingForm">
                @csrf

                <div class="mb-6">
                    <label
                        class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Peminjam
                        <span class="text-red-400">*</span></label>
                    <select name="user_id"
                        class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all"
                        required autofocus>
                        <option value="">Pilih Peminjam</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}
                                ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label
                            class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Tanggal
                            Mulai <span class="text-red-400">*</span></label>
                        <input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}"
                            class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all"
                            required>
                    </div>
                    <div>
                        <label
                            class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Tanggal
                            Selesai <span class="text-red-400">*</span></label>
                        <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                            class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all"
                            required>
                    </div>
                </div>

                <div class="mb-6">
                    <label
                        class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Status
                        <span class="text-red-400">*</span></label>
                    <select name="status"
                        class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all"
                        required>
                        <option value="menunggu" {{ old('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="disetujui" {{ old('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ old('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="menunggu_pengembalian" {{ old('status') == 'menunggu_pengembalian' ? 'selected' : '' }}>Menunggu Pengembalian</option>
                        <option value="dikembalikan" {{ old('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan
                        </option>
                    </select>
                </div>

                <div class="mb-8">
                    <label
                        class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Pilih
                        Alat <span class="text-red-400">*</span></label>
                    <div id="toolsContainer" class="space-y-4">
                        <div
                            class="tool-item flex flex-col sm:flex-row gap-4 sm:items-end border border-gray-200 dark:border-gray-700/50 p-4 rounded-xl bg-gray-50/50 dark:bg-background-dark">
                            <div class="flex-1 w-full">
                                <label class="block text-[10px] text-gray-400 mb-1 uppercase tracking-wider">Alat</label>
                                <select name="tools[0][tool_id]"
                                    class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all tool-select"
                                    required>
                                    <option value="">Pilih Alat</option>
                                    @foreach($tools as $tool)
                                        <option value="{{ $tool->id }}" data-stok="{{ $tool->stok }}">{{ $tool->nama_alat }}
                                            (Stok: {{ $tool->stok }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full sm:w-32">
                                <label class="block text-[10px] text-gray-400 mb-1 uppercase tracking-wider">Jumlah</label>
                                <input type="number" name="tools[0][jumlah]" min="1" value="1"
                                    class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all jumlah-input"
                                    required>
                            </div>
                            <div class="flex justify-end">
                                <button type="button" onclick="removeTool(this)"
                                    class="w-full sm:w-auto p-2.5 bg-red-500/10 hover:bg-red-500/20 text-red-500 border border-red-500/20 rounded-lg transition-all flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="addTool()"
                        class="mt-4 px-4 py-2 bg-green-500/10 hover:bg-green-500/20 text-green-500 border border-green-500/20 rounded-lg transition-all flex items-center gap-2 text-sm font-medium">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        Tambah Alat
                    </button>
                </div>

                <div class="mb-8">
                    <label
                        class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Keterangan</label>
                    <textarea name="keterangan" rows="3"
                        class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all"
                        placeholder="Keterangan peminjaman...">{{ old('keterangan') }}</textarea>
                </div>

                <div class="flex space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700/50">
                    <button type="submit"
                        class="px-6 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-lg font-medium transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
                        <span class="material-symbols-outlined text-[20px]">save</span>
                        Simpan
                    </button>
                    <a href="{{ route('admin.borrowings.index') }}"
                        class="px-6 py-2.5 bg-transparent border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-all flex items-center gap-2 font-medium">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        let toolIndex = 1;

        function addTool() {
            const container = document.getElementById('toolsContainer');
            const newItem = document.createElement('div');
            newItem.className = 'tool-item flex flex-col sm:flex-row gap-4 sm:items-end border border-gray-200 dark:border-gray-700/50 p-4 rounded-xl bg-gray-50/50 dark:bg-background-dark animate-fade-in-up';
            newItem.innerHTML = `
            <div class="flex-1 w-full">
                <label class="block text-[10px] text-gray-400 mb-1 uppercase tracking-wider">Alat</label>
                <select name="tools[${toolIndex}][tool_id]" class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all tool-select" required>
                    <option value="">Pilih Alat</option>
                    @foreach($tools as $tool)
                        <option value="{{ $tool->id }}" data-stok="{{ $tool->stok }}">{{ $tool->nama_alat }} (Stok: {{ $tool->stok }})</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full sm:w-32">
                <label class="block text-[10px] text-gray-400 mb-1 uppercase tracking-wider">Jumlah</label>
                <input type="number" name="tools[${toolIndex}][jumlah]" min="1" value="1" class="w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all jumlah-input" required>
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="removeTool(this)" class="w-full sm:w-auto p-2.5 bg-red-500/10 hover:bg-red-500/20 text-red-500 border border-red-500/20 rounded-lg transition-all flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">delete</span>
                </button>
            </div>
        `;
            container.appendChild(newItem);
            toolIndex++;
        }

        function removeTool(button) {
            const items = document.querySelectorAll('.tool-item');
            if (items.length > 1) {
                button.closest('.tool-item').remove();
            } else {
                showConfirmModal({
                    title: 'Peringatan',
                    message: 'Minimal harus ada 1 alat untuk dapat membuat peminjaman.',
                    type: 'warning',
                    okText: 'Mengerti',
                    onConfirm: function () { }
                });
            }
        }
    </script>
@endsection