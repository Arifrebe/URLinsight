@extends('panel.layout.main')

@section('title', 'Detail Pengguna')

@section('content')
    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Detail Pengguna</h4>
                <p class="text-muted mb-0">
                    Informasi profil dan aktivitas pengguna.
                </p>
            </div>

            <a href="{{ route('panel.users.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>
                Kembali
            </a>
        </div>

        {{-- Profil --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary text-white d-flex
                    align-items-center justify-content-center"
                        style="width: 65px; height: 65px; font-size: 24px;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                        <p class="text-muted mb-1">{{ $user->email }}</p>
                        <small class="text-muted">
                            Bergabung {{ $user->created_at->format('d M Y') }}
                        </small>
                    </div>
                    <div class="mt-3">
                        @if ($user->is_active)
                            <span class="badge bg-success">
                                Akun Aktif
                            </span>
                        @else
                            <span class="badge bg-danger">
                                Akun Dinonaktifkan
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-4">
            @if ($user->is_active)
                <form action="{{ route('panel.users.deactivate', $user) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menonaktifkan akun ini?')">
                    @csrf
                    @method('PATCH')

                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-person-x me-1"></i>
                        Nonaktifkan Akun
                    </button>
                </form>
            @else
                <form action="{{ route('panel.users.activate', $user) }}" method="POST"
                    onsubmit="return confirm('Aktifkan kembali akun ini?')">
                    @csrf
                    @method('PATCH')

                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-person-check me-1"></i>
                        Aktifkan Akun
                    </button>
                </form>
            @endif
        </div>

        {{-- Statistik --}}
        <div class="row g-3 mb-4">

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Total Postingan</div>
                        <h3 class="fw-bold mb-0">
                            {{ $user->forum_posts_count }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Total Komentar</div>
                        <h3 class="fw-bold mb-0">
                            {{ $user->forum_comments_count }}
                        </h3>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="text-muted small">Total Likes</div>
                        <h3 class="fw-bold mb-0">
                            {{ $user->forum_likes_count }}
                        </h3>
                    </div>
                </div>
            </div>

        </div>

        {{-- Daftar Postingan --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Postingan Pengguna</h5>
            </div>

            <div class="card-body">
                @forelse ($posts as $post)
                    <div class="border-bottom py-3">
                        <div class="d-flex justify-content-between gap-3">
                            <div>
                                <a href="{{ route('forum.posts.show', $post->slug) }}"
                                    class="fw-semibold text-decoration-none">
                                    {{ $post->title }}
                                </a>

                                <div class="text-muted small mt-1">
                                    {{ $post->category->name ?? 'Tanpa kategori' }}
                                    · {{ $post->created_at->format('d M Y') }}
                                </div>
                            </div>

                            @if ($post->status === 'published')
                                <span class="badge bg-success align-self-start">
                                    Dipublikasikan
                                </span>
                            @else
                                <span class="badge bg-secondary align-self-start">
                                    Disembunyikan
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center mb-0">
                        Pengguna belum membuat postingan.
                    </p>
                @endforelse

                <div class="mt-3">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>

        {{-- Daftar Komentar --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Komentar Pengguna</h5>
            </div>

            <div class="card-body">
                @forelse ($comments as $comment)
                    <div class="border-bottom py-3">
                        <p class="mb-2">
                            {{ $comment->content }}
                        </p>

                        <div class="text-muted small">
                            Pada postingan:
                            @if ($comment->post)
                                <a href="{{ route('forum.posts.show', $comment->post->slug) }}"
                                    class="text-decoration-none">
                                    {{ $comment->post->title }}
                                </a>
                            @else
                                Postingan tidak tersedia
                            @endif

                            · {{ $comment->created_at->format('d M Y') }}
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center mb-0">
                        Pengguna belum menulis komentar.
                    </p>
                @endforelse

                <div class="mt-3">
                    {{ $comments->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection
