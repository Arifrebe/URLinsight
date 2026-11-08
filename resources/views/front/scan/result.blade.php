
@extends('front.layout.main')

@section('title', 'Hasil Analisis - URLinsight')

@section('content')
<section class="py-5">
    <div class="container">

        <div class="mb-4">
            <a href="{{ route('scan.index') }}" class="text-decoration-none">
                &larr; Analisis URL lainnya
            </a>
        </div>

        <div class="text-center mb-5">
            <h1 class="fw-bold">Hasil Analisis URL</h1>
            <p class="text-secondary">
                Berikut adalah hasil pemeriksaan URL menggunakan URLinsight.
            </p>
        </div>

        {{-- Informasi URL --}}
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body p-4">
                <small class="text-secondary fw-semibold">URL YANG DIPERIKSA</small>

                <div class="mt-2 fw-semibold text-break">
                    {{ $scan->url }}
                </div>

                <div class="text-secondary small mt-2">
                    Domain: {{ $scan->domain ?? '-' }}
                </div>

                <div class="text-secondary small mt-1">
                    Waktu pemindaian:
                    {{ $scan->created_at->format('d M Y, H:i') }}
                </div>
            </div>
        </div>

        {{-- Status gagal --}}
        @if ($scan->status === 'failed')
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 text-center">
                    <div class="mb-3">
                        <span class="badge bg-warning text-dark px-3 py-2">
                            Analisis Gagal
                        </span>
                    </div>

                    <h4 class="fw-bold">Belum Dapat Menganalisis URL</h4>

                    <p class="text-secondary mb-0">
                        Layanan analisis sedang mengalami kendala.
                        Silakan coba kembali beberapa saat lagi.
                    </p>
                </div>
            </div>

        {{-- Status pending --}}
        @elseif ($scan->status === 'pending')
            <div class="alert alert-info">
                Pemindaian masih dalam proses. Silakan periksa kembali nanti.
            </div>

        {{-- Status completed --}}
        @elseif ($scan->status === 'completed')

            {{-- Hasil klasifikasi --}}
            @if ($scan->prediction === 'phishing')
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4 text-center">
                        <span class="badge bg-danger px-3 py-2 mb-3">
                            Phishing
                        </span>

                        <h3 class="fw-bold text-danger">
                            URL Terindikasi Phishing
                        </h3>

                        <p class="text-secondary mb-0">
                            Model mendeteksi karakteristik URL yang
                            mengindikasikan potensi phishing.
                            Hindari memasukkan informasi sensitif pada situs ini.
                        </p>
                    </div>
                </div>

            @elseif ($scan->prediction === 'legitimate')
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4 text-center">
                        <span class="badge bg-success px-3 py-2 mb-3">
                            Legitimate
                        </span>

                        <h3 class="fw-bold text-success">
                            URL Diklasifikasikan Legitimate
                        </h3>

                        <p class="text-secondary mb-0">
                            Model tidak menemukan indikasi phishing
                            berdasarkan karakteristik yang dianalisis.
                            Tetap berhati-hati sebelum memberikan informasi pribadi.
                        </p>
                    </div>
                </div>

            @else
                <div class="alert alert-warning">
                    Hasil klasifikasi tidak tersedia atau tidak dikenali.
                </div>
            @endif

            {{-- Confidence --}}
            @if ($scan->confidence !== null)
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-2">Confidence Model</h5>

                        <h3 class="fw-bold text-primary">
                            {{ number_format($scan->confidence * 100, 2) }}%
                        </h3>

                        <p class="text-secondary small mb-0">
                            Nilai confidence menunjukkan tingkat keyakinan
                            model terhadap prediksi, bukan jaminan bahwa
                            URL tersebut benar-benar aman atau berbahaya.
                        </p>
                    </div>
                </div>
            @endif

            {{-- Indikator --}}
            @if (!empty($scan->indicators))
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Indikator URL</h5>

                        @foreach ($scan->indicators as $indicator)
                            <div class="border rounded-3 p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center gap-2">
                                    <strong>
                                        {{ $indicator['name'] ?? 'Indikator' }}
                                    </strong>

                                    @if (!empty($indicator['severity']))
                                        <span class="badge bg-secondary">
                                            {{ ucfirst($indicator['severity']) }}
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-2">
                                    <span class="text-secondary">Nilai:</span>
                                    {{ is_array($indicator['value'] ?? null)
                                        ? json_encode($indicator['value'])
                                        : ($indicator['value'] ?? '-') }}
                                </div>

                                @if (!empty($indicator['description']))
                                    <p class="text-secondary small mb-0 mt-1">
                                        {{ $indicator['description'] }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Penjelasan model --}}
            @if (!empty($scan->explanation))
                <div class="card border-0 shadow-sm rounded-4 mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Penjelasan Prediksi</h5>

                        @if (!empty($scan->explanation['method']))
                            <p class="small text-secondary">
                                Metode:
                                {{ $scan->explanation['method'] }}
                            </p>
                        @endif

                        @if (!empty($scan->explanation['summary']))
                            <p>
                                {{ $scan->explanation['summary'] }}
                            </p>
                        @endif

                        @if (!empty($scan->explanation['top_features']))
                            <h6 class="fw-semibold mt-4">
                                Faktor yang Memengaruhi Prediksi
                            </h6>

                            @foreach ($scan->explanation['top_features'] as $feature)
                                <div class="border-bottom py-2">
                                    <strong>
                                        {{ $feature['name'] ?? 'Feature' }}
                                    </strong>

                                    <div class="small text-secondary">
                                        Nilai:
                                        {{ $feature['value'] ?? '-' }}

                                        @if (isset($feature['contribution']))
                                            | Kontribusi:
                                            {{ $feature['contribution'] }}
                                        @endif

                                        @if (!empty($feature['direction']))
                                            | Arah:
                                            {{ $feature['direction'] }}
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endif

            {{-- Metadata --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Informasi Pemrosesan</h5>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <small class="text-secondary">Versi Model</small>
                            <div class="fw-semibold">
                                {{ $scan->model_version ?? '-' }}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <small class="text-secondary">Waktu Pemrosesan</small>
                            <div class="fw-semibold">
                                {{ $scan->processing_time_ms !== null
                                    ? $scan->processing_time_ms . ' ms'
                                    : '-' }}
                            </div>
                        </div>

                        <div class="col-md-4">
                            <small class="text-secondary">Selesai Pada</small>
                            <div class="fw-semibold">
                                {{ $scan->completed_at
                                    ? $scan->completed_at->format('d M Y, H:i')
                                    : '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <div class="alert alert-warning">
                Status pemindaian tidak dikenali.
            </div>
        @endif

        <div class="text-center mt-4">
            <a href="{{ route('scan.index') }}" class="btn btn-primary px-4">
                Analisis URL Lain
            </a>
        </div>

    </div>
</section>
@endsection
