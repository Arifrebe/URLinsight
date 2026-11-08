@extends('layouts.urlinsight')

@section('title', 'Daftar - URLinsight')

@section('content')
    <section class="page-head">
        <div class="container">
            <span class="eyebrow">Akun</span>
            <h1><span class="grad-text">Daftar</span> akun baru</h1>
            <p>Buat akun untuk menyimpan riwayat pemeriksaan dan ikut forum diskusi.</p>
        </div>
    </section>

    <div class="container checker-wrap">
        <div class="glass panel auth-card">

            <form method="POST" action="{{ route('register') }}" class="auth-form">
                @csrf

                <div class="field">
                    <label for="name">Nama</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
                    @error('name')
                        <span class="field-msg">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                    @error('email')
                        <span class="field-msg">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                    @error('password')
                        <span class="field-msg">{{ $message }}</span>
                    @enderror
                </div>

                <div class="field">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn">Daftar</button>
            </form>

            <p class="form-hint">Sudah punya akun? <a href="{{ route('login') }}" class="link">Masuk di sini</a></p>
        </div>
    </div>
@endsection