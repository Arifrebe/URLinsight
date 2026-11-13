@extends('layouts.urlinsight')

@section('title', 'Tentang - URLinsight')

@section('content')
    <section class="page-head">
        <div class="container">
            <span class="eyebrow">Tentang</span>
            <h1>Tentang <span class="grad-text">URLinsight</span></h1>
            <p>Aplikasi web untuk membantu siapa saja menilai apakah sebuah tautan terindikasi phishing, tanpa perlu membuka tautan itu.</p>
        </div>
    </section>

    <div class="container checker-wrap">

        {{-- TUJUAN & PENDEKATAN --}}
        <div class="grid grid-2">
            <div class="glass card">
                <div class="icon-box"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="4"/></svg></div>
                <h3>Untuk apa web ini dibuat?</h3>
                <p>Tautan phishing dirancang agar terlihat meyakinkan, sehingga sulit dibedakan hanya dengan mata. Pengecekan manual juga lambat dan bergantung pada kejelian. URLinsight memberi penilaian cepat berbasis data sebelum kamu terlanjur membuka tautan atau mengisi data pribadi.</p>
            </div>
            <div class="glass card">
                <div class="icon-box"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-4-4"/></svg></div>
                <h3>Pendekatan berbasis URL</h3>
                <p>Sistem tidak membuka situs yang kamu periksa. Model Machine Learning hanya membaca ciri-ciri penulisan URL, lalu memperkirakan apakah tautan itu phishing atau tidak. Penilaiannya datang dari pola pada alamat, bukan dari tampilan situsnya.</p>
            </div>
        </div>

        {{-- ANATOMI URL --}}
        <div class="section-head" style="margin-top:2.5rem">
            <h2>Apa yang dibaca dari sebuah URL?</h2>
            <p>Setiap bagian URL membawa sinyal. Ini contoh ilustrasi, bukan hasil pemeriksaan sungguhan.</p>
        </div>
        <div class="glass anatomy">
            <div class="anatomy-url" aria-label="Contoh anatomi URL">
                <span class="seg proto">http://</span><span class="seg domain">secure-login-example.com</span><span class="seg path">/account/verify</span>
            </div>
            <div class="anatomy-notes">
                <div><b style="color:#FDE68A">Protokol</b><span>Apakah memakai HTTPS atau tidak.</span></div>
                <div><b style="color:#FCA5A5">Domain</b><span>Nama situs, panjangnya, dan kemiripannya dengan merek yang dikenal.</span></div>
                <div><b style="color:#BAE6FD">Path</b><span>Pola alamat setelah domain, misalnya yang meminta login atau verifikasi.</span></div>
            </div>
        </div>

        {{-- ALUR --}}
        <div class="section-head" style="margin-top:2.5rem">
            <h2>Bagaimana web ini bekerja?</h2>
            <p>Satu URL melewati tiga bagian sistem.</p>
        </div>
        <div class="grid grid-3">
            <div class="glass card">
                <span class="step-tag">1 · ANTARMUKA WEB</span>
                <h3>Kamu memasukkan URL</h3>
                <p>Halaman URL Checker menerima tautan yang ingin diperiksa dan menampilkan hasilnya beserta penjelasan.</p>
            </div>
            <div class="glass card">
                <span class="step-tag">2 · BACKEND</span>
                <h3>Aplikasi memvalidasi</h3>
                <p>Backend memeriksa format URL, menyimpan pemeriksaan, lalu meneruskan URL ke layanan Machine Learning.</p>
            </div>
            <div class="glass card">
                <span class="step-tag">3 · LAYANAN ML</span>
                <h3>Model memprediksi</h3>
                <p>Layanan ML mengekstrak ciri-ciri URL, menjalankan model, dan mengirim label, tingkat keyakinan, serta indikator pendukung.</p>
            </div>
        </div>

        {{-- TEKNOLOGI --}}
        <div class="section-head" style="margin-top:2.5rem">
            <h2>Teknologi yang digunakan</h2>
            <p>Gambaran teknologi di balik URLinsight.</p>
        </div>
        <div class="glass" style="padding:.5rem">
            <div class="table-wrap">
                <table class="table">
                    <thead><tr><th>Bagian</th><th>Teknologi</th><th>Fungsi</th></tr></thead>
                    <tbody>
                        <tr><td>Backend</td><td>Laravel 12 (PHP)</td><td>Routing, validasi input, akun pengguna, forum, dan penyimpanan riwayat pemeriksaan.</td></tr>
                        <tr><td>Tampilan</td><td>Blade, HTML, CSS, JavaScript ringan</td><td>Halaman dirender di sisi server, responsif untuk desktop dan HP.</td></tr>
                        <tr><td>Machine Learning</td><td>Python dan Scikit-learn</td><td>Pelatihan model dan klasifikasi URL.</td></tr>
                        <tr><td>Layanan prediksi</td><td>FastAPI</td><td>Membungkus model menjadi API yang dipanggil backend.</td></tr>
                        <tr><td>Database</td><td>Database relasional</td><td>Menyimpan akun, riwayat pemeriksaan, dan data forum.</td></tr>
                        <tr><td>Tipografi</td><td>Inter dan JetBrains Mono</td><td>Inter untuk teks, JetBrains Mono untuk menampilkan URL.</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        <p class="note">Rincian model, dataset, dan metrik pengujian ada di halaman <a href="{{ url('/evaluasi') }}" class="link">Evaluasi Model</a>.</p>

        {{-- FITUR --}}
        <div class="section-head" style="margin-top:2.5rem">
            <h2>Fitur utama</h2>
        </div>
        <div class="grid grid-3">
            <div class="glass card">
                <h3>URL Checker</h3>
                <p>Periksa tautan dan lihat label, tingkat keyakinan, indikator yang ditemukan, serta faktor yang paling berpengaruh.</p>
            </div>
            <div class="glass card">
                <h3>Riwayat pemeriksaan</h3>
                <p>Pengguna yang masuk bisa melihat kembali daftar URL yang pernah diperiksa.</p>
            </div>
            <div class="glass card">
                <h3>Forum diskusi</h3>
                <p>Ruang untuk berbagi pengalaman dan pengetahuan seputar phishing dan keamanan digital.</p>
            </div>
        </div>

        {{-- PRIVASI & BATASAN --}}
        <div class="section-head" style="margin-top:2.5rem">
            <h2>Privasi dan batasan</h2>
        </div>
        <div class="glass panel">
            <div class="prose">
                <p><strong>Tautan tidak dibuka.</strong> Sistem hanya membaca tulisan URL. Tautan yang kamu masukkan tidak dikunjungi otomatis.</p>
                <p><strong>Hasilnya indikasi, bukan kepastian.</strong> Model bisa keliru. Hasil "tidak ditemukan indikasi phishing" tidak berarti tautan pasti aman.</p>
                <p><strong>Kegagalan tidak dianggap aman.</strong> Jika layanan prediksi bermasalah, web menampilkan status gagal, bukan menebak hasil.</p>
                <p><strong>Tetap waspada.</strong> Jika sebuah tautan meminta data pribadi, kata sandi, atau terasa mendesak, jangan langsung percaya meski hasilnya tidak mencurigakan.</p>
                <p style="margin:0"><strong>Tautan hasil berbatas waktu.</strong> Halaman hasil pemeriksaan dibagikan lewat tautan bertanda tangan yang berlaku 24 jam.</p>
            </div>
        </div>

        <div class="hero-actions" style="margin-top:2rem">
            <a href="{{ url('/scan') }}" class="btn">Periksa URL Sekarang</a>
            <a href="{{ url('/forum') }}" class="btn btn-ghost">Buka Forum</a>
        </div>
    </div>
@endsection