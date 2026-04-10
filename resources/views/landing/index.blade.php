@extends('layouts.landing')

@section('title', 'Beranda - LabPinjam')

@section('content')

    @php
        $landingReactProps = [
            'totalTools' => $totalTools,
            'availableTools' => $availableTools,
            'toolsUrl' => route('landing.tools'),
            'stepsAnchor' => '#cara-peminjaman',
            'latestTools' => $latestTools->map(function ($tool) {
                return [
                    'id' => $tool->id,
                    'name' => $tool->nama_alat,
                    'image' => $tool->gambar ? asset($tool->gambar) : null,
                    'category' => $tool->category->nama_kategori ?? 'Umum',
                    'status' => $tool->status,
                ];
            })->values(),
            'featuredTools' => $featuredTools->map(function ($tool) {
                return [
                    'id' => $tool->id,
                    'name' => $tool->nama_alat,
                    'image' => $tool->gambar ? asset($tool->gambar) : null,
                    'category' => $tool->category->nama_kategori ?? 'Umum',
                    'status' => $tool->status,
                    'stock' => $tool->stok,
                    'stockTotal' => $tool->stok_total,
                    'borrowUrl' => route('landing.pinjam', $tool->id),
                ];
            })->values(),
        ];
    @endphp

    <div id="landing-react-root"></div>

    <script>
        window.__LANDING_REACT_PROPS__ = @js($landingReactProps);
    </script>

    @vite('resources/js/landing-react.jsx')
@endsection