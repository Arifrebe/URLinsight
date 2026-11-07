@extends('front.layout.main')

@section('title', 'Forum Diskusi - URLinsight')

@section('content')
    <div class="container py-5">

        {{-- Header forum --}}
        <div class="row align-items-center mb-4">
            <div class="col-md-8">
                <h2 class="fw-bold mb-2">Forum Diskusi</h2>
                <p class="text-muted mb-0">
                    Berbagi pengetahuan, bertanya, dan berdiskusi
                    seputar phishing serta keamanan digital.
                </p>
            </div>

            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                @auth
                    <a href="{{ route('forum.posts.create') }}" class="btn btn-primary px-4">
                        + Buat Diskusi
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary px-4">
                        Masuk untuk Berdiskusi
                    </a>
                @endauth
            </div>
        </div>

        {{-- Daftar diskusi --}}
        <div class="row">
            <div class="col-lg-8">

                {{-- Pencarian dan Filter --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <form action="{{ route('forum.posts.index') }}" method="GET">
                            <div class="row g-2">

                                <div class="col-md-6">
                                    <input type="text" name="search" class="form-control" placeholder="Cari diskusi..."
                                        value="{{ request('search') }}">
                                </div>

                                <div class="col-md-4">
                                    <select name="category" class="form-select">
                                        <option value="">Semua Kategori</option>

                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2 d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search"></i> Cari
                                    </button>
                                </div>

                            </div>

                            @if (request()->filled('search') || request()->filled('category'))
                                <div class="mt-2">
                                    <a href="{{ route('forum.posts.index') }}" class="text-decoration-none small">
                                        <i class="bi bi-x-circle"></i> Reset Filter
                                    </a>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>

                @forelse ($posts as $post)
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body p-4">

                            {{-- Kategori --}}
                            <div class="mb-2">
                                <span class="badge bg-primary-subtle text-primary">
                                    {{ $post->category->name }}
                                </span>
                            </div>

                            {{-- Judul --}}
                            <h5 class="fw-bold mb-2">
                                <a href="{{ route('forum.posts.show', $post->slug) }}"
                                    class="text-decoration-none text-dark">
                                    {{ $post->title }}
                                </a>
                            </h5>

                            {{-- Ringkasan konten --}}
                            <p class="text-muted mb-3">
                                {{ \Illuminate\Support\Str::limit($post->content, 180) }}
                            </p>

                            <hr class="text-secondary opacity-25">

                            {{-- Informasi penulis dan aktivitas --}}
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                                <div class="small text-muted">
                                    <span class="fw-semibold text-dark">
                                        <a href="{{ route('forum.profile.show', $post->user->id) }}"
                                            class="text-decoration-none">
                                            {{ $post->user->name }}
                                        </a>
                                    </span>
                                    <span class="mx-1">·</span>
                                    {{ $post->created_at->format('d M Y') }}
                                </div>

                                <div class="small text-muted">
                                    <span class="me-3">
                                        <i class="bi bi-chat"></i>
                                        {{ $post->comments_count }} Komentar
                                    </span>

                                    <span>
                                        <i class="bi bi-heart"></i>
                                        {{ $post->likes_count }} Suka
                                    </span>
                                </div>

                            </div>
                        </div>
                    </div>

                @empty
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <h5 class="fw-semibold">Belum ada diskusi</h5>
                            <p class="text-muted mb-0">
                                Jadilah orang pertama yang memulai diskusi!
                            </p>
                        </div>
                    </div>
                @endforelse

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $posts->links() }}
                </div>

            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4 mt-4 mt-lg-0">

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Tentang Forum</h5>
                        <p class="text-muted small mb-0">
                            Forum URLinsight merupakan ruang diskusi
                            untuk berbagi informasi dan meningkatkan
                            kesadaran mengenai ancaman keamanan digital.
                        </p>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Panduan Diskusi</h5>
                        <ul class="text-muted small mb-0 ps-3">
                            <li class="mb-2">Gunakan judul yang jelas.</li>
                            <li class="mb-2">Bagikan informasi yang relevan.</li>
                            <li class="mb-2">Hargai pendapat pengguna lain.</li>
                            <li>Hindari membagikan informasi pribadi.</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
