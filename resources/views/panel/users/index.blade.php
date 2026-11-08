@extends('panel.layout.main')

@section('title', 'Manajemen Pengguna')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Manajemen Pengguna</h4>
                <p class="text-muted mb-0">
                    Kelola dan pantau aktivitas pengguna URLinsight.
                </p>
            </div>
            <span class="badge bg-primary">
                {{ $users->total() }} Pengguna
            </span>
        </div>

        {{-- Pencarian --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('panel.users.index') }}" method="GET">
                    <div class="row g-2">
                        <div class="col-md-10">
                            <input type="text" name="search" class="form-control"
                                placeholder="Cari nama atau email pengguna..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2 d-grid">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search me-1"></i>
                                Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabel pengguna --}}
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Pengguna</th>
                                <th>Postingan</th>
                                <th>Komentar</th>
                                <th>Likes</th>
                                <th>Bergabung</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td>
                                        <div class="fw-semibold">
                                            {{ $user->name }}
                                        </div>
                                        <small class="text-muted">
                                            {{ $user->email }}
                                        </small>
                                    </td>

                                    <td>{{ $user->forum_posts_count }}</td>
                                    <td>{{ $user->forum_comments_count }}</td>
                                    <td>{{ $user->forum_likes_count }}</td>

                                    <td>
                                        {{ $user->created_at->format('d M Y') }}
                                    </td>

                                    <td>
                                        <a href="{{ route('panel.users.show', $user) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <i class="bi bi-people fs-2 text-muted"></i>
                                        <p class="text-muted mb-0 mt-2">
                                            Belum ada pengguna ditemukan.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $users->links() }}
                </div>
            </div>
        </div>

    </div>
@endsection
