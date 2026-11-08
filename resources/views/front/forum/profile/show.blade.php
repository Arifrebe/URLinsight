@extends('front.layout.main')

@section('content')
    <div class="container py-4">


        {{-- Informasi Profil --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">

                <div class="d-flex align-items-center gap-3">

                    {{-- Avatar otomatis --}}
                    <div class="rounded-circle bg-primary text-white
                        d-flex align-items-center justify-content-center
                        flex-shrink-0"
                        style="width: 75px; height: 75px; font-size: 30px;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>

                    <div>
                        <h4 class="mb-1">{{ $user->name }}</h4>

                        <span class="badge bg-primary-subtle text-primary">
                            {{ $reputation }}
                        </span>

                        <div class="text-muted small mt-2">
                            <i class="bi bi-people"></i>
                            Anggota komunitas URLinsight
                        </div>
                    </div>

                </div>

                <hr>

                {{-- Statistik --}}
                <div class="row text-center">

                    <div class="col-4">
                        <h4 class="mb-0">{{ $totalPosts }}</h4>
                        <small class="text-muted">Postingan</small>
                    </div>

                    <div class="col-4">
                        <h4 class="mb-0">{{ $totalComments }}</h4>
                        <small class="text-muted">Komentar</small>
                    </div>

                    <div class="col-4">
                        <h4 class="mb-0">{{ $activityScore }}</h4>
                        <small class="text-muted">Kontribusi</small>
                    </div>

                </div>

            </div>
        </div>


        <div class="row g-4">

            {{-- Daftar Postingan --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Postingan {{ $user->name }}</h5>
                    </div>

                    <div class="card-body">
                        @forelse ($posts as $post)
                            <div class="mb-4">
                                <span class="badge bg-light text-primary">
                                    {{ $post->category->name }}
                                </span>

                                <h5 class="mt-2">
                                    <a href="{{ route('forum.posts.show', $post->slug) }}"
                                        class="text-decoration-none text-dark">
                                        {{ $post->title }}
                                    </a>
                                </h5>

                                <p class="text-muted">
                                    {{ Str::limit(strip_tags($post->content), 150) }}
                                </p>

                                <small class="text-muted">
                                    <i class="bi bi-calendar3"></i>
                                    {{ $post->created_at->format('d M Y') }}

                                    &nbsp;·&nbsp;

                                    <i class="bi bi-chat"></i>
                                    {{ $post->comments_count }} komentar

                                    &nbsp;·&nbsp;

                                    <i class="bi bi-heart"></i>
                                    {{ $post->likes_count }} like
                                </small>
                            </div>

                            @if (!$loop->last)
                                <hr>
                            @endif
                        @empty
                            <div class="text-center py-4">
                                <i class="bi bi-journal-x fs-1 text-muted"></i>
                                <p class="text-muted mt-2 mb-0">
                                    Pengguna ini belum memiliki postingan.
                                </p>
                            </div>
                        @endforelse

                        {{ $posts->links() }}
                    </div>
                </div>
            </div>

            {{-- Aktivitas Komentar --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0">Komentar Terbaru</h5>
                    </div>

                    <div class="card-body">
                        @forelse ($comments as $comment)
                            <div class="mb-3">
                                <p class="mb-1">
                                    {{ Str::limit($comment->content, 100) }}
                                </p>

                                <a href="{{ route('forum.posts.show', $comment->post->slug) }}"
                                    class="small text-decoration-none">
                                    Lihat diskusi
                                    <i class="bi bi-arrow-up-right"></i>
                                </a>

                                <div class="text-muted small mt-1">
                                    {{ $comment->created_at->diffForHumans() }}
                                </div>
                            </div>

                            @if (!$loop->last)
                                <hr>
                            @endif
                        @empty
                            <p class="text-muted text-center mb-0">
                                Belum ada aktivitas komentar.
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
