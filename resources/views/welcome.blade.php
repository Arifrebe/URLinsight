@extends('layouts.urlinsight')

@section('title', 'URLinsight - Periksa URL Mencurigakan')

@section('content')

    {{-- HERO --}}
    <section class="hero">
        <div class="container hero-grid">
            <div>
                <span class="eyebrow">Deteksi URL phishing berbasis Machine Learning</span>
                <h1>Jangan langsung klik. <span class="grad-text">Pahami tautannya</span> dulu.</h1>
                <p class="lead">
                    Tautan phishing dibuat semirip mungkin dengan situs asli untuk mencuri datamu.
                    URLinsight membaca ciri-ciri sebuah URL dan memberi indikasi risikonya, tanpa membuka tautan itu.
                </p>
                <div class="hero-actions">
                    <a href="/periksa" class="btn">Periksa URL Sekarang</a>
                    <a href="#anatomi" class="btn btn-ghost">Pelajari lebih lanjut</a>
                </div>
            </div>

            <div class="visual" aria-hidden="true">
                <span class="ring"></span><span class="ring r2"></span>
                <svg class="shield-main" viewBox="0 0 200 230" fill="none">
                    <defs>
                        <linearGradient id="sg" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0" stop-color="#55B8F0"/><stop offset="1" stop-color="#8B7CF6"/>
                        </linearGradient>
                    </defs>
                    <path d="M100 8 L180 38 V110 C180 160 145 198 100 220 C55 198 20 160 20 110 V38 Z" fill="rgba(255,255,255,.08)" stroke="url(#sg)" stroke-width="4"/>
                    <path d="M100 30 L162 53 V110 C162 150 135 180 100 198 C65 180 38 150 38 110 V53 Z" fill="rgba(37,99,235,.25)" stroke="rgba(255,255,255,.25)" stroke-width="1.5"/>
                    <path d="M70 112 L92 134 L134 88" stroke="#fff" stroke-width="12" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div class="chip glass c1">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>
                    https://
                </div>
                <div class="chip glass c2">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l10 18H2z"/><path d="M12 10v5"/><path d="M12 18h.01"/></svg>
                    Terindikasi Phishing
                </div>
                <div class="chip glass c3">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M8 12l3 3 5-6"/></svg>
                    Tidak ada indikasi
                </div>
            </div>
        </div>
    </section>

    {{-- STRIP FITUR --}}
    <section class="section" style="padding-top:0">
        <div class="container">
            <div class="glass strip">
                <div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 0 1 8 0v4"/></svg>Tautan tidak dibuka</div>
                <div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M5 5l2 2M17 17l2 2M19 5l-2 2M7 17l-2 2"/></svg>Analisis Machine Learning</div>
                <div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L4 14h7l-1 8 9-12h-7z"/></svg>Hasil cepat</div>
                <div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h10"/></svg>Penjelasan mudah dipahami</div>
            </div>
        </div>
    </section>

    {{-- ANATOMI URL --}}
    <section class="section" id="anatomi">
        <div class="container">
            <div class="section-head">
                <h2>Setiap bagian URL membawa sinyal</h2>
                <p>Phishing sering bersembunyi di detail kecil. Ini contoh ilustrasi bagaimana sebuah URL dibaca.</p>
            </div>
            <div class="glass anatomy">
                <div class="anatomy-url" aria-label="Contoh URL phishing">
                    <span class="seg proto">http://</span><span class="seg domain">secure-login-example.com</span><span class="seg path">/account/verify</span>
                </div>
                <div class="anatomy-notes">
                    <div><b style="color:#FDE68A">Protokol</b><span>Tidak memakai HTTPS, sehingga koneksinya tidak terenkripsi.</span></div>
                    <div><b style="color:#FCA5A5">Domain</b><span>Nama dibuat mirip layanan resmi agar terlihat meyakinkan.</span></div>
                    <div><b style="color:#BAE6FD">Path</b><span>Meminta verifikasi akun, pola yang sering dipakai penipu.</span></div>
                </div>
                <p class="note">Contoh ilustrasi, bukan hasil pemeriksaan sungguhan.</p>
            </div>
        </div>
    </section>

    {{-- CARA KERJA --}}
    <section class="section" id="cara-kerja">
        <div class="container">
            <div class="section-head">
                <h2>Cara kerjanya</h2>
                <p>Tiga langkah singkat, tanpa perlu paham istilah teknis.</p>
            </div>
            <div class="grid grid-3">
                <div class="glass card">
                    <div class="icon-box"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1"/><path d="M14 10a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1-1"/></svg></div>
                    <h3><span class="step-tag">LANGKAH 1</span><br>Masukkan URL</h3>
                    <p>Tempel tautan yang ingin kamu periksa. Tautannya tidak dibuka otomatis.</p>
                </div>
                <div class="glass card">
                    <div class="icon-box"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-4-4"/></svg></div>
                    <h3><span class="step-tag">LANGKAH 2</span><br>Sistem menganalisis</h3>
                    <p>Model membaca ciri-ciri URL untuk memperkirakan risikonya.</p>
                </div>
                <div class="glass card">
                    <div class="icon-box"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/><path d="M9 12l2 2 4-4"/></svg></div>
                    <h3><span class="step-tag">LANGKAH 3</span><br>Lihat hasilnya</h3>
                    <p>Kamu mendapat indikasi beserta penjelasannya. Tetap berhati-hati, ini bukan jaminan.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTOH HASIL --}}
    <section class="section">
        <div class="container">
            <div class="section-head">
                <h2>Seperti apa hasilnya?</h2>
                <p>Dua contoh tampilan hasil. Keduanya ilustrasi.</p>
            </div>
            <div class="grid grid-2">
                <div class="glass result-card">
                    <span class="result-url">http://secure-login-example.com/account/verify</span>
                    <span class="badge badge-danger"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l10 18H2z"/><path d="M12 10v5"/><path d="M12 18h.01"/></svg>Terindikasi Phishing</span>
                    <p>Model mendeteksi indikasi phishing pada URL ini.</p>
                </div>
                <div class="glass result-card">
                    <span class="result-url">https://www.example.com</span>
                    <span class="badge badge-ok"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M8 12l3 3 5-6"/></svg>Tidak ditemukan indikasi phishing</span>
                    <p>Model tidak menemukan indikasi phishing pada URL ini. Tetap berhati-hati.</p>
                </div>
            </div>
            <p class="note">Hasil model adalah indikasi, bukan jaminan keamanan.</p>
        </div>
    </section>

    {{-- AJAKAN --}}
    <section class="section">
        <div class="container">
            <div class="glass cta-band">
                <div>
                    <h2>Ragu dengan sebuah tautan?</h2>
                    <p>Periksa dulu sebelum membuka atau mengisi data apa pun.</p>
                </div>
                <a href="/periksa" class="btn">Periksa URL Sekarang</a>
            </div>
        </div>
    </section>

@endsection