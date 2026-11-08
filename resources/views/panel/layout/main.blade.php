<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Panel Admin') - URLinsight</title>

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body {
            background: #f5f7fb;
            font-family: Arial, sans-serif;
        }

        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #172554;
            position: fixed;
            top: 0;
            left: 0;
            color: white;
            z-index: 1000;
            transition: transform .3s ease;
        }

        .sidebar-brand {
            padding: 25px 22px;
            font-size: 21px;
            font-weight: bold;
            border-bottom: 1px solid rgba(255, 255, 255, .1);
        }

        .sidebar-menu {
            padding: 20px 12px;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            margin-bottom: 5px;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 8px;
            transition: .2s;
        }

        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: #2563eb;
            color: white;
        }

        .menu-label {
            font-size: 11px;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 20px 12px 10px;
        }

        .main-wrapper {
            margin-left: 250px;
            min-height: 100vh;
        }

        .topbar {
            height: 70px;
            background: white;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
        }

        .main-content {
            padding: 30px;
        }

        .sidebar-toggle {
            display: none;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: inline-block;
            }

            .main-content {
                padding: 20px 15px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- Sidebar --}}
    <aside class="sidebar" id="sidebar">

        <div class="sidebar-brand">
            <i class="bi bi-shield-check me-2"></i>
            URLinsight
        </div>

        <div class="sidebar-menu">

            <div class="menu-label">Menu Utama</div>

            <a href="{{ url('/panel') }}" class="{{ request()->is('panel') ? 'active' : '' }}">
                <i class="bi bi-grid"></i>
                Dashboard
            </a>

            <a href="{{ route('panel.users.index') }}" class="{{ request()->is('panel/users*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                Pengguna
            </a>

            <div class="menu-label">Manajemen Forum</div>

            <a href="{{ route('panel.forum.categories.index') }}"
                class="{{ request()->is('panel/forum/categories*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i>
                Kategori
            </a>

            {{-- Menu berikutnya akan ditambahkan --}}
            <a href="{{ route('panel.forum.posts.index') }}"
                class="{{ request()->is('panel/forum/posts') ? 'active' : '' }}">
                <i class="bi bi-file-text"></i>
                Postingan
            </a>
            <a href="{{ route('panel.forum.posts.trash') }}"
                class="{{ request()->is('panel/forum/posts/trash') ? 'active' : '' }}">
                <i class="bi bi-trash"></i>
                Sampah Postingan
            </a>
            <a href="{{ route('panel.forum.comments.index') }}"
                class="{{ request()->is('panel/forum/comments*') ? 'active' : '' }}">
                <i class="bi bi-chat-left-text"></i>
                Komentar
            </a>

            <div class="menu-label">Lainnya</div>

            <a href="{{ route('forum.posts.index') }}">
                <i class="bi bi-box-arrow-up-right"></i>
                Lihat Forum
            </a>

        </div>
    </aside>

    {{-- Main Wrapper --}}
    <div class="main-wrapper">

        {{-- Topbar --}}
        <header class="topbar">

            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light sidebar-toggle" id="sidebarToggle">
                    <i class="bi bi-list fs-5"></i>
                </button>

                <span class="fw-semibold text-dark">
                    @yield('page-heading', 'Dashboard')
                </span>
            </div>

            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle me-1"></i>
                    {{ auth()->user()->name }}
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <span class="dropdown-item-text text-muted small">
                            Administrator
                        </span>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        </header>

        {{-- Content --}}
        <main class="main-content">

            @yield('content')

        </main>

    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>

    @stack('scripts')

</body>

</html>
