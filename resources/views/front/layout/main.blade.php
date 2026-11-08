<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'URLinsight')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f8fafc;
            color: #1e293b;
        }

        .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .brand-highlight {
            color: #2563eb;
        }

        .main-content {
            min-height: 75vh;
        }

        .footer {
            border-top: 1px solid #e2e8f0;
            background: white;
        }
    </style>

    @stack('styles')
</head>

<body>

    <nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                URL<span class="brand-highlight">insight</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/') }}">Beranda</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('forum.posts.index') }}">Forum</a>
                    </li>

                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Masuk</a>
                        </li>

                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm px-3" href="{{ route('register') }}">
                                Daftar
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('scan.history') }}">
                                Riwayat Scan
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <span class="nav-link">
                                Halo, {{ auth()->user()->name }}
                            </span>
                        </li>

                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="btn btn-outline-danger btn-sm">
                                    Keluar
                                </button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="main-content">
        @yield('content')
    </main>

    <footer class="footer py-4 mt-5">
        <div class="container text-center">
            <p class="mb-1 fw-semibold">URLinsight</p>
            <small class="text-muted">
                Kenali ancaman, pahami risikonya, dan tingkatkan keamanan digital.
            </small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>

</html>
