@extends('layouts.urlinsight')

@section('title', 'Evaluasi Model - URLinsight')

@section('content')
    <section class="page-head">
        <div class="container">
            <span class="eyebrow">Evaluasi Model</span>
            <h1>Seberapa baik model <span class="grad-text">bekerja?</span></h1>
            <p>Halaman ini menampilkan hasil pengujian model Machine Learning. Angka hanya ditampilkan setelah diserahkan dan divalidasi oleh tim ML.</p>
        </div>
    </section>

    <div class="container checker-wrap">

        {{-- METRIK --}}
        <div class="section-head">
            <h2>Metrik utama</h2>
            <p>Empat ukuran yang umum dipakai untuk menilai model klasifikasi.</p>
        </div>
        <div class="metric-grid">
            <div class="glass metric">
                <h3>Accuracy</h3>
                <div class="value">—</div>
                <span class="pending">Data menyusul</span>
                <p style="margin-top:.8rem">Dari seluruh URL yang diuji, berapa bagian yang ditebak dengan benar.</p>
            </div>
            <div class="glass metric">
                <h3>Precision</h3>
                <div class="value">—</div>
                <span class="pending">Data menyusul</span>
                <p style="margin-top:.8rem">Dari URL yang dinyatakan phishing, berapa yang benar-benar phishing.</p>
            </div>
            <div class="glass metric">
                <h3>Recall</h3>
                <div class="value">—</div>
                <span class="pending">Data menyusul</span>
                <p style="margin-top:.8rem">Dari seluruh URL phishing yang ada, berapa yang berhasil terdeteksi.</p>
            </div>
            <div class="glass metric">
                <h3>F1-Score</h3>
                <div class="value">—</div>
                <span class="pending">Data menyusul</span>
                <p style="margin-top:.8rem">Gabungan precision dan recall dalam satu angka seimbang.</p>
            </div>
        </div>

        {{-- INFO MODEL --}}
        <div class="section-head" style="margin-top:2.5rem">
            <h2>Informasi model</h2>
            <p>Diisi oleh tim ML.</p>
        </div>
        <div class="glass" style="padding:.5rem">
            <div class="table-wrap">
                <table class="table">
                    <thead><tr><th>Keterangan</th><th>Nilai</th></tr></thead>
                    <tbody>
                        <tr><td>Algoritma</td><td>Data menyusul</td></tr>
                        <tr><td>Sumber dataset</td><td>Data menyusul</td></tr>
                        <tr><td>Jumlah data latih</td><td>Data menyusul</td></tr>
                        <tr><td>Jumlah data uji</td><td>Data menyusul</td></tr>
                        <tr><td>Fitur yang dipakai</td><td>Data menyusul</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- CONFUSION MATRIX --}}
        <div class="section-head" style="margin-top:2.5rem">
            <h2>Confusion matrix</h2>
            <p>Tabel yang menunjukkan tebakan model yang benar dan yang keliru.</p>
        </div>
        <div class="glass" style="padding:1.4rem">
            <div class="cm" role="table" aria-label="Confusion matrix, data menyusul">
                <div class="h"></div><div class="h">Prediksi: Phishing</div><div class="h">Prediksi: Tidak phishing</div>
                <div class="h">Asli: Phishing</div><div>Data menyusul</div><div>Data menyusul</div>
                <div class="h">Asli: Tidak phishing</div><div>Data menyusul</div><div>Data menyusul</div>
            </div>
        </div>

        <p class="note">Tidak ada angka yang ditampilkan di sini sebelum tervalidasi oleh tim ML. Hasil model adalah indikasi, bukan jaminan keamanan.</p>
    </div>
@endsection