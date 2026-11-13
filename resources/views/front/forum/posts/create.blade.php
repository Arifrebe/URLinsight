@extends('layouts.urlinsight')

@section('title', 'Buat Diskusi - URLinsight')

@section('content')
    <section class="page-head">
        <div class="container">
            <a href="{{ route('forum.posts.index') }}" class="back-link">&larr; Kembali ke Forum</a>
            <h1 style="margin-top:.8rem">Buat <span class="grad-text">Diskusi Baru</span></h1>
            <p>Bagikan pertanyaan, pengalaman, atau pengetahuanmu seputar keamanan digital.</p>
        </div>
    </section>

    <div class="container checker-wrap">
        <div class="glass panel" style="max-width:760px">
            <form action="{{ route('forum.posts.store') }}" method="POST" class="auth-form">
                @csrf

                <div class="field">
                    <label for="title">Judul Diskusi</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                        placeholder="Contoh: Bagaimana cara mengenali URL phishing?" required>
                    @error('title')<span class="field-msg">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label for="category_id">Kategori</label>
                    <select name="category_id" id="category_id" required>
                        <option value="">Pilih kategori diskusi</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<span class="field-msg">{{ $message }}</span>@enderror
                </div>

                <div class="field">
                    <label for="content">Isi Diskusi</label>
                    <textarea name="content" id="content" rows="8"
                        placeholder="Tuliskan isi diskusimu secara lengkap..." required>{{ old('content') }}</textarea>
                    @error('content')<span class="field-msg">{{ $message }}</span>@enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('forum.posts.index') }}" class="btn btn-ghost">Batal</a>
                    <button type="submit" class="btn">Publikasikan Diskusi</button>
                </div>
            </form>
        </div>
    </div>
@endsection