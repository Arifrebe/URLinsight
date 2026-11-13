@extends('layouts.urlinsight')

@section('title', 'Forum Diskusi - URLinsight')

@section('content')
    <section class="page-head">
        <div class="container">
            <span class="eyebrow">Forum</span>
            <h1>Forum <span class="grad-text">Diskusi</span></h1>
            <p>Berbagi pengetahuan, bertanya, dan berdiskusi seputar phishing serta keamanan digital.</p>
            <div class="hero-actions" style="margin-top:1.2rem">
                @auth
                    <a href="{{ route('forum.posts.create') }}" class="btn">+ Buat Diskusi</a>
                @else
                    <a href="{{ route('login') }}" class="btn">Masuk untuk Berdiskusi</a>
                @endauth
            </div>
        </div>
    </section>

    <div class="container checker-wrap">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="forum-layout">
            <div>
                {{-- Pencarian dan filter --}}
                <div class="glass panel" style="margin-bottom:1rem">
                    <form action="{{ route('forum.posts.index') }}" method="GET">
                        <div class="forum-bar">
                            <input type="text" name="search" placeholder="Cari diskusi..." value="{{ request('search') }}">
                            <select name="category">
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn">Cari</button>
                        </div>
                        @if (request()->filled('search') || request()->filled('category'))
                            <p class="form-hint"><a href="{{ route('forum.posts.index') }}" class="link">Reset filter</a></p>
                        @endif
                    </form>
                </div>

                @forelse ($posts as $post)
                    <div class="glass post-card">
                        <span class="cat-badge">{{ $post->category->name }}</span>
                        <h3><a href="{{ route('forum.posts.show', $post->slug) }}">{{ $post->title }}</a></h3>
                        <p>{{ \Illuminate\Support\Str::limit($post->content, 180) }}</p>

                        <div class="post-foot">
                            <div class="author">
                                <div class="avatar-sm">{{ strtoupper(substr($post->user->name, 0, 1)) }}</div>
                                <div>
                                    <a href="{{ route('forum.profile.show', $post->user->id) }}">{{ $post->user->name }}</a>
                                    <small>{{ $post->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            <div class="stats">
                                <span>{{ $post->comments_count }} Komentar</span>
                                <span>{{ $post->likes_count }} Suka</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="glass empty">
                        <h3>Belum ada diskusi</h3>
                        <p>Jadilah orang pertama yang memulai diskusi!</p>
                    </div>
                @endforelse

                @if ($posts->hasPages())
                    <div class="pager">
                        @if ($posts->previousPageUrl())
                            <a href="{{ $posts->previousPageUrl() }}" class="btn btn-ghost">Sebelumnya</a>
                        @else
                            <span></span>
                        @endif
                        @if ($posts->nextPageUrl())
                            <a href="{{ $posts->nextPageUrl() }}" class="btn btn-ghost">Berikutnya</a>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <aside>
                <div class="glass side-card">
                    <h3>Tentang Forum</h3>
                    <p>Forum URLinsight adalah ruang diskusi untuk berbagi informasi dan meningkatkan kesadaran mengenai ancaman keamanan digital.</p>
                </div>
                <div class="glass side-card">
                    <h3>Panduan Diskusi</h3>
                    <ul>
                        <li>Gunakan judul yang jelas.</li>
                        <li>Bagikan informasi yang relevan.</li>
                        <li>Hargai pendapat pengguna lain.</li>
                        <li>Hindari membagikan informasi pribadi.</li>
                    </ul>
                </div>
            </aside>
        </div>
    </div>
@endsection