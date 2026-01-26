@props(['status', 'type' => 'borrowing'])

@php
    $classes = '';
    $text = '';
    
    if ($type === 'borrowing') {
        $classes = match($status) {
            'disetujui' => 'bg-green-500/20 text-green-400',
            'ditolak' => 'bg-red-500/20 text-red-400',
            'dikembalikan' => 'bg-blue-500/20 text-blue-400',
            'menunggu' => 'bg-yellow-500/20 text-yellow-400',
            default => 'bg-gray-500/20 text-gray-400',
        };
        $text = ucfirst($status);
    } elseif ($type === 'tool') {
        $classes = match($status) {
            'tersedia' => 'bg-green-500/20 text-green-400',
            'dipinjam' => 'bg-blue-500/20 text-blue-400',
            'rusak' => 'bg-red-500/20 text-red-400',
            'perbaikan' => 'bg-yellow-500/20 text-yellow-400',
            default => 'bg-gray-500/20 text-gray-400',
        };
        $text = ucfirst($status);
    }
@endphp

<span class="px-2 py-1 text-xs font-semibold rounded-full {{ $classes }}">
    {{ $text }}
</span>

