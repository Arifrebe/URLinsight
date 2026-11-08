
@extends('front.layout.main')

@section('title', 'Edit Diskusi - URLinsight')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <a href="{{ route('forum.posts.show', $post->slug) }}"
               class="text-decoration-none text-muted small">
                &larr; Kembali ke Diskusi
            </a>

            <div class="mt-4 mb-4">
                <h2 class="fw-bold">Edit Diskusi</h2>
                <p class="text-muted">
                    Perbarui informasi atau isi diskusimu.
                </p>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">

                    <form action="{{ route('forum.posts.update', $post->slug) }}"
                          method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Judul --}}
                        <div class="mb-3">
                            <label for="title" class="form-label fw-semibold">
                                Judul Diskusi
                            </label>

                            <input
                                type="text"
                                name="title"
                                id="title"
                                class="form-control @error('title') is-invalid @enderror"
                                value="{{ old('title', $post->title) }}"
                                required
                            >

                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Kategori --}}
                        <div class="mb-3">
                            <label for="category_id" class="form-label fw-semibold">
                                Kategori
                            </label>

                            <select
                                name="category_id"
                                id="category_id"
                                class="form-select @error('category_id') is-invalid @enderror"
                                required
                            >
                                <option value="">Pilih kategori</option>

                                @foreach ($categories as $category)
                                    <option
                                        value="{{ $category->id }}"
                                        @selected(old('category_id', $post->category_id) == $category->id)
                                    >
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>

                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Isi diskusi --}}
                        <div class="mb-4">
                            <label for="content" class="form-label fw-semibold">
                                Isi Diskusi
                            </label>

                            <textarea
                                name="content"
                                id="content"
                                rows="8"
                                class="form-control @error('content') is-invalid @enderror"
                                required
                            >{{ old('content', $post->content) }}</textarea>

                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('forum.posts.show', $post->slug) }}"
                               class="btn btn-light">
                                Batal
                            </a>

                            <button type="submit" class="btn btn-primary px-4">
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
