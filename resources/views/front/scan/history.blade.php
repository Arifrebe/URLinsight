@extends('layouts.urlinsight')

@section('title', 'Riwayat Pemeriksaan - URLinsight')

@section('content')
    <section class="page-head">
        <div class="container">
            <span class="eyebrow">Riwayat</span>
            <h1>Riwayat <span class="grad-text">pemeriksaan</span></h1>
            <p>Daftar URL yang pernah kamu periksa.</p>
        </div>
    </section>

    <div class="container checker-wrap">
        @forelse ($scans as $scan)
            <a class="glass history-item" style="margin-bottom:.75rem"
               href="{{ \Illuminate\Support\Facades\URL::temporarySignedRoute('scan.result', now()->addHours(24), ['uuid' => $scan->uuid]) }}">
                <div>
                    <span class="result-url">{{ $scan->url }}</span>
                    <div class="history-meta">{{ $scan->created_at->format('d M Y, H:i') }}</div>
                </div>

                @if ($scan->status === 'completed' && $scan->prediction === 'phishing')
                    <span class="badge badge-danger">Terindikasi Phishing</span>
                @elseif ($scan->status === 'completed' && $scan->prediction === 'legitimate')
                    <span class="badge badge-ok">Tidak ada indikasi</span>
                @elseif ($scan->status === 'failed')
                    <span class="badge badge-warn">Gagal diperiksa</span>
                @else
                    <span class="badge badge-warn">Diproses</span>
                @endif
            </a>
        @empty
            <div class="glass empty">
                <p>Belum ada riwayat pemeriksaan.</p>
                <a href="{{ route('scan.index') }}" class="btn" style="margin-top:.5rem">Periksa URL Pertama</a>
            </div>
        @endforelse

        @if ($scans->hasPages())
            <div class="pager">
                @if ($scans->previousPageUrl())
                    <a href="{{ $scans->previousPageUrl() }}" class="btn btn-ghost">Sebelumnya</a>
                @else
                    <span></span>
                @endif
                @if ($scans->nextPageUrl())
                    <a href="{{ $scans->nextPageUrl() }}" class="btn btn-ghost">Berikutnya</a>
                @endif
            </div>
        @endif

        <p class="note">Hasil model adalah indikasi, bukan jaminan keamanan.</p>
    </div>
@endsection