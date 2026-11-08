@extends('panel.layout.main')

@section('title', 'Sampah Komentar')

@section('content')

    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Sampah Komentar</h3>
                <p class="text-muted mb-0">
                    Kelola komentar yang telah dihapus dari forum.
                </p>
            </div>

            <a href="{{ route('panel.forum.comments.index') }}" class="btn btn-outline-primary">
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

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Komentar Terhapus</h5>

                    <span class="badge bg-secondary">
                        {{ $comments->total() }} Komentar
                    </span>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">

                        <thead class="table-light">
                            <tr>
                                <th style="min-width: 250px;">Komentar</th>
                                <th>Postingan</th>
                                <th>Penulis</th>
                                <th>Dihapus Pada</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($comments as $comment)
                                <tr>
                                    {{-- Isi komentar --}}
                                    <td>
                                        <div class="fw-semibold">
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

                                    {{-- Postingan --}}
                                    <td>
                                        {{ $comment->post->title ?? 'Postingan tidak tersedia' }}
                                    </td>

                                    {{-- Penulis --}}
                                    <td>
                                        {{ $comment->user->name ?? 'Pengguna tidak tersedia' }}
                                    </td>

                                    {{-- Tanggal hapus --}}
                                    <td>
                                        <small>
                                            {{ $comment->deleted_at->format('d M Y, H:i') }}
                                        </small>
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="text-end">
                                        <form action="{{ route('panel.forum.comments.restore', $comment->id) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Yakin ingin memulihkan komentar ini?')">
                                            @csrf
                                            @method('PATCH')

                                            <button type="submit" class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-arrow-counterclockwise me-1"></i>
                                                Pulihkan
                                            </button>
                                        </form>
                                        <form action="{{ route('panel.forum.comments.force-destroy', $comment->id) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('PERINGATAN! Komentar ini akan dihapus permanen dan tidak bisa dipulihkan. Lanjutkan?')">
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
                                    <td colspan="5" class="text-center text-muted py-5">
                                        <i class="bi bi-trash fs-2 d-block mb-2"></i>
                                        Tidak ada komentar di tempat sampah.
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
