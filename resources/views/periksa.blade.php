@extends('layouts.urlinsight')

@section('title', 'URL Checker - URLinsight')

@section('content')
    <section class="page-head">
        <div class="container">
            <span class="eyebrow">URL Checker</span>
            <h1>Periksa sebuah <span class="grad-text">URL</span></h1>
            <p>Tempel tautan yang mencurigakan. Tautannya tidak akan dibuka otomatis.</p>
        </div>
    </section>

    <div class="container checker-wrap">
        <div class="glass panel">
            <form class="check-form" id="form" novalidate>
                <label for="url">Masukkan URL</label>
                <input type="text" id="url" placeholder="Contoh: https://example.com/login" autocomplete="off">
                <button type="submit" class="btn" id="btn">Periksa</button>
            </form>
            <p class="field-error" id="error" role="alert"></p>
            <div class="result-area" id="result" aria-live="polite"></div>
        </div>
    </div>

    <script>
        // MODE DEMO: data tiruan, belum terhubung ke backend.
        // Ganti nilainya untuk melihat tiap tampilan: 'phishing' | 'legitimate' | 'error'
        const DEMO = 'phishing';

        const form = document.getElementById('form');
        const input = document.getElementById('url');
        const btn = document.getElementById('btn');
        const errorEl = document.getElementById('error');
        const resultEl = document.getElementById('result');

        const ICON_WARN = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l10 18H2z"/><path d="M12 10v5"/><path d="M12 18h.01"/></svg>';
        const ICON_OK = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M8 12l3 3 5-6"/></svg>';

        function validUrl(value) {
            if (!value) return false;
            try {
                const u = new URL(/^https?:\/\//i.test(value) ? value : 'http://' + value);
                return u.hostname.includes('.');
            } catch (e) { return false; }
        }

        const views = {
            phishing: { cls: 'is-phishing', badge: 'badge-danger', icon: ICON_WARN, label: 'Terindikasi Phishing',
                text: 'Model mendeteksi indikasi phishing pada URL ini.',
                tips: ['Jangan membuka tautan ini atau mengisi data apa pun.', 'Jika tautan datang dari pesan, jangan balas dan laporkan.', 'Buka situs resminya langsung lewat alamat yang kamu ketik sendiri.'] },
            legitimate: { cls: 'is-ok', badge: 'badge-ok', icon: ICON_OK, label: 'Tidak ditemukan indikasi phishing',
                text: 'Model tidak menemukan indikasi phishing pada URL ini. Tetap berhati-hati.',
                tips: ['Hasil ini bukan jaminan bahwa tautan aman.', 'Periksa ejaan domain dan jangan memasukkan data jika ragu.'] },
            error: { cls: 'is-error', badge: 'badge-warn', icon: ICON_WARN, label: 'Layanan belum bisa dipakai',
                text: 'Pemeriksaan belum bisa dilakukan saat ini. Coba lagi beberapa saat lagi.', tips: [] }
        };

        function render(url) {
            const v = views[DEMO];
            resultEl.innerHTML = '';
            const box = document.createElement('div');
            box.className = 'result-box ' + v.cls;

            const demo = document.createElement('span');
            demo.className = 'demo-tag';
            demo.textContent = 'Mode demo: data tiruan, belum dari model';
            box.appendChild(demo);

            const urlLine = document.createElement('div');
            urlLine.className = 'result-url';
            urlLine.textContent = url; // teks biasa, bukan HTML dan bukan tautan
            box.appendChild(urlLine);

            const badge = document.createElement('div');
            badge.style.marginTop = '.8rem';
            badge.innerHTML = '<span class="badge ' + v.badge + '">' + v.icon + v.label + '</span>';
            box.appendChild(badge);

            const h = document.createElement('h2');
            h.textContent = 'Penjelasan';
            box.appendChild(h);
            const p = document.createElement('p');
            p.textContent = v.text;
            box.appendChild(p);

            if (v.tips.length) {
                const ul = document.createElement('ul');
                ul.className = 'tips';
                v.tips.forEach(t => { const li = document.createElement('li'); li.textContent = t; ul.appendChild(li); });
                box.appendChild(ul);
            }

            const note = document.createElement('p');
            note.className = 'note';
            note.textContent = 'Hasil model adalah indikasi, bukan jaminan keamanan.';
            box.appendChild(note);

            resultEl.appendChild(box);
        }

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const value = input.value.trim();
            errorEl.textContent = '';
            resultEl.innerHTML = '';

            if (!value) { errorEl.textContent = 'Isi dulu URL yang ingin diperiksa.'; return; }
            if (!validUrl(value)) { errorEl.textContent = 'Format URL belum benar. Contoh: https://example.com'; return; }

            btn.disabled = true;
            btn.textContent = 'Memeriksa...';
            resultEl.innerHTML = '<p class="note">Sedang memeriksa, mohon tunggu...</p>';

            setTimeout(function () {
                render(value);
                btn.disabled = false;
                btn.textContent = 'Periksa';
            }, 900);
        });
    </script>
@endsection