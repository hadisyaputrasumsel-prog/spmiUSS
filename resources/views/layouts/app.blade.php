<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AMIRA') - Universitas Sumatera Selatan</title>
    
    <!-- Vite CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Feather Icons -->
    <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-logo-icon">A</div>
                <div style="display:flex; flex-direction:column;">
                    <div class="sidebar-title">AMIRA</div>
                    <div style="font-size: 0.65rem; color: var(--text-muted); line-height: 1.2; margin-top: 2px; font-weight: 500;">Aplikasi Manajemen<br>Audit Mutu Internal</div>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i data-feather="grid" class="nav-icon"></i>
                    Ringkasan
                </a>
                
                @if(in_array(auth()->user()->role->kode, ['super_admin', 'auditee_upps', 'auditee_prodi', 'auditee_unit', 'gpm_upm', 'auditor', 'lpma']))
                <a href="{{ route('led.index') }}" class="nav-item {{ request()->is('led*') ? 'active' : '' }}">
                    <i data-feather="check-square" class="nav-icon"></i>
                    Evaluasi Diri (LED)
                </a>
                @endif
                
                @if(in_array(auth()->user()->role->kode, ['super_admin', 'lpma', 'pimpinan']))
                <a href="{{ route('ami.index') }}" class="nav-item {{ request()->is('ami*') ? 'active' : '' }}">
                    <i data-feather="clipboard" class="nav-icon"></i>
                    Instrumen AMI
                </a>
                @endif
                
                <a href="{{ route('bulan-mutu.index') }}" class="nav-item {{ request()->is('bulan-mutu*') ? 'active' : '' }}">
                    <i data-feather="calendar" class="nav-icon"></i>
                    Bulan Mutu
                </a>
                
                @if(in_array(auth()->user()->role->kode, ['super_admin', 'lpma']))
                <a href="{{ route('standar-mutu.index') }}" class="nav-item {{ request()->is('standar-mutu*') ? 'active' : '' }}">
                    <i data-feather="book-open" class="nav-icon"></i>
                    Standar Mutu
                </a>
                
                <div style="margin-top: 2rem; margin-bottom: 0.5rem; padding: 0 1rem; font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">
                    Sistem
                </div>
                <a href="{{ route('penugasan-auditor.index') }}" class="nav-item {{ request()->is('penugasan-auditor*') ? 'active' : '' }}">
                    <i data-feather="user-check" class="nav-icon"></i>
                    Penugasan Auditor
                </a>
                <a href="{{ route('manajemen-auditee.index') }}" class="nav-item {{ request()->is('manajemen-auditee*') ? 'active' : '' }}">
                    <i data-feather="home" class="nav-icon"></i>
                    Manajemen Auditee
                </a>
                @endif

                @if(auth()->user()->role->kode === 'super_admin')
                <a href="{{ route('akun.index') }}" class="nav-item {{ request()->is('akun*') ? 'active' : '' }}">
                    <i data-feather="users" class="nav-icon"></i>
                    Akun & Peran
                </a>
                @endif
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <h1 class="page-title">@yield('page_title', 'Ringkasan')</h1>
                
                <div class="topbar-actions">
                    <button class="theme-toggle" id="theme-toggle" title="Toggle Dark Mode">
                        <i data-feather="moon"></i>
                    </button>
                    
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div class="user-profile" style="cursor: default;">
                            <div class="user-avatar">{{ substr(auth()->user()->name ?? 'A', 0, 1) }}</div>
                            <div class="user-info">
                                <span class="user-name">{{ auth()->user()->name ?? 'Guest' }}</span>
                                <span class="user-role">{{ auth()->user()->role->nama ?? 'Unknown' }}</span>
                            </div>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="btn btn-outline" style="padding: 0.5rem; border: none;" title="Logout">
                                <i data-feather="log-out" style="width: 18px; height: 18px; color: var(--status-danger);"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="content-area">
                @yield('content')
            </div>
        </main>
    </div>

    <!-- Initialize Icons -->
    <script>
        feather.replace();
        
        // Theme toggle logic
        const themeToggle = document.getElementById('theme-toggle');
        const html = document.documentElement;
        const icon = themeToggle.querySelector('i');
        
        themeToggle.addEventListener('click', () => {
            if (html.getAttribute('data-theme') === 'light') {
                html.setAttribute('data-theme', 'dark');
                icon.setAttribute('data-feather', 'sun');
            } else {
                html.setAttribute('data-theme', 'light');
                icon.setAttribute('data-feather', 'moon');
            }
            feather.replace();
        });
    </script>
    
    @yield('scripts')
</body>
</html>
