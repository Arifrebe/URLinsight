@extends('layouts.urlinsight')

@section('title', 'Edit Diskusi - URLinsight')

@section('content')
    <section class="page-head">
        <div class="container">
            <a href="{{ route('forum.posts.show', $post->slug) }}" class="back-link">&larr; Kembali ke Diskusi</a>
            <h1 style="margin-top:.8rem">Edit <span class="grad-text">Diskusi</span></h1>
            <p>Perbarui informasi atau isi diskusimu.</p>
        </div>
    </section>

    <div class="container checker-wrap">
        <div class="glass panel" style="max-width:760px">
            <form action="{{ route('forum.posts.update', $post->slug) }}" method="POST" class="auth-form">
                @csrf
                @method('PUT')

                <div class="field">
                    <label for="title">Judul Diskusi</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" required>
                    @error('title')<span class="field-msg">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label for="category_id">Kategori</label>
                    <select name="category_id" id="category_id" required>
                        <option value="">Pilih kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id', $post->category_id) == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<span class="field-msg">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label for="content">Isi Diskusi</label>
                    <textarea name="content" id="content" rows="8" required>{{ old('content', $post->content) }}</textarea>
                    @error('content')<span class="field-msg">{{ $message }}</span>@enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('forum.posts.show', $post->slug) }}" class="btn btn-ghost">Batal</a>
                    <button type="submit" class="btn">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection