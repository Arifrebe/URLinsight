@extends('panel.layout.main')

@section('title', 'Manajemen Komentar')

@section('content')

<div class="container-fluid">

    {{-- Header halaman --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Manajemen Komentar</h3>
            <p class="text-muted mb-0">
                Kelola komentar pengguna di forum URLinsight.
            </p>
        </div>

        <a href="{{ route('panel.forum.comments.trash') }}"
           class="btn btn-outline-secondary">
            <i class="bi bi-trash me-1"></i>
            Sampah Komentar
        </a>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button"
                    class="btn-close"
                    data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body">

            {{-- Pencarian --}}
            <form action="{{ route('panel.forum.comments.index') }}"
                  method="GET"
                  class="mb-4">

                <div class="row g-2">
                    <div class="col-md-6">
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Cari komentar, penulis, atau postingan..."
                            value="{{ request('search') }}"
                        >
                    </div>

                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search me-1"></i>
                            Cari
                        </button>

                        <a href="{{ route('panel.forum.comments.index') }}"
                           class="btn btn-outline-secondary">
                            Reset
                        </a>
                    </div>
                </div>
            </form>

            {{-- Informasi jumlah komentar --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Daftar Komentar</h5>

                <span class="badge bg-primary">
                    {{ $comments->total() }} Komentar
                </span>
            </div>

            {{-- Tabel komentar --}}
            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th style="min-width: 250px;">Komentar</th>
                            <th style="min-width: 180px;">Postingan</th>
                            <th>Penulis</th>
                            <th class="text-center">Balasan</th>
                            <th>Tanggal</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($comments as $comment)
                            <tr>
                                {{-- Isi komentar --}}
                                <td>
                                    <div class="fw-semibold mb-1">
                                        {{ \Illuminate\Support\Str::limit($comment->content, 100) }}
                                    </div>

                                    @if ($comment->parent_id)
                                        <span class="badge bg-info text-dark">
                                            Balasan komentar
                                        </span>
                                    @else
                                        <span class="badge bg-light text-dark">
                                            Komentar utama
                                        </span>
                                    @endif
                                </td>

                                {{-- Postingan asal --}}
                                <td>
                                    @if ($comment->post)
                                        <a href="{{ route('forum.posts.show', $comment->post->slug) }}"
                                           target="_blank"
                                           class="text-decoration-none">
                                            {{ \Illuminate\Support\Str::limit($comment->post->title, 45) }}
                                        </a>
                                    @else
                                        <span class="text-muted">
                                            Postingan tidak tersedia
                                        </span>
                                    @endif
                                </td>

                                {{-- Penulis --}}
                                <td>
                                    {{ $comment->user->name ?? 'Pengguna tidak tersedia' }}
                                </td>

                                {{-- Jumlah balasan --}}
                                <td class="text-center">
                                    <span class="badge bg-secondary">
                                        {{ $comment->replies_count }}
                                    </span>
                                </td>

                                {{-- Tanggal --}}
                                <td>
                                    <small>
                                        {{ $comment->created_at->format('d M Y') }}
                                        <br>
                                        <span class="text-muted">
                                            {{ $comment->created_at->format('H:i') }}
                                        </span>
                                    </small>
                                </td>

                                {{-- Aksi --}}
                                <td class="text-end">
                                    <form
                                        action="{{ route('panel.forum.comments.destroy', $comment) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus komentar ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="text-center text-muted py-5">
                                    <i class="bi bi-chat-square-text fs-2 d-block mb-2"></i>
                                    Tidak ada komentar ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            {{-- Pagination --}}
            <div class="mt-3">
                {{ $comments->links() }}
            </div>

        </div>
    </div>

</div>

@endsection