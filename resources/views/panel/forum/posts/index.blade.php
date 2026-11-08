
@extends('panel.layout.main')

@section('title', 'Moderasi Postingan')
@section('page-heading', 'Moderasi Postingan')

@section('content')

<div class="container-fluid">

    <div class="mb-4">
        <h3 class="fw-bold mb-1">Moderasi Postingan</h3>
        <p class="text-muted mb-0">
            Kelola dan pantau seluruh postingan komunitas URLinsight.
        </p>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- Pencarian dan Filter --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('panel.forum.posts.index') }}" method="GET">
                <div class="row g-3">

                    <div class="col-md-6">
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Cari judul postingan..."
                            value="{{ request('search') }}"
                        >
                    </div>

                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Semua Status</option>

                            <option value="published"
                                @selected(request('status') === 'published')>
                                Published
                            </option>

                            <option value="hidden"
                                @selected(request('status') === 'hidden')>
                                Hidden
                            </option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-search me-1"></i>
                            Cari
                        </button>

                        <a href="{{ route('panel.forum.posts.index') }}"
                           class="btn btn-light">
                            Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- Tabel Postingan --}}
    <div class="card border-0 shadow-sm">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0">Daftar Postingan</h5>

                <span class="text-muted small">
                    {{ $posts->total() }} postingan ditemukan
                </span>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">

                    <thead>
                        <tr>
                            <th>Postingan</th>
                            <th>Penulis</th>
                            <th>Kategori</th>
                            <th>Aktivitas</th>
                            <th>Status</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($posts as $post)
                            <tr>
                                <td style="min-width: 220px;">
                                    <div class="fw-semibold">
                                        {{ $post->title }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $post->created_at->format('d M Y, H:i') }}
                                    </small>
                                </td>

                                <td>{{ $post->user->name }}</td>

                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $post->category->name }}
                                    </span>
                                </td>

                                <td>
                                    <small>
                                        <i class="bi bi-chat me-1"></i>
                                        {{ $post->comments_count }}

                                        <i class="bi bi-heart ms-2 me-1"></i>
                                        {{ $post->likes_count }}
                                    </small>
                                </td>

                                <td>
                                    @if ($post->status === 'published')
                                        <span class="badge bg-success">Published</span>
                                    @else
                                        <span class="badge bg-secondary">Hidden</span>
                                    @endif
                                </td>

                                <td class="text-end" style="min-width: 160px;">

                                    {{-- Lihat --}}
                                    <a
                                        href="{{ route('forum.posts.show', $post->slug) }}"
                                        target="_blank"
                                        class="btn btn-sm btn-outline-info"
                                        title="Lihat postingan"
                                    >
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    {{-- Ubah Status --}}
                                    <form
                                        action="{{ route('panel.forum.posts.status', $post) }}"
                                        method="POST"
                                        class="d-inline"
                                    >
                                        @csrf
                                        @method('PATCH')

                                        @if ($post->status === 'published')
                                            <input type="hidden" name="status" value="hidden">

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-warning"
                                                title="Sembunyikan"
                                            >
                                                <i class="bi bi-eye-slash"></i>
                                            </button>
                                        @else
                                            <input type="hidden" name="status" value="published">

                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-outline-success"
                                                title="Terbitkan kembali"
                                            >
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                        @endif
                                    </form>

                                    {{-- Hapus --}}
                                    <form
                                        action="{{ route('panel.forum.posts.destroy', $post) }}"
                                        method="POST"
                                        class="d-inline"
                                        onsubmit="return confirm('Yakin ingin menghapus postingan ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-sm btn-outline-danger"
                                            title="Hapus postingan"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>

                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    Tidak ada postingan ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>

            <div class="mt-3">
                {{ $posts->links() }}
            </div>

        </div>
    </div>

</div>

@endsection
