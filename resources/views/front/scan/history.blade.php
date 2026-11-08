@extends('front.layout.main')

@section('title', 'Riwayat Pemindaian - URLinsight')

@section('content')
<section class="py-5">
    <div class="container">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h1 class="fw-bold mb-1">Riwayat Pemindaian</h1>
                <p class="text-secondary mb-0">
                    Daftar URL yang pernah kamu analisis.
                </p>
            </div>

            <a href="{{ route('scan.index') }}" class="btn btn-primary">
                Analisis URL Baru
            </a>
        </div>

        @if ($scans->isEmpty())
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body text-center py-5">
                    <h5 class="fw-bold">Belum Ada Riwayat</h5>
                    <p class="text-secondary">
                        Kamu belum pernah melakukan pemindaian URL.
                    </p>

                    <a href="{{ route('scan.index') }}" class="btn btn-primary">
                        Mulai Analisis
                    </a>
                </div>
            </div>
        @else
            <div class="card border-0 shadow-sm rounded-4">
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">URL</th>
                                <th>Hasil</th>
                                <th>Status</th>
                                <th>Waktu</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($scans as $scan)
                                <tr>
                                    <td class="ps-4" style="min-width: 220px;">
                                        <div class="fw-semibold text-break">
                                            {{ $scan->domain ?? $scan->url }}
                                        </div>

                                        <small class="text-secondary text-break">
                                            {{ $scan->url }}
                                        </small>
                                    </td>

                                    <td>
                                        @if ($scan->prediction === 'phishing')
                                            <span class="badge bg-danger">
                                                Phishing
                                            </span>
                                        @elseif ($scan->prediction === 'legitimate')
                                            <span class="badge bg-success">
                                                Legitimate
                                            </span>
                                        @else
                                            <span class="text-secondary">-</span>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($scan->status === 'completed')
                                            <span class="badge bg-success">
                                                Selesai
                                            </span>
                                        @elseif ($scan->status === 'failed')
                                            <span class="badge bg-danger">
                                                Gagal
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark">
                                                Pending
                                            </span>
                                        @endif
                                    </td>

                                    <td>
                                        <small>
                                            {{ $scan->created_at->format('d/m/Y H:i') }}
                                        </small>
                                    </td>

                                    <td class="text-end pe-4">
                                        <a
                                            href="{{ \Illuminate\Support\Facades\URL::temporarySignedRoute('scan.result', now()->addHours(24), ['uuid' => $scan->uuid]) }}"
                                            class="btn btn-sm btn-outline-primary"
                                        >
                                            Lihat Hasil
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $scans->links() }}
            </div>
        @endif

    </div>
</section>
@endsection
