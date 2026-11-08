@extends('panel.layout.main')

@section('content')
<div class="container-fluid">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Manajemen Kategori</h3>
            <p class="text-muted mb-0">
                Kelola kategori yang digunakan dalam forum URLinsight.
            </p>
        </div>
    </div>

    {{-- Alert --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Ada kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">

        {{-- Form Tambah Kategori --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Tambah Kategori</h5>

                    <form action="{{ route('panel.forum.categories.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input
                                type="text"
                                name="name"
                                class="form-control"
                                value="{{ old('name') }}"
                                placeholder="Contoh: Keamanan Digital"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea
                                name="description"
                                class="form-control"
                                rows="4"
                                placeholder="Jelaskan kategori ini..."
                            >{{ old('description') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-plus-lg me-1"></i>
                            Tambahkan Kategori
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Daftar Kategori --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Daftar Kategori</h5>
                        <span class="badge bg-primary">
                            {{ $categories->total() }} Kategori
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Nama</th>
                                    <th>Slug</th>
                                    <th>Postingan</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($categories as $category)
                                    <tr>
                                        <td>
                                            <div class="fw-semibold">
                                                {{ $category->name }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $category->description ?: 'Tidak ada deskripsi' }}
                                            </small>
                                        </td>

                                        <td>
                                            <code>{{ $category->slug }}</code>
                                        </td>

                                        <td>
                                            <span class="badge bg-light text-dark">
                                                {{ $category->posts_count }} Postingan
                                            </span>
                                        </td>

                                        <td class="text-end">
                                            <button
                                                class="btn btn-sm btn-outline-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editCategory{{ $category->id }}"
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </button>

                                            <form
                                                action="{{ route('panel.forum.categories.destroy', $category) }}"
                                                method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus kategori ini?')"
                                            >
                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    {{ $category->posts_count > 0 ? 'disabled' : '' }}
                                                    title="{{ $category->posts_count > 0 ? 'Kategori masih digunakan' : 'Hapus kategori' }}"
                                                >
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>

                                    {{-- Modal Edit --}}
                                    <div
                                        class="modal fade"
                                        id="editCategory{{ $category->id }}"
                                        tabindex="-1"
                                        aria-hidden="true"
                                    >
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form
                                                    action="{{ route('panel.forum.categories.update', $category) }}"
                                                    method="POST"
                                                >
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="modal-header">
                                                        <h5 class="modal-title fw-bold">
                                                            Edit Kategori
                                                        </h5>
                                                        <button
                                                            type="button"
                                                            class="btn-close"
                                                            data-bs-dismiss="modal"
                                                        ></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">
                                                                Nama Kategori
                                                            </label>
                                                            <input
                                                                type="text"
                                                                name="name"
                                                                class="form-control"
                                                                value="{{ $category->name }}"
                                                                required
                                                            >
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">
                                                                Deskripsi
                                                            </label>
                                                            <textarea
                                                                name="description"
                                                                class="form-control"
                                                                rows="3"
                                                            >{{ $category->description }}</textarea>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button
                                                            type="button"
                                                            class="btn btn-light"
                                                            data-bs-dismiss="modal"
                                                        >
                                                            Batal
                                                        </button>
                                                        <button type="submit" class="btn btn-primary">
                                                            Simpan Perubahan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-4">
                                            Belum ada kategori.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection