
@extends('front.layout.main')

@section('title', $post->title . ' - Forum URLinsight')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            {{-- Kembali ke forum --}}
            <a href="{{ route('forum.posts.index') }}"
               class="text-decoration-none text-muted small">
                &larr; Kembali ke Forum
            </a>

            {{-- Detail postingan --}}
            <div class="card border-0 shadow-sm mt-4 mb-4">
                <div class="card-body p-4 p-md-5">

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <span class="badge bg-primary-subtle text-primary">
                            {{ $post->category->name }}
                        </span>

                        <small class="text-muted">
                            {{ $post->created_at->format('d M Y, H:i') }}
                        </small>
                    </div>

                    <h2 class="fw-bold mb-3">
                        {{ $post->title }}
                    </h2>

                    <div class="text-muted small mb-4">
                        Oleh <strong class="text-dark">{{ $post->user->name }}</strong>
                    </div>

                    <hr>

                    {{-- Isi postingan --}}
                    <div class="mt-4" style="white-space: pre-line; line-height: 1.8;">
                        {{ $post->content }}
                    </div>

                    <hr class="my-4">

                    {{-- Statistik --}}
                    <div class="d-flex gap-4 text-muted small">
                        <span>
                            <i class="bi bi-chat"></i>
                            {{ $post->comments_count }} Komentar
                        </span>

                        <span>
                            <i class="bi bi-heart"></i>
                            {{ $post->likes_count }} Suka
                        </span>
                    </div>

                    {{-- Aksi pemilik atau admin --}}
                    @auth
                        @if (auth()->id() === $post->user_id || auth()->user()->role === 'admin')
                            <hr class="my-4">

                            <div class="d-flex gap-2">
                                <a href="{{ route('forum.posts.edit', $post->slug) }}"
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>

                                <form action="{{ route('forum.posts.destroy', $post->slug) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus diskusi ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth

                </div>
            </div>

            {{-- Bagian komentar --}}
            <div class="mb-4">
                <h4 class="fw-bold mb-3">
                    Diskusi dan Komentar
                    <span class="text-muted fs-6">({{ $post->comments_count }})</span>
                </h4>

                @forelse ($post->comments as $comment)
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body p-3 p-md-4">

                            <div class="d-flex justify-content-between flex-wrap gap-2">
                                <strong>{{ $comment->user->name }}</strong>

                                <small class="text-muted">
                                    {{ $comment->created_at->diffForHumans() }}
                                </small>
                            </div>

                            <p class="mt-3 mb-0" style="white-space: pre-line;">
                                {{ $comment->content }}
                            </p>

                            {{-- Balasan --}}
                            @foreach ($comment->replies as $reply)
                                <div class="border-start border-3 ps-3 ms-3 mt-3">
                                    <div class="d-flex justify-content-between flex-wrap gap-2">
                                        <strong class="small">
                                            {{ $reply->user->name }}
                                        </strong>

                                        <small class="text-muted">
                                            {{ $reply->created_at->diffForHumans() }}
                                        </small>
                                    </div>

                                    <p class="small mt-2 mb-0"
                                       style="white-space: pre-line;">
                                        {{ $reply->content }}
                                    </p>
                                </div>
                            @endforeach

                        </div>
                    </div>
                @empty
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-4">
                            <p class="text-muted mb-0">
                                Belum ada komentar. Jadilah yang pertama berdiskusi!
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Form komentar sementara --}}
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Tulis Komentar</h5>

                    @auth
                        <p class="text-muted small">
                            Fitur pengiriman komentar akan kita aktifkan pada tahap berikutnya.
                        </p>
                    @else
                        <p class="text-muted">
                            Silakan <a href="{{ route('login') }}">masuk</a>
                            untuk ikut berdiskusi.
                        </p>
                    @endauth
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
