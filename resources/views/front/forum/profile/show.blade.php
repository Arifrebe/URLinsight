@extends('layouts.urlinsight')

@section('title', $user->name . ' - Profil Forum URLinsight')

@section('content')
    <section class="page-head">
        <div class="container">
            <a href="{{ route('forum.posts.index') }}" class="back-link">&larr; Kembali ke Forum</a>
        </div>
    </section>

    <div class="container checker-wrap">

        {{-- Informasi profil --}}
        <div class="glass panel" style="margin-bottom:1.25rem">
            <div class="profile-head">
                <div class="avatar-sm avatar-lg">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <div>
                    <h2>{{ $user->name }}</h2>
                    <span class="cat-badge">{{ $reputation }}</span>
                    <div class="profile-sub">Anggota komunitas URLinsight</div>
                </div>
            </div>

            <div class="profile-stats">
                <div><b>{{ $totalPosts }}</b><span>Postingan</span></div>
                <div><b>{{ $totalComments }}</b><span>Komentar</span></div>
                <div><b>{{ $activityScore }}</b><span>Kontribusi</span></div>
            </div>
        </div>

        <div class="forum-layout">

            {{-- Postingan --}}
            <div class="glass panel">
                <h3 style="margin:0 0 .5rem">Postingan {{ $user->name }}</h3>

                @forelse ($posts as $post)
                    <div class="profile-post">
                        <span class="cat-badge">{{ $post->category->name }}</span>
                        <h3><a href="{{ route('forum.posts.show', $post->slug) }}">{{ $post->title }}</a></h3>
                        <p>{{ \Illuminate\Support\Str::limit(strip_tags($post->content), 150) }}</p>
                        <div class="profile-meta">
                            {{ $post->created_at->format('d M Y') }}
                            · {{ $post->comments_count }} komentar
                            · {{ $post->likes_count }} suka
                        </div>
                    </div>
                @empty
                    <div class="empty">
                        <p>Pengguna ini belum memiliki postingan.</p>
                    </div>
                @endforelse

                @if ($posts->hasPages())
                    <div class="pager">
                        @if ($posts->previousPageUrl())
                            <a href="{{ $posts->previousPageUrl() }}" class="btn btn-ghost btn-sm">Sebelumnya</a>
                        @else
                            <span></span>
                        @endif
                        @if ($posts->nextPageUrl())
                            <a href="{{ $posts->nextPageUrl() }}" class="btn btn-ghost btn-sm">Berikutnya</a>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Komentar terbaru --}}
            <aside class="glass panel">
                <h3 style="margin:0 0 .5rem">Komentar Terbaru</h3>

                @forelse ($comments as $comment)
                    <div class="mini-comment">
                        <p>{{ \Illuminate\Support\Str::limit($comment->content, 100) }}</p>
                        <a href="{{ route('forum.posts.show', $comment->post->slug) }}" class="link" style="font-size:.88rem">Lihat diskusi</a>
                        <div class="profile-meta">{{ $comment->created_at->diffForHumans() }}</div>
                    </div>
                @empty
                    <div class="empty">
                        <p>Belum ada aktivitas komentar.</p>
                    </div>
                @endforelse
            </aside>
        </div>
    </div>
@endsection