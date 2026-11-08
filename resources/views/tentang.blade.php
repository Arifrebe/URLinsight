@extends('layouts.urlinsight')

@section('title', 'Tentang - URLinsight')

@section('content')
    <section class="page-head">
        <div class="container">
            <span class="eyebrow">Tentang</span>
            <h1>Tentang <span class="grad-text">URLinsight</span></h1>
            <p>Aplikasi web untuk membantu orang awam menilai apakah sebuah tautan terindikasi phishing.</p>
        </div>
    </section>

    <div class="container checker-wrap">

        <div class="grid grid-2">
            <div class="glass card">
                <div class="icon-box"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><circle cx="12" cy="12" r="4"/></svg></div>
                <h3>Tujuan</h3>
                <p>Tautan phishing dirancang agar terlihat meyakinkan, dan pengecekan manual lambat serta bergantung pada kejelian. URLinsight memberi penilaian cepat berbasis data sebelum kamu terlanjur membuka tautan atau mengisi data.</p>
            </div>
            <div class="glass card">
                <div class="icon-box"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-4-4"/></svg></div>
                <h3>Pendekatan berbasis URL</h3>
                <p>Sistem tidak membuka situsnya. Model Machine Learning hanya membaca ciri-ciri tulisan URL, seperti protokol, nama domain, panjang alamat, dan pola path, lalu memperkirakan risikonya.</p>
            </div>
        </div>

        <div class="section-head" style="margin-top:2.5rem">
            <h2>Alur singkat</h2>
        </div>
        <div class="glass" style="padding:1.4rem">
            <div class="hero-actions" style="align-items:center">
                <span class="badge badge-warn" style="border-color:rgba(85,184,240,.5);background:rgba(85,184,240,.12);color:#BAE6FD">Kamu</span> →
                <span class="badge badge-warn" style="border-color:rgba(85,184,240,.5);background:rgba(85,184,240,.12);color:#BAE6FD">Antarmuka web</span> →
                <span class="badge badge-warn" style="border-color:rgba(85,184,240,.5);background:rgba(85,184,240,.12);color:#BAE6FD">Backend Laravel</span> →
                <span class="badge badge-warn" style="border-color:rgba(85,184,240,.5);background:rgba(85,184,240,.12);color:#BAE6FD">Layanan ML</span> →
                <span class="badge badge-warn" style="border-color:rgba(85,184,240,.5);background:rgba(85,184,240,.12);color:#BAE6FD">Hasil</span>
            </div>
        </div>

        <div class="section-head" style="margin-top:2.5rem">
            <h2>Hal yang perlu kamu tahu</h2>
        </div>
        <div class="glass" style="padding:1.4rem">
            <div class="prose">
                <p><strong>Hasilnya indikasi, bukan kepastian.</strong> Model bisa keliru. Hasil "tidak ditemukan indikasi phishing" bukan berarti tautan pasti aman.</p>
                <p><strong>Tetap waspada.</strong> Jika sebuah tautan meminta data pribadi, kata sandi, atau terasa mendesak, jangan langsung percaya meski hasilnya tidak mencurigakan.</p>
                <p style="margin:0"><strong>Privasi.</strong> Tautan yang kamu masukkan tidak dibuka otomatis oleh sistem.</p>
            </div>
        </div>

        <div class="section-head" style="margin-top:2.5rem">
            <h2>Tim</h2>
            <p>Mahasiswa Ilmu Komputer yang mengerjakan proyek ini.</p>
        </div>
        <div class="grid grid-3">
            <div class="glass team-card">
                <div class="avatar">A</div>
                <h3>Nama Kamu</h3>
                <p>Frontend &amp; UI/UX</p>
            </div>
            <div class="glass team-card">
                <div class="avatar">B</div>
                <h3>Nama Temanmu</h3>
                <p>Backend &amp; System Integration</p>
            </div>
            <div class="glass team-card">
                <div class="avatar">C</div>
                <h3>Nama Anggota ML</h3>
                <p>Machine Learning &amp; API Service</p>
            </div>
        </div>
    </div>
@endsection