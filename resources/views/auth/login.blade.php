@extends('layouts.urlinsight')

@section('title', 'Masuk - URLinsight')

@section('content')
    <section class="page-head">
        <div class="container">
            <span class="eyebrow">Akun</span>
            <h1><span class="grad-text">Masuk</span> ke URLinsight</h1>
            <p>Masuk untuk menyimpan riwayat pemeriksaan dan ikut forum diskusi.</p>
        </div>
    </section>

    <div class="container checker-wrap">
        <div class="glass panel auth-card">

            @if (session('status'))
                <p class="auth-status">{{ session('status') }}</p>
            @endif

            @if ($errors->any())
                <ul class="auth-errors" role="alert">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form method="POST" action="{{ route('login') }}" class="auth-form">
                @csrf

                <div class="field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" required>
                </div>

                <label class="remember">
                    <input type="checkbox" name="remember"> Ingat saya
                </label>

                <button type="submit" class="btn">Masuk</button>
            </form>

            <p class="form-hint">Belum punya akun? <a href="{{ route('register') }}" class="link">Daftar di sini</a></p>
        </div>
    </div>
@endsection