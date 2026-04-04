@extends('layouts.app')

@section('title', 'Pengaturan Denda')

@section('content')
    <div class="mb-6 flex flex-col gap-2 md:flex-row md:items-end md:justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Pengaturan Denda</h1>
            <div class="h-1 w-16 bg-primary mt-2"></div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Atur nominal denda per alat. Nilai ini dipakai untuk
                semua perhitungan denda.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button type="button" id="save-all-denda-btn"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-primary hover:bg-primary/90 text-white transition shadow-lg shadow-primary/20">
                <span class="material-symbols-outlined text-[18px]">save</span>
                Simpan Semua
            </button>
            <a href="{{ route('admin.denda.export') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg border border-gray-300 dark:border-gray-700 text-gray-700 dark:text-gray-200 hover:border-primary hover:text-primary hover:bg-primary/5 transition">
                <span class="material-symbols-outlined text-[18px]">download</span>
                Export CSV
            </a>
        </div>
    </div>

    <form id="bulk-denda-form" method="POST" action="{{ route('admin.denda.update') }}" class="hidden">
        @csrf
        <input type="hidden" name="page" value="{{ $tools->currentPage() }}">
        <div id="bulk-denda-inputs"></div>
    </form>

    <div class="space-y-4">
        @foreach($tools as $tool)
            <x-card class="industrial-border" :padding="false">
                <form method="POST" action="{{ route('admin.denda.update') }}"
                    class="p-4 md:p-5 flex flex-col lg:flex-row gap-4 lg:gap-6 lg:items-center">
                    @csrf
                    <input type="hidden" name="tool_id" value="{{ $tool->id }}">
                    <input type="hidden" name="page" value="{{ $tools->currentPage() }}">

                    <div class="w-full lg:w-44 xl:w-56 flex-shrink-0">
                        <div
                            class="aspect-[4/3] rounded-lg bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 overflow-hidden">
                            @if($tool->gambar)
                                <img src="{{ asset($tool->gambar) }}" alt="{{ $tool->nama_alat }}"
                                    class="w-full h-full object-cover object-center">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                    <span class="material-symbols-outlined text-[48px]">construction</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex-1 min-w-0 space-y-3">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white leading-tight">{{ $tool->nama_alat }}
                            </h3>
                            <p class="text-xs uppercase tracking-widest text-gray-500 dark:text-gray-400 mt-1">
                                {{ $tool->category->nama_kategori ?? '-' }}
                            </p>
                        </div>

                        <div
                            class="inline-flex items-center gap-2 rounded-lg bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 px-3 py-2">
                            <span
                                class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Stok</span>
                            <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $tool->stok_total }}</span>
                        </div>
                    </div>

                    <div class="w-full lg:w-72 xl:w-80 space-y-3">
                        <div class="space-y-2">
                            <label for="denda_{{ $tool->id }}"
                                class="block text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Denda
                                / Hari</label>
                            <input id="denda_{{ $tool->id }}" type="number" name="denda_per_hari" data-denda-input
                                data-tool-id="{{ $tool->id }}"
                                value="{{ old('denda_per_hari', $tool->denda_per_hari ?? 5000) }}" min="0" step="1000"
                                class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm px-3 py-2 focus:border-primary focus:ring-primary">
                        </div>

                        <button type="submit"
                            class="w-full px-4 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-lg font-medium transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">save</span>
                            Simpan Denda
                        </button>
                    </div>
                </form>
            </x-card>
        @endforeach
    </div>

    @if($tools->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $tools->links('pagination::tailwind') }}
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const saveAllButton = document.getElementById('save-all-denda-btn');
            const bulkForm = document.getElementById('bulk-denda-form');
            const bulkInputs = document.getElementById('bulk-denda-inputs');
            if (!saveAllButton || !bulkForm || !bulkInputs) return;

            saveAllButton.addEventListener('click', function () {
                const dendaInputs = document.querySelectorAll('[data-denda-input][data-tool-id]');
                bulkInputs.innerHTML = '';

                dendaInputs.forEach(function (input) {
                    const toolId = input.getAttribute('data-tool-id');
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = `tools[${toolId}][denda_per_hari]`;
                    hiddenInput.value = input.value;
                    bulkInputs.appendChild(hiddenInput);
                });

                bulkForm.submit();
            });
        });
    </script>
@endsection