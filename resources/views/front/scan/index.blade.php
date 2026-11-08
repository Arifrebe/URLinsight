@extends('layouts.urlinsight')

@section('title', 'URL Checker - URLinsight')

@section('content')
    <section class="page-head">
        <div class="container">
            <span class="eyebrow">URL Checker</span>
            <h1>Periksa sebuah <span class="grad-text">URL</span></h1>
            <p>Tempel tautan yang mencurigakan. Tautannya tidak akan dibuka otomatis.</p>
        </div>
    </section>

    <div class="container checker-wrap">
        <div class="glass panel">
            <form class="check-form" action="{{ route('scan.store') }}" method="POST">
                @csrf
                <label for="url">Masukkan URL</label>
                <input type="text" id="url" name="url" value="{{ old('url') }}" placeholder="Contoh: https://example.com/login" autocomplete="off" required>
                <button type="submit" class="btn">Periksa</button>
            </form>

            @error('url')
                <p class="form-error" role="alert">{{ $message }}</p>
            @enderror

            <p class="form-hint">Hasil model adalah indikasi, bukan jaminan keamanan.</p>
        </div>
    </div>
@endsection