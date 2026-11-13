<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'URLinsight')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=JetBrains+Mono:wght@400;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/urlinsight.css') }}">
</head>
<body>
    <header class="navbar">
        <div class="container">
            <a href="/" class="brand">
                <svg viewBox="0 0 24 24" fill="none" stroke="#55B8F0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/><path d="M9 12l2 2 4-4"/></svg>
                URLinsight
            </a>
            <nav class="nav-links" aria-label="Navigasi utama">
    <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">Beranda</a>
    <a href="/scan" class="{{ request()->is('scan') ? 'active' : '' }}">URL Checker</a>
    @auth
        <a href="/scan/history" class="{{ request()->is('scan/history') ? 'active' : '' }}">Riwayat</a>
    @endauth
    <a href="/forum" class="{{ request()->is('forum*') ? 'active' : '' }}">Forum</a>
    <a href="/evaluasi" class="{{ request()->is('evaluasi') ? 'active' : '' }}">Evaluasi Model</a>
    <a href="/tentang" class="{{ request()->is('tentang') ? 'active' : '' }}">Tentang</a>
</nav>

<div class="nav-auth">
    @auth
        <span class="nav-user">{{ auth()->user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-ghost btn-sm">Keluar</button>
        </form>
    @else
        <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">Masuk</a>
        <a href="{{ route('register') }}" class="btn btn-sm">Daftar</a>
    @endauth
</div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            URLinsight · Hasil model adalah indikasi, bukan jaminan keamanan.
        </div>
    </footer>
</body>
</html>