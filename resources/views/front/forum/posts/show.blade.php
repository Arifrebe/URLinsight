@extends('layouts.urlinsight')

@section('title', $post->title . ' - Forum URLinsight')

@section('content')
    <section class="page-head">
        <div class="container">
            <a href="{{ route('forum.posts.index') }}" class="back-link">&larr; Kembali ke Forum</a>
        </div>
    </section>

    <div class="container checker-wrap" style="max-width:860px">

        {{-- Detail postingan --}}
        <div class="glass panel">
            <div class="post-foot" style="border:0;padding:0">
                <span class="cat-badge">{{ $post->category->name }}</span>
                <small style="color:var(--muted)">{{ $post->created_at->format('d M Y, H:i') }}</small>
            </div>

            <h1 class="post-title">{{ $post->title }}</h1>

            <div class="author">
                <div class="avatar-sm avatar-md">{{ strtoupper(substr($post->user->name, 0, 1)) }}</div>
                <div>
                    <a href="{{ route('forum.profile.show', $post->user->id) }}">{{ $post->user->name }}</a>
                    <small>{{ $post->created_at->diffForHumans() }}</small>
                </div>
            </div>

            <div class="post-body">{{ $post->content }}</div>

            <div class="post-foot">
                <div class="stats">
                    <span>{{ $post->comments_count }} Komentar</span>
                    <span>{{ $post->likes_count }} Suka</span>
                </div>

                <div class="action-row">
                    @auth
                        @php
                            $isLiked = $post->likes()->where('user_id', auth()->id())->exists();
                        @endphp
                        <form action="{{ route('forum.likes.toggle', $post->slug) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm {{ $isLiked ? 'btn-liked' : 'btn-ghost' }}">
                                {{ $isLiked ? '♥ Disukai' : '♡ Suka' }} ({{ $post->likes_count }})
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">♡ Suka ({{ $post->likes_count }})</a>
                    @endauth

                    @auth
                        @if ((int) auth()->id() === (int) $post->user_id || auth()->user()->role === 'admin')
                            <a href="{{ route('forum.posts.edit', $post->slug) }}" class="btn btn-ghost btn-sm">Edit</a>

                            <form action="{{ route('forum.posts.destroy', $post->slug) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus diskusi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger-ghost btn-sm">Hapus</button>
                            </form>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        {{-- Komentar --}}
        <div class="section-head" style="margin-top:2rem">
            <h2>Diskusi dan Komentar <span style="color:var(--muted);font-size:1rem">({{ $post->comments_count }})</span></h2>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @forelse ($post->comments as $comment)
            <div class="glass comment">
                <div class="comment-head">
                    <strong>{{ $comment->user->name }}</strong>
                    <small>{{ $comment->created_at->diffForHumans() }}</small>
                </div>

                <p>{{ $comment->content }}</p>

                <div class="action-row">
                    @auth
                        @if ((int) auth()->id() === (int) $comment->user_id || auth()->user()->role === 'admin')
                            <form action="{{ route('forum.comments.destroy', $comment->id) }}" method="POST"
                                onsubmit="return confirm('Hapus komentar ini beserta balasannya?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="link-btn danger">Hapus</button>
                            </form>
                        @endif
                    @endauth
                </div>

                @auth
                    <details class="reply-box">
                        <summary>Balas</summary>
                        <form action="{{ route('forum.comments.store', $post->slug) }}" method="POST" class="reply-form">
                            @csrf
                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                            <input type="text" name="content" placeholder="Tulis balasan..." required minlength="2" maxlength="2000">
                            <button type="submit" class="btn btn-sm">Kirim</button>
                        </form>
                    </details>
                @endauth

                @if ($comment->replies->isNotEmpty())
                    <div class="replies">
                        @foreach ($comment->replies as $reply)
                            <div class="reply-item">
                                <div class="comment-head">
                                    <strong>{{ $reply->user->name }}</strong>
                                    <small>{{ $reply->created_at->diffForHumans() }}</small>
                                </div>
                                <p>{{ $reply->content }}</p>

                                @auth
                                    @if ((int) auth()->id() === (int) $reply->user_id || auth()->user()->role === 'admin')
                                        <form action="{{ route('forum.comments.destroy', $reply->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus balasan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="link-btn danger">Hapus</button>
                                        </form>
                                    @endif
                                @endauth
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <div class="glass empty">
                <h3>Belum Ada Komentar</h3>
                <p>Jadilah orang pertama yang memulai diskusi!</p>
            </div>
        @endforelse

        {{-- Form komentar utama --}}
        <div class="glass panel" style="margin-top:1.25rem">
            <h3 style="margin:0 0 .8rem">Tulis Komentar</h3>

            @auth
                <form action="{{ route('forum.comments.store', $post->slug) }}" method="POST" class="auth-form">
                    @csrf
                    <div class="field">
                        <textarea name="content" rows="4" placeholder="Bagikan pendapat atau tanggapanmu..." required minlength="2" maxlength="2000">{{ old('content') }}</textarea>
                        @error('content')<span class="field-msg">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-actions" style="margin-top:0">
                        <button type="submit" class="btn">Kirim Komentar</button>
                    </div>
                </form>
            @else
                <p class="form-hint" style="margin:0">
                    Kamu harus <a href="{{ route('login') }}" class="link">masuk</a> terlebih dahulu untuk ikut berdiskusi.
                </p>
            @endauth
        </div>
    </div>
@endsection