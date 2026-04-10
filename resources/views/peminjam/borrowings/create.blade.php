@extends('layouts.app')

@section('title', 'Ajukan Peminjaman')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Ajukan Peminjaman</h1>
            <div class="h-1 w-16 bg-primary mt-2"></div>
        </div>
    </div>

    <div class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-6 industrial-border">
        <form method="POST" action="{{ route('peminjam.borrowings.store') }}" id="borrowingForm">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label
                        class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Tanggal
                        Mulai</label>
                    <input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}"
                        min="{{ date('Y-m-d') }}"
                        class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all"
                        required autofocus>
                </div>
                <div>
                    <label
                        class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Tanggal
                        Selesai</label>
                    <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" min="{{ date('Y-m-d') }}"
                        class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all"
                        required>
                    <p class="text-xs text-gray-500 mt-1 uppercase tracking-wide">Tanggal pengembalian yang diharapkan</p>
                </div>
            </div>

            <div class="mb-6">
                <label
                    class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-3 uppercase tracking-widest pl-1">Pilih
                    Alat</label>
                <div id="toolsContainer" class="space-y-4">
                    <div
                        class="tool-item flex gap-4 items-end border border-gray-200 dark:border-gray-700 p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50">
                        @if(isset($selectedTool) && $selectedTool)
                            <div class="flex-1">
                                <label
                                    class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest">Alat
                                    yang Dipilih</label>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex-1 p-3 bg-white dark:bg-gray-700/50 rounded-lg border border-gray-200 dark:border-gray-600">
                                        <div class="font-medium text-gray-900 dark:text-white">{{ $selectedTool->nama_alat }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Kategori:
                                            {{ $selectedTool->category->nama_kategori }} | Stok:
                                            {{ $selectedTool->stok_tersedia }}</div>
                                    </div>
                                    <button type="button" onclick="changeTool(this)"
                                        class="px-4 py-2 bg-transparent border border-gray-400 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-all font-medium text-sm">Ubah</button>
                                </div>
                                <input type="hidden" name="tools[0][tool_id]" value="{{ $selectedTool->id }}"
                                    class="tool-id-input">
                            </div>
                        @else
                            <div class="flex-1">
                                <label
                                    class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest">Alat</label>
                                <select name="tools[0][tool_id]"
                                    class="w-full bg-white dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all tool-select"
                                    required>
                                    <option value="">Pilih Alat</option>
                                    @foreach($tools as $tool)
                                        <option value="{{ $tool->id }}" data-stok="{{ $tool->stok_tersedia }}" {{ old('tools.0.tool_id') == $tool->id ? 'selected' : '' }}>{{ $tool->nama_alat }} (Stok:
                                            {{ $tool->stok_tersedia }})</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                        <div class="w-32">
                            <label
                                class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest">Jumlah</label>
                            <input type="number" name="tools[0][jumlah]" min="1" value="1"
                                max="{{ isset($selectedTool) ? $selectedTool->stok_tersedia : '' }}"
                                class="w-full bg-white dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all jumlah-input"
                                required>
                        </div>
                        <button type="button" onclick="removeTool(this)"
                            class="h-[42px] px-4 bg-red-600 hover:bg-red-500 text-white rounded-lg font-medium transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">delete</span>
                            Hapus
                        </button>
                    </div>
                </div>
                <button type="button" onclick="addTool()"
                    class="mt-4 h-[42px] px-5 bg-green-600 hover:bg-green-500 text-white rounded-lg font-medium transition-all flex items-center gap-2 shadow-lg shadow-green-600/20">
                    <span class="material-symbols-outlined text-[20px]">add</span>
                    Tambah Alat
                </button>
            </div>

            <div class="flex gap-3 pt-6 border-t border-gray-200 dark:border-gray-700/50">
                <button type="submit"
                    class="px-5 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-lg transition-all flex items-center gap-2 text-sm font-medium shadow-lg shadow-primary/20">
                    <span class="material-symbols-outlined text-[20px]">send</span>
                    Ajukan Peminjaman
                </button>
                <a href="{{ route('peminjam.borrowings.index') }}"
                    class="px-5 py-2.5 bg-transparent border border-gray-400 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-all font-medium text-sm flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                    Batal
                </a>
            </div>
        </form>
    </div>

    <script>
        let toolIndex = 1;
        const toolsData = [
            @foreach($tools as $tool)
                { id: {{ $tool->id }}, nama: "{{ $tool->nama_alat }}", stok: {{ $tool->stok_tersedia }} },
            @endforeach
    ];

        function addTool() {
            const container = document.getElementById('toolsContainer');
            const newItem = document.createElement('div');
            newItem.className = 'tool-item flex gap-4 items-end border border-gray-200 dark:border-gray-700 p-4 rounded-lg bg-gray-50 dark:bg-gray-800/50';

            let optionsHtml = '<option value="">Pilih Alat</option>';
            toolsData.forEach(tool => {
                optionsHtml += `<option value="${tool.id}" data-stok="${tool.stok}">${tool.nama} (Stok: ${tool.stok})</option>`;
            });

            newItem.innerHTML = `
            <div class="flex-1">
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest">Alat</label>
                <select name="tools[${toolIndex}][tool_id]" class="w-full bg-white dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all tool-select" required>
                    ${optionsHtml}
                </select>
            </div>
            <div class="w-32">
                <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest">Jumlah</label>
                <input type="number" name="tools[${toolIndex}][jumlah]" min="1" value="1" class="w-full bg-white dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all jumlah-input" required>
            </div>
            <button type="button" onclick="removeTool(this)" class="h-[42px] px-4 bg-red-600 hover:bg-red-500 text-white rounded-lg font-medium transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">delete</span>
                Hapus
            </button>
        `;
            container.appendChild(newItem);
            toolIndex++;
        }

        function changeTool(button) {
            const toolItem = button.closest('.tool-item');
            const toolIdInput = toolItem.querySelector('.tool-id-input');
            const currentToolId = toolIdInput ? toolIdInput.value : null;
            const jumlahInput = toolItem.querySelector('.jumlah-input');
            const currentJumlah = jumlahInput ? jumlahInput.value : 1;

            // Ganti dengan dropdown
            const toolInfoContainer = toolItem.querySelector('.flex-1');

            let optionsHtml = '<option value="">Pilih Alat</option>';
            toolsData.forEach(tool => {
                const selected = currentToolId == tool.id ? 'selected' : '';
                optionsHtml += `<option value="${tool.id}" data-stok="${tool.stok}" ${selected}>${tool.nama} (Stok: ${tool.stok})</option>`;
            });

            toolInfoContainer.innerHTML = `
            <label class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest">Alat</label>
            <select name="tools[0][tool_id]" class="w-full bg-white dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-2.5 transition-all tool-select" required>
                ${optionsHtml}
            </select>
        `;

            // Update max jumlah jika ada
            const newSelect = toolInfoContainer.querySelector('.tool-select');
            if (newSelect) {
                newSelect.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];
                    const stok = selectedOption ? selectedOption.getAttribute('data-stok') : '';
                    if (jumlahInput && stok) {
                        jumlahInput.setAttribute('max', stok);
                    }
                });
                // Trigger untuk set max awal
                if (newSelect.value) {
                    const selectedOption = newSelect.options[newSelect.selectedIndex];
                    const stok = selectedOption ? selectedOption.getAttribute('data-stok') : '';
                    if (jumlahInput && stok) {
                        jumlahInput.setAttribute('max', stok);
                    }
                }
            }
        }

        function removeTool(button) {
            const items = document.querySelectorAll('.tool-item');
            if (items.length > 1) {
                button.closest('.tool-item').remove();
            } else {
                showConfirmModal({
                    title: 'Peringatan',
                    message: 'Minimal harus ada 1 alat untuk dapat mengajukan peminjaman.',
                    type: 'warning',
                    okText: 'Mengerti',
                    onConfirm: function () {
                        // Just close
                    }
                });
            }
        }
    </script>
@endsection