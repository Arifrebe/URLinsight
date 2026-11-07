@extends('front.layout.main')

@section('title', $post->title . ' - Forum URLinsight')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                {{-- Tombol kembali --}}
                <a href="{{ route('forum.posts.index') }}" class="text-decoration-none text-muted small">
                    <i class="bi bi-arrow-left"></i> Kembali ke Forum
                </a>

                {{-- Detail postingan --}}
                <div class="card border-0 shadow-sm mt-4 mb-4">
                    <div class="card-body p-4 p-md-5">

                        {{-- Kategori dan tanggal --}}
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                            <span class="badge bg-primary-subtle text-primary">
                                {{ $post->category->name }}
                            </span>

                            <small class="text-muted">
                                {{ $post->created_at->format('d M Y, H:i') }}
                            </small>
                        </div>

                        {{-- Judul --}}
                        <h2 class="fw-bold mb-3">
                            {{ $post->title }}
                        </h2>

                        {{-- Penulis --}}
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

                            @auth
                                @php
                                    $isLiked = $post->likes()->where('user_id', auth()->id())->exists();
                                @endphp

                                <form action="{{ route('forum.likes.toggle', $post->slug) }}" method="POST">
                                    @csrf

                                    <button type="submit"
                                        class="btn btn-sm {{ $isLiked ? 'btn-danger' : 'btn-outline-danger' }}">
                                        <i class="bi bi-heart{{ $isLiked ? '-fill' : '' }}"></i>
                                        {{ $post->likes_count }} Like
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-heart"></i>
                                    {{ $post->likes_count }} Like
                                </a>
                            @endauth
                        </div>

                        {{-- Aksi pemilik postingan atau admin --}}
                        @auth
                            @if ((int) auth()->id() === (int) $post->user_id || auth()->user()->role === 'admin')
                                <hr class="my-4">

                                <div class="d-flex gap-2">
                                    <a href="{{ route('forum.posts.edit', $post->slug) }}"
                                        class="btn btn-outline-primary btn-sm">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>

                                    <form action="{{ route('forum.posts.destroy', $post->slug) }}" method="POST"
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
                        <span class="text-muted fs-6">
                            ({{ $post->comments_count }})
                        </span>
                    </h4>

                    {{-- Pesan berhasil --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    {{-- Pesan error --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Daftar komentar utama --}}
                    @forelse ($post->comments as $comment)

                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body p-3 p-md-4">

                                {{-- Informasi komentar --}}
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <strong>
                                        <i class="bi bi-person-circle me-1"></i>
                                        {{ $comment->user->name }}
                                    </strong>

                                    <small class="text-muted">
                                        {{ $comment->created_at->diffForHumans() }}
                                    </small>
                                </div>

                                {{-- Isi komentar --}}
                                <p class="mt-3 mb-2" style="white-space: pre-line;">
                                    {{ $comment->content }}
                                </p>

                                {{-- Aksi komentar utama --}}
                                <div class="d-flex align-items-center gap-3">

                                    @auth
                                        <button class="btn btn-link btn-sm text-primary p-0 text-decoration-none" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#replyForm{{ $comment->id }}">
                                            <i class="bi bi-reply"></i> Balas
                                        </button>
                                    @endauth

                                    {{-- Hapus komentar --}}
                                    @auth
                                        @if ((int) auth()->id() === (int) $comment->user_id || auth()->user()->role === 'admin')
                                            <form action="{{ route('forum.comments.destroy', $comment->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus komentar ini beserta balasannya?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                    class="btn btn-link btn-sm text-danger p-0 text-decoration-none">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        @endif
                                    @endauth

                                </div>

                                {{-- Form balasan --}}
                                @auth
                                    <div class="collapse mt-3" id="replyForm{{ $comment->id }}">

                                        <form action="{{ route('forum.comments.store', $post->slug) }}" method="POST">
                                            @csrf

                                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">

                                            <div class="input-group">
                                                <input type="text" name="content" class="form-control"
                                                    placeholder="Tulis balasan..." required minlength="2" maxlength="2000">

                                                <button type="submit" class="btn btn-outline-primary">
                                                    Kirim
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                @endauth

                                {{-- Daftar balasan --}}
                                @if ($comment->replies->isNotEmpty())
                                    <div class="border-start border-3 border-primary ps-3 ms-2 mt-4">

                                        @foreach ($comment->replies as $reply)
                                            <div class="mb-3">

                                                {{-- Informasi penulis balasan --}}
                                                <div
                                                    class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                                    <strong class="small">
                                                        <i class="bi bi-person-circle me-1"></i>
                                                        {{ $reply->user->name }}
                                                    </strong>

                                                    <small class="text-muted">
                                                        {{ $reply->created_at->diffForHumans() }}
                                                    </small>
                                                </div>

                                                {{-- Isi balasan --}}
                                                <p class="small mt-2 mb-2" style="white-space: pre-line;">
                                                    {{ $reply->content }}
                                                </p>

                                                {{-- Hapus balasan --}}
                                                @auth
                                                    @if ((int) auth()->id() === (int) $reply->user_id || auth()->user()->role === 'admin')
                                                        <form action="{{ route('forum.comments.destroy', $reply->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Yakin ingin menghapus balasan ini?')">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit"
                                                                class="btn btn-link btn-sm text-danger p-0 text-decoration-none">
                                                                <i class="bi bi-trash"></i> Hapus
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endauth

                                            </div>
                                        @endforeach

                                    </div>
                                @endif

                            </div>
                        </div>

                    @empty

                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center py-5">
                                <i class="bi bi-chat-square-text fs-1 text-muted"></i>

                                <h5 class="fw-semibold mt-3">
                                    Belum Ada Komentar
                                </h5>

                                <p class="text-muted mb-0">
                                    Jadilah orang pertama yang memulai diskusi!
                                </p>
                            </div>
                        </div>

                    @endforelse

                </div>

                {{-- Form komentar utama --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">

                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-chat-left-text me-1"></i>
                            Tulis Komentar
                        </h5>

                        @auth
                            <form action="{{ route('forum.comments.store', $post->slug) }}" method="POST">
                                @csrf

                                <div class="mb-3">
                                    <textarea name="content" rows="4" class="form-control @error('content') is-invalid @enderror"
                                        placeholder="Bagikan pendapat atau tanggapanmu..." required minlength="2" maxlength="2000">{{ old('content') }}</textarea>

                                    @error('content')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="bi bi-send me-1"></i>
                                        Kirim Komentar
                                    </button>
                                </div>
                            </form>
                        @else
                            <p class="text-muted mb-0">
                                Kamu harus
                                <a href="{{ route('login') }}">masuk</a>
                                terlebih dahulu untuk ikut berdiskusi.
                            </p>
                        @endauth

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
