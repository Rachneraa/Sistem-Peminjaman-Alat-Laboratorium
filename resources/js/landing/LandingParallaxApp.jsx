import React, { useEffect, useMemo, useRef } from 'react';
import gsap from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

function formatStatus(status) {
    if (!status) return 'Tidak diketahui';
    return status.charAt(0).toUpperCase() + status.slice(1);
}

export default function LandingParallaxApp({
    totalTools = 0,
    availableTools = 0,
    toolsUrl = '#',
    stepsAnchor = '#cara-peminjaman',
    latestTools = [],
    featuredTools = [],
}) {
    const appRef = useRef(null);

    const heroStats = useMemo(
        () => [
            { label: 'Total Alat', value: totalTools, sub: 'Tercatat di laboratorium' },
            { label: 'Siap Dipinjam', value: availableTools, sub: 'Siap untuk dipinjam' },
        ],
        [totalTools, availableTools]
    );

    useEffect(() => {
        let ctx;

        try {
            ctx = gsap.context(() => {
                gsap.set('.lp-moon', { transformOrigin: '50% 50%' });
                gsap.set('.lp-layer-far', { y: 0 });
                gsap.set('.lp-layer-mid', { y: 0 });
                gsap.set('.lp-layer-front', { y: 0 });

                const scene = gsap.timeline({
                    scrollTrigger: {
                        trigger: '.lp-scene',
                        start: 'top top',
                        end: 'bottom bottom',
                        scrub: 2,
                    },
                });

                scene
                    .to('.lp-sky-gradient', { attr: { cy: 380 }, duration: 1 }, 0)
                    .to('.lp-moon', { y: 180, x: 60, scale: 1.25, duration: 1 }, 0)
                    .to('.lp-cloud-left', { x: -260, y: 100, duration: 1 }, 0)
                    .to('.lp-cloud-right', { x: 260, y: 80, duration: 1 }, 0)
                    .to('.lp-layer-far', { y: 210, x: -40, duration: 1 }, 0)
                    .to('.lp-layer-mid', { y: 320, x: 65, duration: 1 }, 0)
                    .to('.lp-layer-front', { y: 420, x: -95, duration: 1 }, 0)
                    .to('.lp-hero-panel', { y: 140, opacity: 0.12, duration: 0.6 }, 0.2);

                gsap.from('.lp-reveal', {
                    y: 60,
                    opacity: 0,
                    duration: 0.9,
                    stagger: 0.12,
                    ease: 'power3.out',
                    scrollTrigger: {
                        trigger: '.lp-content',
                        start: 'top 78%',
                    },
                });

                gsap.from('.lp-step', {
                    y: 40,
                    opacity: 0,
                    duration: 0.7,
                    stagger: 0.16,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: '#cara-peminjaman',
                        start: 'top 78%',
                    },
                });

                gsap.from('.lp-card', {
                    y: 45,
                    opacity: 0,
                    duration: 0.7,
                    stagger: 0.08,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: '#alat-unggulan',
                        start: 'top 82%',
                    },
                });
            }, appRef);
        } catch (error) {
            console.error('Landing parallax animation init failed:', error);
        }

        return () => {
            if (ctx) {
                ctx.revert();
            }
        };
    }, []);

    return (
        <div ref={appRef} className="lp-app">
            <section className="lp-scene" aria-label="Parallax Hero Scene">
                <div className="lp-fixed-stage" aria-hidden="true">
                    <svg className="lp-parallax-svg" viewBox="0 0 1400 900" preserveAspectRatio="xMidYMid slice">
                        <defs>
                            <radialGradient id="lp-sky" className="lp-sky-gradient" cx="700" cy="-80" r="900" gradientUnits="userSpaceOnUse">
                                <stop offset="0" stopColor="#bfdcff" />
                                <stop offset="0.33" stopColor="#5ba8ff" />
                                <stop offset="0.62" stopColor="#3d6cd8" />
                                <stop offset="1" stopColor="#1f2f72" />
                            </radialGradient>
                            <linearGradient id="lp-far" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0" stopColor="#6f8fd1" />
                                <stop offset="1" stopColor="#3e5ea6" />
                            </linearGradient>
                            <linearGradient id="lp-mid" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0" stopColor="#33508f" />
                                <stop offset="1" stopColor="#1f366f" />
                            </linearGradient>
                            <linearGradient id="lp-front" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0" stopColor="#172f63" />
                                <stop offset="1" stopColor="#0d1e47" />
                            </linearGradient>
                        </defs>

                        <rect width="1400" height="900" fill="url(#lp-sky)" />
                        <circle className="lp-moon" cx="920" cy="180" r="88" fill="#d8ecff" opacity="0.88" />

                        <g className="lp-cloud-left" opacity="0.72" fill="#eef6ff">
                            <ellipse cx="300" cy="185" rx="120" ry="38" />
                            <ellipse cx="390" cy="174" rx="75" ry="30" />
                            <ellipse cx="222" cy="172" rx="68" ry="26" />
                        </g>

                        <g className="lp-cloud-right" opacity="0.65" fill="#eef6ff">
                            <ellipse cx="1110" cy="220" rx="130" ry="42" />
                            <ellipse cx="1200" cy="208" rx="86" ry="34" />
                            <ellipse cx="1025" cy="205" rx="70" ry="27" />
                        </g>

                        <path
                            className="lp-layer-far"
                            d="M0 560 C130 490 260 535 390 490 C535 438 660 498 780 466 C930 423 1040 495 1190 470 C1280 453 1350 468 1400 446 L1400 900 L0 900 Z"
                            fill="url(#lp-far)"
                        />
                        <path
                            className="lp-layer-mid"
                            d="M0 640 C160 570 260 615 410 566 C545 523 700 592 845 547 C1008 493 1128 580 1280 548 C1348 532 1388 538 1400 531 L1400 900 L0 900 Z"
                            fill="url(#lp-mid)"
                        />
                        <path
                            className="lp-layer-front"
                            d="M0 735 C120 682 260 726 382 686 C533 635 676 724 838 664 C955 620 1096 689 1216 658 C1318 635 1375 663 1400 651 L1400 900 L0 900 Z"
                            fill="url(#lp-front)"
                        />
                    </svg>
                </div>

                <div className="lp-hero-panel">
                    <div className="lp-hero-content">
                        <p className="lp-kicker">Universitas Indonesia | MIPA</p>
                        <h1>
                            Peminjaman Alat Lab
                            <span> Terpercaya. Mudah & Cepat</span>
                        </h1>
                        <p>
                            Platform peminjaman alat laboratorium yang dirancang untuk mempercepat riset kamu. Jelajahi katalog alat, ajukan jadwal, dan pantau status peminjaman dengan mudah dalam satu aplikasi terpadu. Tentu saja biaya peminjaman.
                        </p>
                        <div className="lp-cta-row">
                            <a href={toolsUrl} className="lp-btn lp-btn-primary">
                                Lihat Alat
                            </a>
                            <a href={stepsAnchor} className="lp-btn lp-btn-outline">
                                Pinjam Sekarang
                            </a>
                        </div>

                        <div className="lp-stats-grid">
                            {heroStats.map((stat) => (
                                <article key={stat.label} className="lp-stat">
                                    <h3>{stat.label}</h3>
                                    <div>{stat.value}</div>
                                    <p>{stat.sub}</p>
                                </article>
                            ))}
                        </div>
                    </div>
                </div>
            </section>

            <main className="lp-content">

                <section id="cara-peminjaman" className="lp-section lp-steps">
                    <div className="lp-container">
                        <h2 className="lp-reveal">Alur 3 Langkah</h2>
                        <p className="lp-reveal">Proses dibuat ringkas agar riset kamu tidak tertahan birokrasi.</p>
                        <div className="lp-step-grid">
                            <article className="lp-step">
                                <span>1</span>
                                <h3>Pilih Alat</h3>
                                <p>Cek ketersediaan dan sesuaikan alat dengan kebutuhan eksperimen.</p>
                            </article>
                            <article className="lp-step">
                                <span>2</span>
                                <h3>Ajukan Jadwal</h3>
                                <p>Atur tanggal pinjam serta pengembalian, lalu kirim pengajuan online.</p>
                            </article>
                            <article className="lp-step">
                                <span>3</span>
                                <h3>Ambil & Gunakan</h3>
                                <p>Datang sesuai jadwal dan tunjukkan persetujuan peminjaman ke petugas.</p>
                            </article>
                        </div>
                    </div>
                </section>

                <section id="alat-unggulan" className="lp-section lp-featured">
                    <div className="lp-container lp-reveal">
                        <h2>Alat Unggulan</h2>
                        <a href={toolsUrl} className="lp-link-more">
                            Yu, cari alat yang kamu butuhkan! →
                        </a>
                    </div>
                    <div className="lp-container lp-cards-grid">
                        {featuredTools.length === 0 && (
                            <article className="lp-empty lp-card">Belum ada alat yang tersedia saat ini.</article>
                        )}
                        {featuredTools.map((tool) => (
                            <article key={tool.id} className="lp-card">
                                <div className="lp-card-media">
                                    {tool.image ? (
                                        <img src={tool.image} alt={tool.name} className="lp-card-image" />
                                    ) : (
                                        <div className="lp-card-image lp-thumb-fallback">Lab</div>
                                    )}
                                    <div className="lp-card-chip">Total {tool.stockTotal}</div>
                                </div>
                                <div className="lp-card-body">
                                    <p>{tool.category}</p>
                                    <h3>{tool.name}</h3>
                                    <div className="lp-card-footer">
                                        <span>{tool.stock > 0 ? `${tool.stock} tersedia` : 'Habis dipinjam'}</span>
                                        <a href={tool.borrowUrl}>Pinjam</a>
                                    </div>
                                </div>
                            </article>
                        ))}
                    </div>
                </section>
            </main>
        </div>
    );
}
