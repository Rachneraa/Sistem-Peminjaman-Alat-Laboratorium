@extends('layouts.app')

@section('title', 'Edit Alat')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white uppercase tracking-wider">Edit Alat</h1>
            <div class="h-1 w-16 bg-primary mt-2"></div>
        </div>

        <div
            class="bg-white dark:bg-panel-dark border border-gray-200 dark:border-white/5 rounded-xl p-8 industrial-border">
            <form method="POST" action="{{ route('admin.tools.update', $tool) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label
                        class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Nama
                        Alat</label>
                    <input type="text" name="nama_alat" value="{{ old('nama_alat', $tool->nama_alat) }}"
                        class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all"
                        required autofocus>
                </div>

                <div class="mb-6">
                    <label
                        class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Kategori</label>
                    <select name="kategori_id"
                        class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all"
                        required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $tool->kategori_id == $category->id ? 'selected' : '' }}>
                                {{ $category->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <label
                        class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Kategori
                        Kondisi</label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label
                                class="block text-[10px] text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">Stok
                                Baik</label>
                            <input type="number" name="stok" value="{{ old('stok', $tool->stok) }}" min="0"
                                class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all"
                                required>
                        </div>
                        <div>
                            <label
                                class="block text-[10px] text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">Stok
                                Rusak</label>
                            <input type="number" name="stok_rusak" value="{{ old('stok_rusak', $tool->stok_rusak) }}"
                                min="0"
                                class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all"
                                required>
                        </div>
                        <div>
                            <label
                                class="block text-[10px] text-gray-500 dark:text-gray-400 mb-1 uppercase tracking-wider">Stok
                                Diperbaiki</label>
                            <input type="number" name="stok_perbaikan"
                                value="{{ old('stok_perbaikan', $tool->stok_perbaikan ?? 0) }}" min="0"
                                class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all"
                                required>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <label
                        class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Harga Asli (Rp)</label>
                    <input type="number" name="harga_asli" value="{{ old('harga_asli', $tool->harga_asli ?? 0) }}" min="0" step="0.01"
                        class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all"
                        required placeholder="Masukkan harga asli alat...">
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-2 uppercase tracking-wide">Harga ini digunakan untuk menghitung denda kerusakan</p>
                </div>

                <div class="mb-6">
                    <label
                        class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Denda Per Hari (Rp)</label>
                    <input type="number" name="denda_per_hari" value="{{ old('denda_per_hari', $tool->denda_per_hari ?? 5000) }}" min="0" step="0.01"
                        class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all"
                        required placeholder="Masukkan denda per hari...">
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-2 uppercase tracking-wide">Denda keterlambatan per hari</p>
                </div>

                <div class="mb-6">
                    <label
                        class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Status</label>
                    <select name="status"
                        class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg focus:ring-primary focus:border-primary block p-3 transition-all"
                        required>
                        <option value="tersedia" {{ old('status', $tool->status) == 'tersedia' ? 'selected' : '' }}>Tersedia
                        </option>
                        <option value="dipinjam" {{ old('status', $tool->status) == 'dipinjam' ? 'selected' : '' }}>Dipinjam
                        </option>
                        <option value="rusak" {{ old('status', $tool->status) == 'rusak' ? 'selected' : '' }}>Rusak</option>
                        <option value="perbaikan" {{ old('status', $tool->status) == 'perbaikan' ? 'selected' : '' }}>
                            Perbaikan</option>
                    </select>
                </div>

                <div class="mb-8">
                    <label
                        class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-2 uppercase tracking-widest pl-1">Gambar
                        Alat</label>
                    @if($tool->gambar)
                        <div
                            class="mb-3 p-2 bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 rounded-lg inline-block">
                            <img src="{{ asset($tool->gambar) }}" alt="{{ $tool->nama_alat }}"
                                class="w-32 h-32 object-cover rounded-lg">
                            <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-2 text-center uppercase tracking-wide">
                                Gambar saat ini</p>
                        </div>
                    @endif
                    <input type="file" name="gambar" accept="image/*"
                        class="w-full bg-gray-50 dark:bg-background-dark border border-gray-300 dark:border-gray-700 text-gray-900 dark:text-white text-sm rounded-lg file:mr-4 file:py-2.5 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-gray-200 file:text-gray-700 dark:file:bg-gray-700 dark:file:text-gray-300 hover:file:bg-gray-300 dark:hover:file:bg-gray-600 transition-all">
                    <p class="text-[10px] text-gray-500 dark:text-gray-400 mt-2 uppercase tracking-wide">Format: JPG, PNG,
                        GIF (Max: 2MB). Kosongkan jika tidak ingin mengubah gambar.</p>
                </div>

                <div class="flex space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700/50">
                    <button type="submit"
                        class="px-6 py-2.5 bg-primary hover:bg-primary/90 text-white rounded-lg font-medium transition-all flex items-center gap-2 shadow-lg shadow-primary/20">
                        <span class="material-symbols-outlined text-[20px]">update</span>
                        Update
                    </button>
                    <a href="{{ route('admin.tools.index') }}"
                        class="px-6 py-2.5 bg-transparent border border-gray-300 dark:border-gray-600 text-gray-600 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white transition-all flex items-center gap-2 font-medium">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection