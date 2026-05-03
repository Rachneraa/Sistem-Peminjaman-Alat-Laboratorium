<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LabPinjam — Daftar Alat Lab</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>

:root {
  --sky-top:  #4ec3f7;
  --sky-mid:  #7dd3f8;
  --sky-bot:  #b3e8fb;
  --grass:    #6dcc6d;
  --grass-d:  #4aab4a;
  --sun:      #FFD93D;
  --coral:    #FF6B6B;
  --mint:     #00CBA9;
  --lavender: #C084FC;
  --peach:    #FFA552;
  --white:    #ffffff;
  --ink:      #1a2340;
  --bg:       #f6fbff;
  --card-bg:  rgba(255,255,255,0.75);
  --border:   rgba(0,0,0,.07);
  --rx-xl:    26px;
  --rx-lg:    18px;
  --rx-md:    12px;
  --font-h:   'Fredoka One', cursive;
  --font-b:   'Nunito', sans-serif;
  --ease-spring: cubic-bezier(.34,1.56,.64,1);
}

*,*::before,*::after { box-sizing:border-box; margin:0; padding:0; }
html { scroll-behavior:smooth; }
body { font-family:var(--font-b); background:var(--bg); color:var(--ink); overflow-x:hidden; }
a    { text-decoration:none; color:inherit; }

/* ── NAV ─────────────────────────────────── */
nav {
  position:fixed; top:0; left:0; right:0; z-index:200;
  display:flex; align-items:center; justify-content:space-between;
  padding:0 5vw; height:64px;
  background:rgba(78,195,247,.78);
  backdrop-filter:blur(20px);
  border-bottom:2px solid rgba(255,255,255,.35);
}
.logo {
  font-family:var(--font-h); font-size:1.45rem; color:#fff;
  display:flex; align-items:center; gap:9px;
  text-shadow:0 2px 8px rgba(0,0,0,.12);
}
.logo-dot {
  width:34px; height:34px; border-radius:10px;
  background:#fff; display:grid; place-items:center;
  font-size:1.2rem; box-shadow:0 4px 14px rgba(0,0,0,.1);
}
.nav-links { display:flex; gap:28px; }
.nav-links a {
  font-size:.9rem; font-weight:700; color:#fff;
  text-shadow:0 1px 4px rgba(0,0,0,.15); transition:opacity .2s;
}
.nav-links a:hover { opacity:.7; }
.nav-links a.active {
  background:rgba(255,255,255,.25); padding:6px 16px;
  border-radius:100px;
}
.btn-nav {
  padding:10px 24px; border-radius:100px;
  background:#fff; color:var(--sky-top);
  font-family:var(--font-b); font-size:.9rem; font-weight:800;
  border:none; cursor:pointer;
  box-shadow:0 4px 14px rgba(0,0,0,.1);
  transition:transform .2s var(--ease-spring), box-shadow .2s;
}
.btn-nav:hover { transform:translateY(-2px) scale(1.04); box-shadow:0 8px 22px rgba(0,0,0,.16); }

/* ── HERO BANNER ─────────────────────────── */
.hero-banner {
  min-height:260px;
  background:linear-gradient(180deg, var(--sky-top) 0%, var(--sky-mid) 70%, var(--sky-bot) 100%);
  display:flex; flex-direction:column; align-items:center; justify-content:center;
  padding:100px 5vw 60px;
  text-align:center; position:relative; overflow:hidden;
}
.hero-banner::after {
  content:'';
  position:absolute; bottom:0; left:0; right:0; height:40px;
  background:var(--bg);
  clip-path:ellipse(55% 100% at 50% 100%);
}
/* clouds */
.hcloud { position:absolute; opacity:.8; animation:drift linear infinite; }
.hcloud svg { fill:#fff; filter:drop-shadow(0 4px 12px rgba(0,0,0,.06)); }
.hc1 { top:10%; left:-4%; animation-duration:26s; }
.hc2 { top:18%; right:-3%; animation-duration:20s; animation-direction:reverse; }
/* floating emojis */
.hef {
  position:absolute; font-size:clamp(2rem,4vw,4rem);
  animation:floatBob 4s ease-in-out infinite;
  filter:drop-shadow(0 6px 14px rgba(0,0,0,.12));
  user-select:none; pointer-events:none;
}
.hef1 { top:20%; left:8%;  animation-delay:0s; }
.hef2 { top:25%; right:7%; animation-delay:.9s; }
.hef3 { bottom:28%; left:15%; animation-delay:1.7s; font-size:clamp(1.4rem,2.5vw,3rem); }
.hef4 { bottom:20%; right:12%; animation-delay:2.2s; font-size:clamp(1.4rem,2.5vw,2.8rem); }

.hero-tag {
  display:inline-block; padding:5px 18px; border-radius:100px;
  background:rgba(255,255,255,.3); backdrop-filter:blur(8px);
  font-size:.78rem; font-weight:900; letter-spacing:.1em; text-transform:uppercase;
  color:#fff; margin-bottom:14px;
  border:1.5px solid rgba(255,255,255,.4);
}
.hero-banner h1 {
  font-family:var(--font-h); font-size:clamp(2.2rem,5vw,3.8rem);
  color:#fff; letter-spacing:.5px;
  text-shadow:0 4px 20px rgba(0,0,0,.14);
  position:relative; z-index:1;
}
.hero-banner p {
  font-size:1rem; font-weight:700; color:rgba(255,255,255,.88);
  max-width:500px; margin:12px auto 0;
  text-shadow:0 2px 8px rgba(0,0,0,.1);
  position:relative; z-index:1;
}

@keyframes drift {
  0%  {transform:translateX(0) translateY(0);}
  25% {transform:translateX(18px) translateY(-7px);}
  50% {transform:translateX(5px) translateY(5px);}
  75% {transform:translateX(-10px) translateY(-4px);}
  100%{transform:translateX(0) translateY(0);}
}
@keyframes floatBob {
  0%,100%{transform:translateY(0) rotate(-3deg);}
  50%    {transform:translateY(-16px) rotate(4deg);}
}

/* ── SEARCH / FILTER BAR ─────────────────── */
.toolbar {
  display:flex; gap:12px; flex-wrap:wrap; align-items:center;
  padding:28px 5vw 0;
}
.search-wrap {
  flex:1; min-width:220px; position:relative;
}
.search-wrap input {
  width:100%; padding:14px 18px 14px 46px;
  border-radius:var(--rx-lg); border:2.5px solid rgba(78,195,247,.3);
  background:#fff; font-family:var(--font-b); font-size:.95rem; font-weight:600;
  color:var(--ink); outline:none;
  box-shadow:0 4px 16px rgba(78,195,247,.1);
  transition:border-color .2s, box-shadow .2s;
}
.search-wrap input:focus {
  border-color:var(--sky-top);
  box-shadow:0 4px 20px rgba(78,195,247,.22);
}
.search-wrap input::placeholder { color:rgba(26,35,64,.35); }
.search-icon {
  position:absolute; left:15px; top:50%; transform:translateY(-50%);
  font-size:1.1rem; pointer-events:none;
}
.toolbar select {
  padding:14px 38px 14px 18px;
  border-radius:var(--rx-lg); border:2.5px solid rgba(78,195,247,.3);
  background:#fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%234ec3f7' stroke-width='2' fill='none' stroke-linecap='round'/%3E%3C/svg%3E") no-repeat right 14px center;
  font-family:var(--font-b); font-size:.9rem; font-weight:700;
  color:var(--ink); outline:none; cursor:pointer;
  appearance:none;
  box-shadow:0 4px 16px rgba(78,195,247,.1);
  transition:border-color .2s;
}
.toolbar select:focus { border-color:var(--sky-top); }

/* chip filters */
.chips-row {
  display:flex; gap:10px; flex-wrap:wrap; padding:18px 5vw 8px;
}
.chip {
  padding:9px 20px; border-radius:100px;
  font-family:var(--font-b); font-size:.85rem; font-weight:800;
  border:2.5px solid transparent;
  cursor:pointer; transition:all .22s var(--ease-spring);
  background:#fff; color:var(--ink);
  box-shadow:0 3px 10px rgba(0,0,0,.06);
}
.chip:hover { transform:scale(1.06) translateY(-2px); box-shadow:0 8px 20px rgba(0,0,0,.1); }
.chip.active-all     { background:var(--ink);    color:#fff; border-color:var(--ink); }
.chip.active-ukur    { background:var(--coral);  color:#fff; border-color:var(--coral); }
.chip.active-gelas   { background:var(--mint);   color:#fff; border-color:var(--mint); }
.chip.active-pemanas { background:var(--peach);  color:#fff; border-color:var(--peach); }
.chip.active-optik   { background:var(--lavender);color:#fff;border-color:var(--lavender); }
.chip.active-tersedia{ background:var(--grass);  color:#fff; border-color:var(--grass); }

/* ── RESULT INFO ─────────────────────────── */
.result-info {
  padding:10px 5vw 0;
  font-size:.88rem; font-weight:700; color:rgba(26,35,64,.45);
}
.result-info span { color:var(--coral); }

/* ── GRID ────────────────────────────────── */
.grid-section { padding:20px 5vw 40px; }
.catalog-grid {
  display:grid;
  grid-template-columns:repeat(5, 1fr);
  gap:22px;
}

/* ── ALAT CARD ───────────────────────────── */
.alat-card {
  background:#fff; border-radius:var(--rx-xl);
  overflow:hidden; border:3px solid #eef6ff;
  box-shadow:0 6px 24px rgba(0,0,0,.06);
  transition:transform .3s var(--ease-spring), box-shadow .3s, border-color .2s;
  cursor:pointer; position:relative;
}
.alat-card:hover {
  transform:translateY(-10px) scale(1.02);
  box-shadow:0 24px 50px rgba(0,0,0,.13);
  border-color:var(--sun);
}
.alat-card.unavailable { opacity:.75; }
.alat-card.unavailable:hover { transform:translateY(-4px) scale(1.01); border-color:#fdd; }

/* image area */
.card-img {
  height:200px; position:relative; overflow:hidden;
  display:flex; align-items:center; justify-content:center;
}
.card-img img {
  width:100%; height:100%; object-fit:cover;
  transition:transform .4s;
}
.alat-card:hover .card-img img { transform:scale(1.07); }
/* emoji fallback */
.card-emoji {
  font-size:5rem;
  filter:drop-shadow(0 8px 16px rgba(0,0,0,.1));
  transition:transform .3s var(--ease-spring);
}
.alat-card:hover .card-emoji { transform:scale(1.15) rotate(-5deg); }

/* gradient overlay on image */
.card-img::after {
  content:''; position:absolute; bottom:0; left:0; right:0; height:60px;
  background:linear-gradient(to top, rgba(255,255,255,.6), transparent);
  pointer-events:none;
}

/* category badge */
.cat-badge {
  position:absolute; top:12px; left:12px; z-index:2;
  padding:5px 13px; border-radius:100px;
  font-size:.68rem; font-weight:900; text-transform:uppercase; letter-spacing:.08em;
  box-shadow:0 3px 10px rgba(0,0,0,.1);
}
.cat-ukur    { background:var(--sun);    color:var(--ink); }
.cat-gelas   { background:var(--mint);   color:#fff; }
.cat-pemanas { background:var(--coral);  color:#fff; }
.cat-optik   { background:var(--lavender); color:#fff; }

/* stock badge */
.stock-badge {
  position:absolute; top:12px; right:12px; z-index:2;
  width:32px; height:32px; border-radius:50%;
  background:#fff; display:grid; place-items:center;
  font-size:.7rem; font-weight:900; box-shadow:0 3px 10px rgba(0,0,0,.12);
  color:var(--coral);
}
.stock-badge.ok { color:var(--mint); }

/* body */
.card-body { padding:18px 20px 20px; }
.card-name { font-family:var(--font-h); font-size:1.15rem; margin-bottom:7px; letter-spacing:.3px; }
.card-avail {
  font-size:.82rem; font-weight:700;
  display:flex; align-items:center; gap:7px;
  margin-bottom:16px; color:rgba(26,35,64,.45);
}
.dot {
  width:8px; height:8px; border-radius:50%; flex-shrink:0;
}
.dot-g { background:#22c55e; box-shadow:0 0 6px rgba(34,197,94,.5); }
.dot-r { background:#ef4444; box-shadow:0 0 6px rgba(239,68,68,.4); }
.dot-y { background:var(--peach); box-shadow:0 0 6px rgba(255,165,82,.4); }

.btn-pinjam {
  width:100%; padding:13px; border-radius:var(--rx-md);
  font-family:var(--font-b); font-size:.9rem; font-weight:900;
  border:none; cursor:pointer;
  transition:transform .2s var(--ease-spring), box-shadow .2s;
}
.btn-pinjam.avail {
  background:linear-gradient(135deg, var(--coral), #ff8c5a);
  color:#fff; box-shadow:0 6px 20px rgba(255,107,107,.3);
}
.btn-pinjam.avail:hover { transform:scale(1.03) translateY(-2px); box-shadow:0 10px 28px rgba(255,107,107,.42); }
.btn-pinjam.low {
  background:linear-gradient(135deg, var(--peach), #ffca82);
  color:var(--ink); box-shadow:0 6px 20px rgba(255,165,82,.25);
}
.btn-pinjam.low:hover { transform:scale(1.03) translateY(-2px); }
.btn-pinjam.gone {
  background:#f0f0f0; color:#aaa; cursor:not-allowed;
}

/* ── PAGINATION ──────────────────────────── */
.pagination {
  display:flex; align-items:center; justify-content:center;
  gap:10px; padding:20px 5vw 60px;
  flex-wrap:wrap;
}
.page-info {
  font-size:.9rem; font-weight:700; color:rgba(26,35,64,.45);
  padding:0 6px;
}
.btn-page {
  padding:12px 28px; border-radius:100px;
  font-family:var(--font-b); font-size:.9rem; font-weight:800;
  border:2.5px solid var(--border); background:#fff; color:var(--ink);
  cursor:pointer; transition:all .2s var(--ease-spring);
  box-shadow:0 4px 12px rgba(0,0,0,.06);
}
.btn-page:hover { border-color:var(--sky-top); transform:scale(1.05); }
.btn-page.disabled { opacity:.35; cursor:not-allowed; transform:none !important; }
.page-dots { display:flex; gap:8px; align-items:center; }
.pdot {
  width:36px; height:36px; border-radius:50%;
  display:grid; place-items:center;
  font-size:.88rem; font-weight:800;
  border:2.5px solid var(--border); background:#fff;
  cursor:pointer; transition:all .2s var(--ease-spring);
}
.pdot:hover { border-color:var(--sky-top); transform:scale(1.1); }
.pdot.active { background:var(--coral); border-color:var(--coral); color:#fff; box-shadow:0 4px 14px rgba(255,107,107,.35); }

/* ── EMPTY STATE ─────────────────────────── */
.empty-state {
  display:none; flex-direction:column; align-items:center;
  justify-content:center; padding:80px 20px; text-align:center;
}
.empty-state.show { display:flex; }
.empty-emoji { font-size:5rem; margin-bottom:20px; animation:floatBob 3s ease-in-out infinite; }
.empty-state h3 { font-family:var(--font-h); font-size:1.8rem; margin-bottom:8px; }
.empty-state p  { color:rgba(26,35,64,.45); font-weight:600; }

/* ── FOOTER ──────────────────────────────── */
.footer-grass { background:linear-gradient(180deg, var(--grass) 0%, var(--grass-d) 100%); position:relative; }
.footer-grass-svg { display:block; }
.footer-inner {
  display:grid; grid-template-columns:2fr 1fr 1fr 1fr; gap:40px;
  padding:50px 5vw 40px;
}
.footer-brand .logo { color:#fff; margin-bottom:12px; }
.footer-brand p { font-size:.875rem; font-weight:600; color:rgba(255,255,255,.7); max-width:280px; line-height:1.7; }
.footer-col h4 { font-family:var(--font-h); font-size:1.1rem; color:#fff; margin-bottom:16px; }
.footer-col a  { display:block; font-size:.875rem; font-weight:600; color:rgba(255,255,255,.65); margin-bottom:10px; transition:color .2s; }
.footer-col a:hover { color:#fff; }
.footer-contact { display:flex; gap:8px; font-size:.875rem; font-weight:600; color:rgba(255,255,255,.65); margin-bottom:10px; }
.footer-bottom { padding:22px 5vw; border-top:2px dashed rgba(255,255,255,.2); text-align:center; font-size:.82rem; font-weight:700; color:rgba(255,255,255,.5); }

/* ── REVEAL ──────────────────────────────── */
.reveal { opacity:0; transform:translateY(28px); transition:opacity .6s, transform .6s cubic-bezier(.25,.8,.25,1); }
.reveal.up { opacity:1; transform:translateY(0); }

/* ── RESPONSIVE ──────────────────────────── */
@media(max-width:860px){
  .nav-links { display:none; }
  .footer-inner { grid-template-columns:1fr 1fr; }
  .catalog-grid { grid-template-columns:repeat(3, 1fr); }
}
@media(max-width:560px){
  .toolbar { flex-direction:column; }
  .search-wrap, .toolbar select { width:100%; }
  .footer-inner { grid-template-columns:1fr; }
  .catalog-grid { grid-template-columns:repeat(2, 1fr); }
}
@media(max-width:400px){
  .catalog-grid { grid-template-columns:1fr; }
}
</style>
</head>
<body>

<!-- ── NAV ────────────────────────────────── -->
<nav>
  <div class="logo">
    <div class="logo-dot">🔬</div>
    LabPinjam
  </div>
  <div class="nav-links">
    <a href="{{ route('landing.index') }}">Beranda</a>
    <a href="{{ route('landing.tools') }}" class="active">Alat Lab</a>
    <a href="{{ route('landing.index') }}#cara">Cara Peminjaman</a>
  </div>
  @auth
    @if(auth()->user()->role === 'admin')
      <a href="{{ route('admin.dashboard') }}" class="btn-nav" style="text-decoration:none;display:inline-block;">Dashboard 🚀</a>
    @elseif(auth()->user()->role === 'petugas')
      <a href="{{ route('petugas.dashboard') }}" class="btn-nav" style="text-decoration:none;display:inline-block;">Dashboard 🚀</a>
    @else
      <a href="{{ route('peminjam.dashboard') }}" class="btn-nav" style="text-decoration:none;display:inline-block;">Dashboard 🚀</a>
    @endif
  @else
    <a href="{{ route('login') }}" class="btn-nav" style="text-decoration:none;display:inline-block;">Mulai Sekarang 🚀</a>
  @endauth
</nav>

<!-- ── HERO BANNER ────────────────────────── -->
<div class="hero-banner">
  <div class="hcloud hc1"><svg viewBox="0 0 220 80" width="220"><ellipse cx="90" cy="55" rx="78" ry="26"/><ellipse cx="110" cy="38" rx="54" ry="30"/><ellipse cx="158" cy="52" rx="58" ry="24"/></svg></div>
  <div class="hcloud hc2"><svg viewBox="0 0 180 70" width="180"><ellipse cx="70" cy="48" rx="60" ry="22"/><ellipse cx="92" cy="33" rx="46" ry="26"/><ellipse cx="132" cy="46" rx="46" ry="20"/></svg></div>
  <div class="hef hef1">🧪</div>
  <div class="hef hef2">⚗️</div>
  <div class="hef hef3">🌡️</div>
  <div class="hef hef4">⚖️</div>
  <div class="hero-tag">🔬 Katalog Lengkap</div>
  <h1>Daftar Alat Lab</h1>
  <p>Jelajahi berbagai alat laboratorium yang tersedia untuk dipinjam.<br>Semua alat dirawat dengan baik dan siap digunakan! ✨</p>
</div>

<!-- ── TOOLBAR ────────────────────────────── -->
<div class="toolbar">
  <div class="search-wrap">
    <span class="search-icon">🔍</span>
    <input type="text" id="searchInput" placeholder="Cari alat... (contoh: neraca, buret)" value="{{ $search ?? '' }}">
  </div>
  <select id="categorySelect">
    <option value="">Semua Kategori</option>
    @foreach($categories as $category)
    <option value="{{ $category->id }}" {{ $categoryId == $category->id ? 'selected' : '' }}>
      {{ $category->nama_kategori }}
    </option>
    @endforeach
  </select>
  <select id="sortSelect">
    <option value="">Urutkan</option>
    <option value="name-asc" {{ $sort == 'name-asc' ? 'selected' : '' }}>A → Z</option>
    <option value="stock-high" {{ $sort == 'stock-high' ? 'selected' : '' }}>Stok Terbanyak</option>
    <option value="available" {{ $sort == 'available' ? 'selected' : '' }}>Tersedia Dulu</option>
  </select>
</div>

<!-- ── RESULT INFO ────────────────────────── -->
<div class="result-info">Menampilkan <span>{{ $tools->total() }}</span> alat</div>

<!-- ── GRID ──────────────────────────────── -->
<section class="grid-section">
  <div class="catalog-grid" id="catalogGrid">
    @forelse($tools as $tool)
    <div class="alat-card reveal {{ $tool->stok == 0 ? 'unavailable' : '' }}">
      <div class="card-img" style="background:linear-gradient(135deg,#fff9e6,#e8f8ff)">
        <span class="cat-badge cat-ukur">{{ $tool->category->nama_kategori ?? 'Alat Lab' }}</span>
        <div class="stock-badge {{ $tool->stok > 0 ? 'ok' : '' }}">{{ $tool->stok }}</div>
        @if($tool->gambar)
          @php
            $imagePath = str_replace('images/tools/', '', $tool->gambar);
          @endphp
          <img src="{{ asset('images/tools/' . $imagePath) }}" alt="{{ $tool->nama_alat }}" style="width:100%;height:100%;object-fit:cover;position:absolute;top:0;left:0;">
        @else
          <span class="card-emoji">🔬</span>
        @endif
      </div>
      <div class="card-body">
        <div class="card-name">{{ $tool->nama_alat }}</div>
        @if($tool->stok > 0)
          <div class="card-avail">
            <span class="dot {{ $tool->stok > 5 ? 'dot-g' : 'dot-y' }}"></span>
            {{ $tool->stok }} {{ $tool->stok > 5 ? 'Tersedia' : 'Tersisa — Cepat!' }}
          </div>
          <a href="{{ route('login') }}" class="btn-pinjam {{ $tool->stok > 5 ? 'avail' : 'low' }}" style="display:block;text-align:center;text-decoration:none;">Pinjam Sekarang →</a>
        @else
          <div class="card-avail"><span class="dot dot-r"></span> Habis Dipinjam</div>
          <button class="btn-pinjam gone" disabled>Tidak Tersedia 😢</button>
        @endif
      </div>
    </div>
    @empty
    <div class="empty-state show" style="grid-column:1/-1">
      <div class="empty-emoji">🔍</div>
      <h3>Alat nggak ditemukan</h3>
      <p>Coba kata kunci lain atau hapus filter yang aktif ya!</p>
    </div>
    @endforelse
  </div>
</section>

<!-- ── PAGINATION ─────────────────────────── -->
@if($tools->hasPages())
<div class="pagination">
  @if($tools->onFirstPage())
    <button class="btn-page disabled">← Sebelumnya</button>
  @else
    <a href="{{ $tools->previousPageUrl() }}" class="btn-page">← Sebelumnya</a>
  @endif
  
  <div class="page-dots">
    @foreach(range(1, $tools->lastPage()) as $page)
      @if($page == $tools->currentPage())
        <button class="pdot active">{{ $page }}</button>
      @else
        <a href="{{ $tools->url($page) }}" class="pdot">{{ $page }}</a>
      @endif
    @endforeach
  </div>
  
  <div class="page-info">Halaman {{ $tools->currentPage() }} dari {{ $tools->lastPage() }}</div>
  
  @if($tools->hasMorePages())
    <a href="{{ $tools->nextPageUrl() }}" class="btn-page">Berikutnya →</a>
  @else
    <button class="btn-page disabled">Berikutnya →</button>
  @endif
</div>
@endif

<!-- ── FOOTER ─────────────────────────────── -->
<footer class="footer-grass">
  <svg class="footer-grass-svg" viewBox="0 0 1440 80" preserveAspectRatio="none" height="80">
    <path fill="#6dcc6d" d="M0,40 C120,80 240,0 360,40 C480,80 600,0 720,40 C840,80 960,0 1080,40 C1200,80 1320,0 1440,40 L1440,80 L0,80 Z"/>
  </svg>
  <div class="footer-inner">
    <div class="footer-brand">
      <div class="logo" style="color:#fff">
        <div class="logo-dot" style="background:rgba(255,255,255,.2)">🔬</div>
        LabPinjam
      </div>
      <p>Platform peminjaman alat laboratorium terintegrasi untuk mendukung riset dan praktikum yang lebih efisien.</p>
    </div>
    <div class="footer-col">
      <h4>Navigasi</h4>
      <a href="{{ route('landing.index') }}">🏠 Beranda</a>
      <a href="{{ route('landing.tools') }}">🔬 Daftar Alat</a>
      <a href="{{ route('landing.index') }}#cara">📋 Cara Peminjaman</a>
    </div>
    <div class="footer-col">
      <h4>Info</h4>
      <a href="#">📜 Syarat & Ketentuan</a>
      <a href="#">❓ FAQ</a>
      <a href="#">🔒 Kebijakan Privasi</a>
    </div>
    <div class="footer-col">
      <h4>Kontak</h4>
      <div class="footer-contact">✉️ labpinjam@university.ac.id</div>
      <div class="footer-contact">📞 +62 22 1234 5678</div>
      <div class="footer-contact">📍 Gedung Lab Lt. 2, Kampus Utama</div>
    </div>
  </div>
  <div class="footer-bottom">© 2026 LabPinjam — Dibuat dengan 💚 untuk mahasiswa Indonesia</div>
</footer>

<script>
// ── FILTER & SEARCH ─────────────────────────
document.getElementById('searchInput').addEventListener('input', applyFilters);
document.getElementById('categorySelect').addEventListener('change', applyFilters);
document.getElementById('sortSelect').addEventListener('change', applyFilters);

function applyFilters() {
  const search = document.getElementById('searchInput').value;
  const category = document.getElementById('categorySelect').value;
  const sort = document.getElementById('sortSelect').value;
  
  const url = new URL(window.location.href);
  url.searchParams.set('search', search);
  url.searchParams.set('category', category);
  url.searchParams.set('sort', sort);
  
  window.location.href = url.toString();
}

// ── SCROLL REVEAL ────────────────────────────
const io = new IntersectionObserver(entries => {
  entries.forEach((e, idx) => {
    if (e.isIntersecting) {
      setTimeout(() => e.target.classList.add('up'), idx * 70);
    }
  });
}, { threshold: 0.1 });
document.querySelectorAll('.reveal').forEach(el => io.observe(el));
</script>
</body>
</html>