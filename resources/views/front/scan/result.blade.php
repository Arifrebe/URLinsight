@extends('layouts.urlinsight')

@section('title', 'Hasil Pemeriksaan - URLinsight')

@php
    $indicators = is_array($scan->indicators) ? $scan->indicators : (json_decode($scan->indicators ?? '[]', true) ?: []);
    $explanation = is_array($scan->explanation) ? $scan->explanation : (json_decode($scan->explanation ?? '[]', true) ?: []);
    $topFeatures = $explanation['top_features'] ?? [];
    $isPhishing = $scan->prediction === 'phishing';
@endphp

@section('content')
    <section class="page-head">
        <div class="container">
            <span class="eyebrow">Hasil Pemeriksaan</span>
            <h1>Hasil <span class="grad-text">pemeriksaan</span></h1>
            <p>URL yang diperiksa tidak dibuka oleh sistem.</p>
        </div>
    </section>

    <div class="container checker-wrap">
        <div class="glass panel">

            {{-- URL (teks biasa, bukan tautan) --}}
            <span class="result-url">{{ $scan->url }}</span>

            @if ($scan->status === 'completed' && in_array($scan->prediction, ['phishing', 'legitimate'], true))

                <div class="result-box {{ $isPhishing ? 'is-phishing' : 'is-ok' }}" style="margin-top:1rem">
                    <div class="result-head">
                        @if ($isPhishing)
                            <span class="badge badge-danger">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l10 18H2z"/><path d="M12 10v5"/><path d="M12 18h.01"/></svg>
                                Terindikasi Phishing
                            </span>
                        @else
                            <span class="badge badge-ok">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M8 12l3 3 5-6"/></svg>
                                Tidak ditemukan indikasi phishing
                            </span>
                        @endif

                        @if ($scan->confidence !== null)
                            <span class="confidence">Tingkat keyakinan model: <b>{{ number_format($scan->confidence * 100, 0) }}%</b></span>
                        @endif
                    </div>

                    <h2>Penjelasan</h2>
                    <p>
                        @if ($isPhishing)
                            Model mendeteksi indikasi phishing pada URL ini. Jangan membuka tautan ini atau mengisi data apa pun.
                        @else
                            Model tidak menemukan indikasi phishing pada URL ini. Tetap berhati-hati, hasil ini bukan jaminan tautan aman.
                        @endif
                    </p>
                    @if (!empty($explanation['summary']))
                        <p>{{ $explanation['summary'] }}</p>
                    @endif
                </div>

                @if (count($indicators))
                    <div class="section-head" style="margin-top:2rem">
                        <h2>Indikator yang ditemukan</h2>
                    </div>
                    <div class="indicator-list">
                        @foreach ($indicators as $indicator)
                            <div class="indicator">
                                <div class="indicator-top">
                                    <b>{{ $indicator['name'] }}</b>
                                    <span class="sev">{{ $indicator['severity'] }}</span>
                                </div>
                                <p class="val">Nilai: {{ $indicator['value'] }}</p>
                                <p>{{ $indicator['description'] }}</p>
                                <p>{{ $indicator['interpretation'] }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if (count($topFeatures))
                    <div class="section-head" style="margin-top:2rem">
                        <h2>Faktor yang paling berpengaruh</h2>
                        @if (!empty($explanation['method']))
                            <p>Metode: {{ $explanation['method'] }}</p>
                        @endif
                    </div>
                    <div class="indicator">
                        @foreach ($topFeatures as $feature)
                            <div class="feature-row">
                                <b>{{ $feature['name'] }}</b>
                                <span>Mengarah ke {{ $feature['direction'] === 'phishing' ? 'phishing' : 'tidak phishing' }} ({{ number_format($feature['contribution'], 3) }})</span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="meta">
                    @if ($scan->model_version)<span>Versi model: {{ $scan->model_version }}</span>@endif
                    @if ($scan->processing_time_ms !== null)<span>Waktu proses: {{ $scan->processing_time_ms }} ms</span>@endif
                </div>

            @elseif ($scan->status === 'failed')

                <div class="result-box is-error" style="margin-top:1rem">
                    <span class="badge badge-warn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l10 18H2z"/><path d="M12 10v5"/><path d="M12 18h.01"/></svg>
                        Pemeriksaan belum berhasil
                    </span>
                    <h2>Layanan belum bisa dipakai</h2>
                    <p>Pemeriksaan tidak dapat diselesaikan saat ini. Hasil ini bukan berarti URL aman. Coba lagi beberapa saat lagi.</p>
                </div>

            @else

                <div class="result-box is-error" style="margin-top:1rem">
                    <span class="badge badge-warn">Sedang diproses</span>
                    <p style="margin-top:.8rem">Pemeriksaan masih berjalan. Muat ulang halaman ini beberapa saat lagi.</p>
                </div>

            @endif

            <p class="note">Hasil model adalah indikasi, bukan jaminan keamanan.</p>

            <div class="hero-actions" style="margin-top:1.25rem">
                <a href="{{ route('scan.index') }}" class="btn">Periksa URL Lain</a>
                @auth
                    <a href="{{ route('scan.history') }}" class="btn btn-ghost">Lihat Riwayat</a>
                @endauth
            </div>
        </div>
    </div>
@endsection