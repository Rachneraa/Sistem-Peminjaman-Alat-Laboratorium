<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LabPinjam — Pinjam Alat Lab Gampang!</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
<style>

/* ══ TOKENS ══════════════════════════════════════════ */
:root {
  --sky-top:   #4ec3f7;
  --sky-mid:   #7dd3f8;
  --sky-bot:   #b3e8fb;
  --grass:     #6dcc6d;
  --grass-d:   #4aab4a;
  --sun:       #FFD93D;
  --coral:     #FF6B6B;
  --mint:      #00CBA9;
  --lavender:  #C084FC;
  --peach:     #FFA552;
  --white:     #ffffff;
  --ink:       #1a2340;
  --card-bg:   rgba(255,255,255,0.70);
  --radius-xl: 28px;
  --radius-lg: 20px;
  --radius-md: 14px;
  --font-h:    'Fredoka One', cursive;
  --font-b:    'Nunito', sans-serif;
}

/* ══ RESET ═══════════════════════════════════════════ */
*,*::before,*::after { box-sizing: border-box; margin:0; padding:0; }
html { scroll-behavior: smooth; }
body {
  font-family: var(--font-b);
  background: var(--sky-top);
  color: var(--ink);
  overflow-x: hidden;
}

/* ══ NAV ════════════════════════════════════════════ */
nav {
  position: fixed; top: 0; left: 0; right: 0; z-index: 200;
  display: flex; align-items: center; justify-content: space-between;
  padding: 0 5vw; height: 64px;
  background: rgba(78,195,247,.75);
  backdrop-filter: blur(20px);
  border-bottom: 2px solid rgba(255,255,255,.35);
}
.logo {
  font-family: var(--font-h);
  font-size: 1.5rem; color: var(--white);
  display: flex; align-items: center; gap: 8px;
  letter-spacing: .5px;
  text-shadow: 0 2px 8px rgba(0,0,0,.15);
}
.logo-dot {
  width: 34px; height: 34px; border-radius: 10px;
  background: var(--white);
  display: grid; place-items: center;
  font-size: 1.2rem;
  box-shadow: 0 4px 14px rgba(0,0,0,.12);
}
.nav-links { display: flex; gap: 28px; }
.nav-links a {
  font-size: .9rem; font-weight: 700; color: var(--white);
  text-shadow: 0 1px 4px rgba(0,0,0,.15);
  transition: opacity .2s;
}
.nav-links a:hover { opacity: .75; }
.btn-nav {
  padding: 10px 24px; border-radius: 100px;
  background: var(--white); color: var(--sky-top);
  font-family: var(--font-b); font-size: .9rem; font-weight: 800;
  border: none; cursor: pointer;
  box-shadow: 0 4px 14px rgba(0,0,0,.12);
  transition: transform .2s, box-shadow .2s;
}
.btn-nav:hover { transform: translateY(-2px) scale(1.04); box-shadow: 0 8px 22px rgba(0,0,0,.18); }

/* ══ HERO ════════════════════════════════════════════ */
.hero {
  min-height: 100vh;
  background: linear-gradient(180deg, var(--sky-top) 0%, var(--sky-mid) 55%, var(--sky-bot) 100%);
  position: relative; overflow: hidden;
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  padding: 100px 5vw 80px;
  text-align: center;
}

/* floating clouds SVG */
.cloud {
  position: absolute;
  opacity: .9;
  animation: drift linear infinite;
}
.cloud svg { fill: white; filter: drop-shadow(0 4px 16px rgba(0,0,0,.08)); }
.c1 { top: 12%; left: -8%; animation-duration: 28s; }
.c2 { top: 20%; right: -6%; animation-duration: 22s; animation-direction: reverse; }
.c3 { top: 40%; left: 5%; animation-duration: 34s; opacity:.6; }
.c4 { top: 30%; right: 8%; animation-duration: 26s; opacity:.7; }

@keyframes drift {
  0%   { transform: translateX(0) translateY(0); }
  25%  { transform: translateX(20px) translateY(-8px); }
  50%  { transform: translateX(5px) translateY(4px); }
  75%  { transform: translateX(-10px) translateY(-4px); }
  100% { transform: translateX(0) translateY(0); }
}

/* floating 3d emojis */
.emoji-float {
  position: absolute;
  font-size: clamp(2.5rem, 5vw, 5rem);
  animation: floatBob 4s ease-in-out infinite;
  user-select: none; pointer-events: none;
  filter: drop-shadow(0 8px 20px rgba(0,0,0,.15));
}
.ef1 { top: 8%;  left: 6%;  animation-delay: 0s;   font-size: clamp(2rem,4vw,4rem); }
.ef2 { top: 15%; right: 8%; animation-delay: .8s;  font-size: clamp(2.5rem,5vw,5rem); }
.ef3 { top: 50%; left: 3%;  animation-delay: 1.4s; font-size: clamp(1.8rem,3vw,3.5rem); }
.ef4 { top: 60%; right: 5%; animation-delay: 2s;   font-size: clamp(2rem,3.5vw,4rem); }
.ef5 { top: 75%; left: 10%; animation-delay: .5s;  font-size: clamp(1.5rem,2.5vw,3rem); }

@keyframes floatBob {
  0%,100% { transform: translateY(0) rotate(-3deg); }
  50%      { transform: translateY(-18px) rotate(3deg); }
}

.hero-badge {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 8px 20px; border-radius: 100px;
  background: rgba(255,255,255,.8);
  backdrop-filter: blur(10px);
  font-size: .85rem; font-weight: 800;
  color: var(--coral); letter-spacing: .04em;
  margin-bottom: 24px;
  animation: popIn .7s cubic-bezier(.34,1.56,.64,1) both;
  box-shadow: 0 4px 18px rgba(0,0,0,.1);
}
@keyframes popIn {
  from { opacity:0; transform: scale(.6); }
  to   { opacity:1; transform: scale(1); }
}

.hero-title {
  font-family: var(--font-h);
  font-size: clamp(3.2rem, 9vw, 7rem);
  line-height: 1.05;
  color: var(--white);
  text-shadow: 0 4px 20px rgba(0,0,0,.15);
  animation: fadeUp .8s .1s cubic-bezier(.25,.8,.25,1) both;
  letter-spacing: 1px;
}
.hero-title .line2 {
  color: var(--sun);
  -webkit-text-stroke: 2px rgba(200,160,0,.3);
  display: block;
}
.hero-title .line3 {
  color: #fff;
  display: block;
}

.hero-sub {
  font-size: clamp(1rem, 2vw, 1.3rem);
  font-weight: 700; color: rgba(255,255,255,.9);
  max-width: 480px; margin: 20px auto 36px;
  text-shadow: 0 2px 8px rgba(0,0,0,.12);
  animation: fadeUp .8s .2s cubic-bezier(.25,.8,.25,1) both;
}

.hero-ctas {
  display: flex; gap: 14px; justify-content: center; flex-wrap: wrap;
  animation: fadeUp .8s .3s cubic-bezier(.25,.8,.25,1) both;
}
.btn-big {
  padding: 18px 40px; border-radius: 100px;
  font-family: var(--font-b); font-size: 1.05rem; font-weight: 900;
  border: none; cursor: pointer;
  transition: transform .2s cubic-bezier(.34,1.56,.64,1), box-shadow .2s;
}
.btn-big:hover { transform: translateY(-4px) scale(1.04); }
.btn-yellow {
  background: var(--sun); color: var(--ink);
  box-shadow: 0 8px 28px rgba(255,217,61,.5);
}
.btn-yellow:hover { box-shadow: 0 14px 36px rgba(255,217,61,.6); }
.btn-white {
  background: var(--white); color: var(--sky-top);
  box-shadow: 0 8px 28px rgba(255,255,255,.35);
}
.btn-white:hover { box-shadow: 0 14px 36px rgba(255,255,255,.45); }

/* hero slides down arrow */
.scroll-hint {
  position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%);
  width: 44px; height: 44px; border-radius: 50%;
  background: rgba(255,255,255,.7);
  display: grid; place-items: center;
  font-size: 1.3rem;
  animation: bounce 2s ease-in-out infinite;
  cursor: pointer;
}
@keyframes bounce {
  0%,100% { transform: translateX(-50%) translateY(0); }
  50%      { transform: translateX(-50%) translateY(8px); }
}

@keyframes fadeUp {
  from { opacity:0; transform:translateY(30px); }
  to   { opacity:1; transform:translateY(0); }
}

/* ══ GRASS DIVIDER ═══════════════════════════════════ */
.grass-divider {
  position: relative; height: 120px; overflow: hidden;
  background: linear-gradient(180deg, var(--sky-bot) 0%, transparent 100%);
}
.grass-divider svg { position: absolute; bottom: 0; width: 100%; }

/* ══ STATS ROW ═══════════════════════════════════════ */
.stats-row {
  background: linear-gradient(135deg, var(--coral) 0%, #ff8c5a 100%);
  padding: 0 5vw;
  display: flex;
}
.stat-pill {
  flex: 1; text-align: center; padding: 40px 16px;
  border-right: 2px dashed rgba(255,255,255,.25);
}
.stat-pill:last-child { border-right: none; }
.stat-num {
  font-family: var(--font-h); font-size: 3rem; color: var(--white);
  line-height: 1; text-shadow: 0 3px 10px rgba(0,0,0,.12);
}
.stat-lbl { font-size: .85rem; font-weight: 700; color: rgba(255,255,255,.75); margin-top: 6px; }

/* ══ SECTION BASE ════════════════════════════════════ */
.section { padding: 90px 5vw; }
.sky-section { background: linear-gradient(180deg, var(--sky-bot) 0%, #e6f7ff 100%); }
.white-section { background: #fffdf6; }
.section-tag {
  display: inline-block;
  font-size: .8rem; font-weight: 900; letter-spacing: .12em;
  text-transform: uppercase; color: var(--coral);
  background: rgba(255,107,107,.1); border-radius: 100px;
  padding: 5px 16px; margin-bottom: 14px;
}
.section-title {
  font-family: var(--font-h);
  font-size: clamp(2rem, 4vw, 3.2rem);
  line-height: 1.15; letter-spacing: .5px;
  margin-bottom: 14px;
}
.section-desc {
  font-size: 1rem; font-weight: 600;
  color: rgba(26,35,64,.55);
  max-width: 500px;
}

/* ══ HOW IT WORKS ════════════════════════════════════ */
.hiw-grid {
  display: grid; grid-template-columns: repeat(3,1fr); gap: 20px;
  margin-top: 50px;
}
.hiw-card {
  background: var(--card-bg);
  backdrop-filter: blur(16px);
  border-radius: var(--radius-xl);
  padding: 36px 28px 30px;
  border: 2px solid rgba(255,255,255,.8);
  box-shadow: 0 8px 32px rgba(0,0,0,.07);
  transition: transform .3s cubic-bezier(.34,1.56,.64,1), box-shadow .3s;
  text-align: center;
}
.hiw-card:hover {
  transform: translateY(-10px) rotate(-1deg);
  box-shadow: 0 20px 48px rgba(0,0,0,.12);
}
.hiw-card:nth-child(even):hover { transform: translateY(-10px) rotate(1deg); }
.hiw-emoji {
  font-size: 3.5rem; display: block; margin-bottom: 14px;
  animation: floatBob 4s ease-in-out infinite;
}
.hiw-card:nth-child(1) .hiw-emoji { animation-delay:0s; }
.hiw-card:nth-child(2) .hiw-emoji { animation-delay:.5s; }
.hiw-card:nth-child(3) .hiw-emoji { animation-delay:1s; }
.hiw-card:nth-child(4) .hiw-emoji { animation-delay:1.5s; }
.hiw-card:nth-child(5) .hiw-emoji { animation-delay:2s; }
.hiw-card:nth-child(6) .hiw-emoji { animation-delay:2.5s; }
.hiw-step {
  display: inline-block;
  font-family: var(--font-h);
  font-size: .85rem; letter-spacing: .08em;
  padding: 3px 14px; border-radius: 100px;
  margin-bottom: 12px;
  color: white; font-weight: 400;
}
.s1,.s2,.s3 { background: var(--coral); }
.s4 { background: var(--peach); }
.s5 { background: var(--mint); }
.s6 { background: var(--lavender); }
.hiw-card h3 { font-family: var(--font-h); font-size: 1.25rem; margin-bottom: 8px; }
.hiw-card p  { font-size: .875rem; font-weight: 600; color: rgba(26,35,64,.55); }

/* connector arrow between cards */
.hiw-arrow {
  display: flex; align-items: center; justify-content: center;
  font-size: 2rem; color: rgba(26,35,64,.15);
  align-self: center;
}

/* ══ CATALOG ═════════════════════════════════════════ */
.filter-row {
  display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 36px;
}
.chip {
  padding: 9px 22px; border-radius: 100px;
  font-family: var(--font-b); font-size: .875rem; font-weight: 800;
  border: 2.5px solid var(--ink); background: transparent; color: var(--ink);
  cursor: pointer; transition: all .2s cubic-bezier(.34,1.56,.64,1);
}
.chip:hover, .chip.active {
  background: var(--ink); color: #fff;
  transform: scale(1.05);
}
.chip.c-coral.active   { background: var(--coral); border-color: var(--coral); color: #fff; }
.chip.c-mint.active    { background: var(--mint);  border-color: var(--mint);  color: #fff; }
.chip.c-lav.active     { background: var(--lavender); border-color: var(--lavender); color: #fff; }

.catalog-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  max-width: 1200px;
  margin: 0 auto;
}
.alat-card {
  background: var(--white);
  border-radius: var(--radius-xl);
  overflow: hidden;
  border: 3px solid #f0ede4;
  transition: transform .3s cubic-bezier(.34,1.56,.64,1), box-shadow .3s, border-color .2s;
  cursor: pointer;
}
.alat-card:hover {
  transform: translateY(-10px) scale(1.02);
  box-shadow: 0 22px 50px rgba(0,0,0,.12);
  border-color: var(--sun);
}
.alat-img-box {
  height: 190px; display: flex; align-items: center; justify-content: center;
  position: relative; overflow: hidden;
  background: linear-gradient(135deg, #fef9ec 0%, #f0f8ff 100%);
}
.alat-img-box img {
  width: 100%; height: 100%; object-fit: cover;
  transition: transform .3s;
}
.alat-card:hover .alat-img-box img {
  transform: scale(1.1);
}
.alat-emoji { font-size: 5rem; transition: transform .3s; filter: drop-shadow(0 8px 16px rgba(0,0,0,.1)); }
.alat-card:hover .alat-emoji { transform: scale(1.18) rotate(-6deg); }
.alat-tag {
  position: absolute; top: 12px; left: 12px;
  padding: 5px 12px; border-radius: 100px;
  font-size: .7rem; font-weight: 900; text-transform: uppercase; letter-spacing: .08em;
  background: var(--sun); color: var(--ink);
  box-shadow: 0 3px 10px rgba(0,0,0,.1);
}
.tag-gelas { background: var(--mint); color: #fff; }
.alat-body { padding: 20px; }
.alat-name { font-family: var(--font-h); font-size: 1.2rem; margin-bottom: 6px; letter-spacing: .3px; }
.alat-avail {
  font-size: .82rem; font-weight: 700;
  display: flex; align-items: center; gap: 6px; margin-bottom: 16px;
  color: rgba(26,35,64,.5);
}
.dot-g { width: 8px; height: 8px; border-radius: 50%; background: #22c55e; box-shadow: 0 0 6px #22c55e; }
.dot-r { width: 8px; height: 8px; border-radius: 50%; background: #ef4444; box-shadow: 0 0 6px #ef4444; }
.btn-pinjam {
  width: 100%; padding: 13px; border-radius: var(--radius-md);
  background: linear-gradient(135deg, var(--coral), #ff8c5a);
  color: #fff; font-family: var(--font-b); font-size: .9rem; font-weight: 900;
  border: none; cursor: pointer;
  box-shadow: 0 6px 20px rgba(255,107,107,.35);
  transition: transform .2s cubic-bezier(.34,1.56,.64,1), box-shadow .2s;
}
.btn-pinjam:hover { transform: scale(1.03) translateY(-2px); box-shadow: 0 10px 28px rgba(255,107,107,.45); }
.btn-pinjam:disabled { background: #ddd; box-shadow: none; cursor: not-allowed; color: #999; }

/* ══ CTA RAINBOW ══════════════════════════════════════ */
.cta-section {
  background: linear-gradient(160deg, var(--lavender) 0%, var(--coral) 50%, var(--peach) 100%);
  padding: 100px 5vw;
  text-align: center; position: relative; overflow: hidden;
}
.cta-section::before {
  content: '';
  position: absolute; inset: 0;
  background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.06'%3E%3Ccircle cx='30' cy='30' r='4'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.cta-emoji-row {
  font-size: 3.5rem; margin-bottom: 24px; position: relative; z-index: 1;
  display: flex; gap: 20px; justify-content: center; flex-wrap: wrap;
}
.cta-emoji-row span { animation: floatBob 3s ease-in-out infinite; display: inline-block; }
.cta-emoji-row span:nth-child(2) { animation-delay: .5s; }
.cta-emoji-row span:nth-child(3) { animation-delay: 1s; }
.cta-section h2 {
  font-family: var(--font-h); font-size: clamp(2.4rem, 6vw, 4rem);
  color: var(--white); margin-bottom: 14px; position: relative; z-index: 1;
  text-shadow: 0 4px 18px rgba(0,0,0,.2);
  letter-spacing: .5px;
}
.cta-section p {
  font-size: 1.05rem; font-weight: 700;
  color: rgba(255,255,255,.85); margin-bottom: 40px;
  position: relative; z-index: 1;
}
.btn-cta-big {
  padding: 20px 52px; border-radius: 100px;
  background: var(--white); color: var(--coral);
  font-family: var(--font-b); font-size: 1.15rem; font-weight: 900;
  border: none; cursor: pointer;
  box-shadow: 0 10px 36px rgba(0,0,0,.18);
  transition: transform .25s cubic-bezier(.34,1.56,.64,1), box-shadow .2s;
  position: relative; z-index: 1;
}
.btn-cta-big:hover { transform: scale(1.07) translateY(-4px); box-shadow: 0 20px 50px rgba(0,0,0,.25); }

/* ══ GRASS FOOTER ════════════════════════════════════ */
.footer-grass {
  background: linear-gradient(180deg, var(--grass) 0%, var(--grass-d) 100%);
  padding: 0 5vw 0;
  position: relative;
}
.footer-grass-svg { display: block; }
.footer-inner {
  display: grid; grid-template-columns: 2fr 1fr 1fr 1fr; gap: 40px;
  padding: 60px 0 40px;
}
.footer-brand .logo { color: var(--white); margin-bottom: 12px; }
.footer-brand p { font-size: .875rem; font-weight: 600; color: rgba(255,255,255,.7); max-width: 280px; line-height: 1.7; }
.footer-col h4 {
  font-family: var(--font-h); font-size: 1.1rem;
  color: var(--white); margin-bottom: 16px;
}
.footer-col a {
  display: block; font-size: .875rem; font-weight: 600;
  color: rgba(255,255,255,.65); margin-bottom: 10px;
  transition: color .2s;
  text-decoration: none;
}
.footer-col a:hover { color: var(--white); }
.footer-contact { display: flex; gap: 8px; align-items: flex-start; font-size: .875rem; font-weight: 600; color: rgba(255,255,255,.65); margin-bottom: 10px; }
.footer-bottom {
  padding: 24px 0;
  border-top: 2px dashed rgba(255,255,255,.2);
  text-align: center; font-size: .82rem; font-weight: 700;
  color: rgba(255,255,255,.55);
}

/* ══ SCROLL REVEAL ════════════════════════════════════ */
.reveal { opacity:0; transform: translateY(36px); transition: opacity .7s, transform .7s cubic-bezier(.25,.8,.25,1); }
.reveal.up { opacity:1; transform: translateY(0); }

@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.95); }
  to { opacity: 1; transform: scale(1); }
}

/* ══ RESPONSIVE ══════════════════════════════════════ */
@media (max-width:860px) {
  .hiw-grid { grid-template-columns: 1fr 1fr; }
  .footer-inner { grid-template-columns: 1fr 1fr; }
  .stats-row { flex-wrap: wrap; }
  .stat-pill { flex: 1 1 50%; border-right:none; border-bottom: 2px dashed rgba(255,255,255,.25); }
  .nav-links { display: none; }
  .catalog-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width:560px) {
  .hiw-grid { grid-template-columns: 1fr; }
  .footer-inner { grid-template-columns: 1fr; }
  .catalog-grid { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<!-- ══ NAV ══════════════════════════════════════════ -->
<nav>
  <div class="logo">
    <div class="logo-dot">🔬</div>
    LabPinjam
  </div>
  <div class="nav-links">
    <a href="{{ route('landing.index') }}">Beranda</a>
    <a href="{{ route('landing.tools') }}">Alat Lab</a>
    <a href="#cara">Cara Peminjaman</a>
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

<!-- ══ HERO ══════════════════════════════════════════ -->
<section class="hero">
  <!-- Clouds -->
  <div class="cloud c1"><svg viewBox="0 0 200 80" width="200"><ellipse cx="80" cy="55" rx="70" ry="28"/><ellipse cx="100" cy="40" rx="50" ry="30"/><ellipse cx="140" cy="52" rx="55" ry="24"/></svg></div>
  <div class="cloud c2"><svg viewBox="0 0 260 90" width="260"><ellipse cx="100" cy="65" rx="90" ry="28"/><ellipse cx="130" cy="45" rx="65" ry="34"/><ellipse cx="190" cy="62" rx="65" ry="26"/></svg></div>
  <div class="cloud c3"><svg viewBox="0 0 140 60" width="140"><ellipse cx="55" cy="42" rx="50" ry="20"/><ellipse cx="70" cy="30" rx="36" ry="22"/><ellipse cx="100" cy="40" rx="38" ry="18"/></svg></div>
  <div class="cloud c4"><svg viewBox="0 0 180 70" width="180"><ellipse cx="70" cy="50" rx="60" ry="22"/><ellipse cx="90" cy="35" rx="46" ry="26"/><ellipse cx="130" cy="48" rx="48" ry="20"/></svg></div>

  <!-- Floating emojis -->
  <div class="emoji-float ef1">☀️</div>
  <div class="emoji-float ef2">🧪</div>
  <div class="emoji-float ef3">⚗️</div>
  <div class="emoji-float ef4">🔭</div>
  <div class="emoji-float ef5">🧲</div>

  <div class="hero-badge">🎉 Platform Resmi Universitas</div>
  <h1 class="hero-title">
    Pinjam Alat Lab<br>
    <span class="line2">Gampang Banget!</span>
    <span class="line3" style="font-size:clamp(1.4rem,3vw,2.4rem);margin-top:6px;display:block;font-family:var(--font-b);font-weight:900;color:rgba(255,255,255,.85)">untuk praktikum & riset kamu 🧫</span>
  </h1>
  <p class="hero-sub">Semua alat terawat, tersedia, dan bisa dipinjam dalam hitungan menit. Gratis untuk mahasiswa terdaftar!</p>
  <div class="hero-ctas">
    <a href="{{ route('login') }}" class="btn-big btn-yellow" style="text-decoration:none;display:inline-block;">Mulai Peminjaman 🎯</a>
    <a href="#cara" class="btn-big btn-white" style="text-decoration:none;display:inline-block;">Lihat Cara Kerjanya</a>
  </div>
  <div class="scroll-hint" onclick="document.getElementById('stats').scrollIntoView({behavior:'smooth'})">⬇️</div>
</section>

<!-- ══ STATS ══════════════════════════════════════════ -->
<div id="stats" class="stats-row">
  <div class="stat-pill">
    <div class="stat-num">{{ $totalTools ?? 0 }} 🔬</div>
    <div class="stat-lbl">Total Alat Lab</div>
  </div>
  <div class="stat-pill">
    <div class="stat-num">{{ $availableTools ?? 0 }}</div>
    <div class="stat-lbl">Alat Tersedia</div>
  </div>
  <div class="stat-pill">
    <div class="stat-num">24jam ⚡</div>
    <div class="stat-lbl">Proses Persetujuan</div>
  </div>
</div>

<!-- ══ HOW IT WORKS ════════════════════════════════════ -->
<section class="section sky-section" id="cara">
  <div class="reveal" style="text-align:center">
    <div class="section-tag">✨ Cara Kerja</div>
    <h2 class="section-title">6 Langkah Mudah<br>Pinjam Alat Lab!</h2>
    <p class="section-desc" style="margin:0 auto">Prosesnya simpel, transparan, dan nggak ribet sama sekali 🎉</p>
  </div>
  <div class="hiw-grid">
    <div class="hiw-card reveal">
      <span class="hiw-emoji">👤</span>
      <span class="hiw-step s1">Langkah 1</span>
      <h3>Daftar & Login</h3>
      <p>Buat akun atau login ke platform LabPinjam untuk mengakses katalog alat lengkap.</p>
    </div>
    <div class="hiw-card reveal">
      <span class="hiw-emoji">🔍</span>
      <span class="hiw-step s2">Langkah 2</span>
      <h3>Pilih Alat</h3>
      <p>Jelajahi daftar alat lab dan pilih yang kamu butuhkan untuk praktikum.</p>
    </div>
    <div class="hiw-card reveal">
      <span class="hiw-emoji">📝</span>
      <span class="hiw-step s3">Langkah 3</span>
      <h3>Ajukan Peminjaman</h3>
      <p>Isi formulir dengan tanggal & durasi yang kamu perlukan, lalu tunggu persetujuan.</p>
    </div>
    <div class="hiw-card reveal">
      <span class="hiw-emoji">📦</span>
      <span class="hiw-step s4">Langkah 4</span>
      <h3>Ambil Alat</h3>
      <p>Setelah disetujui, ambil alatnya di lab sesuai jadwal yang sudah ditentukan.</p>
    </div>
    <div class="hiw-card reveal">
      <span class="hiw-emoji">🧪</span>
      <span class="hiw-step s5">Langkah 5</span>
      <h3>Gunakan & Jaga</h3>
      <p>Gunakan alat dengan baik, jaga kondisinya selama masa peminjaman ya!</p>
    </div>
    <div class="hiw-card reveal">
      <span class="hiw-emoji">🔄</span>
      <span class="hiw-step s6">Langkah 6</span>
      <h3>Kembalikan</h3>
      <p>Kembalikan alat dalam kondisi baik sesuai tanggal yang sudah disepakati.</p>
    </div>
  </div>
</section>

<!-- ══ CATALOG ════════════════════════════════════════ -->
<section class="section white-section" id="alat">
  <div class="reveal" style="text-align:center;margin-bottom:40px">
    <div class="section-tag">🔬 Katalog Alat</div>
    <h2 class="section-title">Pilih Alat Favoritmu!</h2>
    <p class="section-desc" style="margin:0 auto">Semua alat dalam kondisi prima dan siap dipakai untuk eksperimenmu 💡</p>
  </div>
  <div class="catalog-grid reveal">
    @forelse($featuredTools ?? [] as $tool)
    <div class="alat-card" data-category="cat-{{ $tool->category_id }}" data-available="{{ $tool->stok > 0 ? 'yes' : 'no' }}">
      <div class="alat-img-box">
        <span class="alat-tag">{{ $tool->category->nama_kategori ?? 'Alat Lab' }}</span>
        @if($tool->gambar)
          @php
            // Remove 'images/tools/' prefix if exists in database
            $imagePath = str_replace('images/tools/', '', $tool->gambar);
          @endphp
          <img src="{{ asset('images/tools/' . $imagePath) }}" alt="{{ $tool->nama_alat }}" style="width:100%;height:100%;object-fit:cover;">
        @else
          <span class="alat-emoji">🔬</span>
        @endif
      </div>
      <div class="alat-body">
        <div class="alat-name">{{ $tool->nama_alat }}</div>
        @if($tool->stok > 0)
          <div class="alat-avail"><span class="dot-g"></span>{{ $tool->stok }} Tersedia</div>
          <a href="{{ route('login') }}" class="btn-pinjam" style="display:block;text-align:center;text-decoration:none;">Pinjam Sekarang →</a>
        @else
          <div class="alat-avail"><span class="dot-r"></span>Habis dipinjam</div>
          <button class="btn-pinjam" disabled>Tidak Tersedia 😢</button>
        @endif
      </div>
    </div>
    @empty
    <div style="grid-column:1/-1;text-align:center;padding:60px 20px">
      <div style="font-size:4rem;margin-bottom:16px">🔍</div>
      <h3 style="font-family:var(--font-h);font-size:1.5rem;margin-bottom:8px">Belum Ada Alat</h3>
      <p style="color:rgba(26,35,64,.5);font-weight:600">Katalog alat akan segera tersedia!</p>
    </div>
    @endforelse
  </div>
  @if(isset($featuredTools) && $featuredTools->count() > 0)
  <div style="text-align:center;margin-top:50px">
    <a href="{{ route('landing.tools') }}" class="btn-big btn-yellow" style="display:inline-block;text-decoration:none;">Lihat Semua Alat 🎯</a>
  </div>
  @endif
</section>

<!-- ══ CTA ════════════════════════════════════════════ -->
<section class="cta-section reveal">
  <div class="cta-emoji-row">
    <span>🧬</span><span>🔬</span><span>⚗️</span><span>🧫</span><span>🔭</span>
  </div>
  <h2>Siap Mulai Eksperimenmu?</h2>
  <p>Jangan tunda lagi — ribuan alat lab berkualitas menunggumu! 🚀</p>
  <a href="{{ route('landing.tools') }}" class="btn-cta-big" style="text-decoration:none;display:inline-block;">Lihat Semua Alat 🎯</a>
</section>

<!-- ══ FOOTER GRASS ════════════════════════════════════ -->
<footer class="footer-grass">
  <svg class="footer-grass-svg" viewBox="0 0 1440 80" preserveAspectRatio="none" height="80"><path fill="#6dcc6d" d="M0,40 C120,80 240,0 360,40 C480,80 600,0 720,40 C840,80 960,0 1080,40 C1200,80 1320,0 1440,40 L1440,80 L0,80 Z"/></svg>
  <div class="footer-inner">
    <div class="footer-brand">
      <div class="logo" style="color:#fff;margin-bottom:12px">
        <div class="logo-dot" style="background:rgba(255,255,255,.2)">🔬</div>
        LabPinjam
      </div>
      <p>Platform peminjaman alat laboratorium terintegrasi untuk mendukung riset dan praktikum yang lebih efisien dan menyenangkan.</p>
    </div>
    <div class="footer-col">
      <h4>Navigasi</h4>
      <a href="{{ route('landing.index') }}">🏠 Beranda</a>
      <a href="{{ route('landing.tools') }}">🔬 Daftar Alat</a>
      <a href="#cara">📋 Cara Peminjaman</a>
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
  <div class="footer-bottom">
    © 2026 LabPinjam — Dibuat dengan 💚 untuk mahasiswa Indonesia
  </div>
</footer>

<script>
// Filter chips
let currentFilter = 'all';

function setFilter(el, filterType) {
  document.querySelectorAll('.chip').forEach(c => c.classList.remove('active'));
  el.classList.add('active');
  currentFilter = filterType;
  
  const cards = document.querySelectorAll('.alat-card');
  
  cards.forEach(card => {
    let show = false;
    
    if (filterType === 'all') {
      show = true;
    } else if (filterType === 'available') {
      show = card.dataset.available === 'yes';
    } else if (filterType.startsWith('cat-')) {
      show = card.dataset.category === filterType;
    }
    
    if (show) {
      card.style.display = 'block';
      card.style.animation = 'fadeIn 0.3s ease-in-out';
    } else {
      card.style.display = 'none';
    }
  });
}

// Scroll reveal
const io = new IntersectionObserver(entries => {
  entries.forEach((e, i) => {
    if (e.isIntersecting) {
      // stagger cards in same parent
      const siblings = [...e.target.parentElement.querySelectorAll('.reveal')];
      const idx = siblings.indexOf(e.target);
      setTimeout(() => e.target.classList.add('up'), idx * 90);
    }
  });
}, { threshold: 0.1 });
document.querySelectorAll('.reveal').forEach(el => io.observe(el));
</script>
</body>
</html>
