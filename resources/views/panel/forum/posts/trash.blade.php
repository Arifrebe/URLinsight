@extends('panel.layout.main')

@section('title', 'Sampah Postingan')
@section('page-heading', 'Sampah Postingan')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Sampah Postingan</h3>
                <p class="text-muted mb-0">
                    Postingan yang telah dihapus dan dapat dipulihkan.
                </p>
            </div>

            <a href="{{ route('panel.forum.posts.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-1"></i>
                Kembali
            </a>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm">
            <div class="card-body">

                <div class="d-flex justify-content-between mb-3">
                    <h5 class="fw-bold mb-0">Postingan Terhapus</h5>
                    <span class="badge bg-secondary">
                        {{ $posts->total() }} Postingan
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table align-middle">

                        <thead>
                            <tr>
                                <th>Postingan</th>
                                <th>Penulis</th>
                                <th>Status Sebelumnya</th>
                                <th>Dihapus Pada</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($posts as $post)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">
                                            {{ $post->title }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $post->category->name }}
                                        </small>
                                    </td>

                                    <td>{{ $post->user->name }}</td>

                                    <td>
                                        @if ($post->status === 'published')
                                            <span class="badge bg-success">Published</span>
                                        @else
                                            <span class="badge bg-secondary">Hidden</span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $post->deleted_at->format('d M Y, H:i') }}
                                    </td>

                                    <td class="text-end">
                                        <form action="{{ route('panel.forum.posts.restore', $post->id) }}" method="POST"
                                            class="d-inline" onsubmit="return confirm('Pulihkan postingan ini?')">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-arrow-counterclockwise me-1"></i>
                                                Pulihkan
                                            </button>
                                        </form>
                                        <form action="{{ route('panel.forum.posts.force-destroy', $post->id) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('PERINGATAN! Postingan beserta seluruh komentar, balasan, dan likes akan dihapus permanen. Tindakan ini tidak dapat dibatalkan. Lanjutkan?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-x-circle me-1"></i>
                                                Hapus Permanen
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        Tidak ada postingan di tempat sampah.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>

                {{ $posts->links() }}

            </div>
        </div>

    </div>

@endsection
