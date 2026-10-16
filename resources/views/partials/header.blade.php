<header class="header">
    <div style="display: flex; align-items: center; gap: 12px;">
        <button class="mobile-toggle" id="mobileToggle">
            <i class="fas fa-bars"></i>
        </button>
        <h1 style="font-size: 15px; font-weight: 600; color: var(--text-primary);">
            @yield('page-title', 'Beranda')
        </h1>
    </div>
    
    <div style="display: flex; align-items: center; gap: 8px;">
        {{-- Theme toggle --}}
        <button class="icon-btn" onclick="toggleTheme()" title="Toggle theme">
            <i class="fas fa-moon" id="themeIcon"></i>
        </button>
        
        {{-- User dropdown --}}
        <div class="user-menu-wrapper">
            <button class="user-menu-btn">
                <div class="user-avatar-sm">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
                <span style="font-size: 13px; font-weight: 500;">
                    {{ Auth::user()->name ?? 'User' }}
                </span>
                <i class="fas fa-chevron-down" style="font-size: 10px;"></i>
            </button>
            <div class="user-dropdown">
                <a href="#" class="dropdown-item">
                    <i class="fas fa-user"></i> Profil
                </a>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-cog"></i> Pengaturan
                </a>
                <div style="border-top: 1px solid var(--border-subtle); margin: 4px 0;"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item danger" style="width: 100%;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<script>
    // Theme init
    if (localStorage.getItem('cloud-sahaja-theme') === 'light') {
        document.body.classList.add('light-mode');
    }
    updateThemeIcon();
    
    function toggleTheme() {
        const isLight = document.body.classList.toggle('light-mode');
        document.documentElement.classList.toggle('light-mode', isLight);
        localStorage.setItem('cloud-sahaja-theme', isLight ? 'light' : 'dark');
        updateThemeIcon();
    }
    
    function updateThemeIcon() {
        const icon = document.getElementById('themeIcon');
        if (!icon) return;
        const isLight = document.body.classList.contains('light-mode');
        icon.className = isLight ? 'fas fa-sun' : 'fas fa-moon';
    }
    
    // User dropdown toggle
    document.querySelector('.user-menu-btn')?.addEventListener('click', (e) => {
        e.stopPropagation();
        document.querySelector('.user-dropdown')?.classList.toggle('show');
    });
    
    document.addEventListener('click', () => {
        document.querySelector('.user-dropdown')?.classList.remove('show');
    });
    
    // Mobile sidebar toggle
    document.getElementById('mobileToggle')?.addEventListener('click', () => {
        document.getElementById('sidebar')?.classList.toggle('mobile-open');
    });
</script>
