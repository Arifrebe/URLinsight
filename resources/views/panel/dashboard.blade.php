@extends('panel.layout.main')

@section('title', 'Dashboard Admin')

@section('content')

    <div class="container-fluid">

        {{-- Header --}}
        <div class="mb-4">
            <h3 class="fw-bold mb-1">Dashboard</h3>
            <p class="text-muted mb-0">
                Ringkasan aktivitas dan statistik forum URLinsight.
            </p>
        </div>

        {{-- Statistik --}}
        <div class="row g-3 mb-4">

            {{-- Pengguna --}}
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted mb-2">Total Pengguna</p>
                                <h3 class="fw-bold mb-0">
                                    {{ number_format($totalUsers) }}
                                </h3>
                            </div>

                            <div class="bg-primary bg-opacity-10 text-primary rounded p-3">
                                <i class="bi bi-people fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Postingan --}}
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted mb-2">Total Postingan</p>
                                <h3 class="fw-bold mb-0">
                                    {{ number_format($totalPosts) }}
                                </h3>
                            </div>

                            <div class="bg-success bg-opacity-10 text-success rounded p-3">
                                <i class="bi bi-file-text fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Komentar --}}
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted mb-2">Total Komentar</p>
                                <h3 class="fw-bold mb-0">
                                    {{ number_format($totalComments) }}
                                </h3>
                            </div>

                            <div class="bg-info bg-opacity-10 text-info rounded p-3">
                                <i class="bi bi-chat-left-text fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Likes --}}
            <div class="col-md-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <p class="text-muted mb-2">Total Likes</p>
                                <h3 class="fw-bold mb-0">
                                    {{ number_format($totalLikes) }}
                                </h3>
                            </div>

                            <div class="bg-danger bg-opacity-10 text-danger rounded p-3">
                                <i class="bi bi-heart fs-4"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Informasi tambahan --}}
        <div class="row g-3 mb-4">

            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="bg-warning bg-opacity-10 text-warning rounded p-3">
                            <i class="bi bi-eye-slash fs-4"></i>
                        </div>

                        <div>
                            <p class="text-muted mb-1">Postingan Tersembunyi</p>
                            <h4 class="fw-bold mb-0">
                                {{ number_format($hiddenPosts) }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="bg-secondary bg-opacity-10 text-secondary rounded p-3">
                            <i class="bi bi-shield-check fs-4"></i>
                        </div>

                        <div>
                            <p class="text-muted mb-1">Status Sistem</p>
                            <h5 class="fw-bold text-success mb-0">
                                Forum Aktif
                            </h5>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Grafik Aktivitas Forum --}}
        <div class="card border-0 shadow-sm mb-4">

            <div class="card-header bg-white border-0 pt-3">
                <div>
                    <h5 class="fw-bold mb-1">Aktivitas Forum</h5>
                    <p class="text-muted small mb-0">
                        Perkembangan postingan dan komentar selama 7 hari terakhir.
                    </p>
                </div>
            </div>

            <div class="card-body">
                <div style="height: 320px;">
                    <canvas id="forumActivityChart"></canvas>
                </div>
            </div>

        </div>

        {{-- Aktivitas terbaru --}}
        <div class="row g-3">

            {{-- Postingan terbaru --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">

                    <div class="card-header bg-white border-0 pt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">Postingan Terbaru</h5>

                            <a href="{{ route('panel.forum.posts.index') }}" class="text-decoration-none small">
                                Lihat semua
                            </a>
                        </div>
                    </div>

                    <div class="card-body">

                        @forelse ($recentPosts as $post)
                            <div class="d-flex justify-content-between align-items-start border-bottom py-3">

                                <div class="me-3">
                                    <a href="{{ route('forum.posts.show', $post->slug) }}" target="_blank"
                                        class="fw-semibold text-dark text-decoration-none">
                                        {{ $post->title }}
                                    </a>

                                    <div class="text-muted small mt-1">
                                        {{ $post->user->name ?? 'Pengguna tidak tersedia' }}
                                        ·
                                        {{ $post->created_at->diffForHumans() }}
                                    </div>
                                </div>

                                @if ($post->status === 'published')
                                    <span class="badge bg-success">Published</span>
                                @else
                                    <span class="badge bg-secondary">Hidden</span>
                                @endif

                            </div>
                        @empty
                            <p class="text-muted text-center py-4 mb-0">
                                Belum ada postingan.
                            </p>
                        @endforelse

                    </div>
                </div>
            </div>

            {{-- Komentar terbaru --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">

                    <div class="card-header bg-white border-0 pt-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">Komentar Terbaru</h5>

                            <a href="{{ route('panel.forum.comments.index') }}" class="text-decoration-none small">
                                Lihat semua
                            </a>
                        </div>
                    </div>

                    <div class="card-body">

                        @forelse ($recentComments as $comment)
                            <div class="border-bottom py-3">

                                <div class="fw-semibold">
                                    {{ $comment->user->name ?? 'Pengguna tidak tersedia' }}
                                </div>

                                <p class="text-muted small mb-1">
                                    {{ \Illuminate\Support\Str::limit($comment->content, 100) }}
                                </p>

                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        Pada:
                                        {{ $comment->post->title ?? 'Postingan tidak tersedia' }}
                                    </small>

                                    <small class="text-muted">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </small>
                                </div>

                            </div>
                        @empty
                            <p class="text-muted text-center py-4 mb-0">
                                Belum ada komentar.
                            </p>
                        @endforelse

                    </div>
                </div>
            </div>

        </div>

    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            const chartLabels = {{ Illuminate\Support\Js::from($chartLabels) }};
            const postData = {{ Illuminate\Support\Js::from($postChartData) }};
            const commentData = {{ Illuminate\Support\Js::from($commentChartData) }};

            const ctx = document.getElementById('forumActivityChart');

            new Chart(ctx, {
                type: 'line',

                data: {
                    labels: chartLabels,

                    datasets: [{
                            label: 'Postingan',
                            data: postData,
                            borderColor: '#2563eb',
                            backgroundColor: 'rgba(37, 99, 235, 0.1)',
                            tension: 0.3,
                            fill: true
                        },
                        {
                            label: 'Komentar',
                            data: commentData,
                            borderColor: '#16a34a',
                            backgroundColor: 'rgba(22, 163, 74, 0.1)',
                            tension: 0.3,
                            fill: true
                        }
                    ]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },

                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },

                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection
