<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Cloud Sahaja</title>
    <link rel="icon" type="image/png" href="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        (function() {
            const savedTheme = localStorage.getItem('cloud-sahaja-theme');
            if (savedTheme === 'light' || (!savedTheme && window.matchMedia('(prefers-color-scheme: light)').matches)) {
                document.documentElement.classList.add('light-mode');
            }
        })();
    </script>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js?onload=onTurnstileLoaded&render=explicit" async defer></script>
    @vite(['resources/css/app.css'])
</head>
<body>
    <div class="auth-container">
        <!-- Sisi Kiri (Visual) -->
        <div class="auth-visual">
            <div class="auth-visual-brand">
                <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" alt="Logo">
                <span>Cloud Sahaja</span>
            </div>
            <div class="auth-visual-content">
                <h1>Simpan Data.<br>Akses Tanpa Batas.</h1>
                <p>Penyimpanan awan pribadi yang dibangun untuk kecepatan, keamanan, dan privasi. Dibuat untuk mahasiswa, developer, dan tim kecil.</p>
            </div>
            <div style="font-size: 12px; opacity: 0.5; z-index: 2;">© {{ date('Y') }} Cloud Sahaja.</div>
        </div>

        <!-- Sisi Kanan (Form) -->
        <div class="auth-form-wrapper">
            <button type="button" class="auth-theme-btn" onclick="toggleAuthTheme()" title="Ubah Tema" aria-label="Ubah Tema">
                <i class="fas fa-sun" id="themeIcon"></i>
            </button>

            <div class="auth-form-box">
                <div class="auth-header">
                    <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" alt="Cloud Sahaja Logo">
                    <h2>Selamat Datang Kembali</h2>
                    <p>Masuk ke akun Cloud Sahaja Anda</p>
                </div>

                @if (session('status'))
                    <div class="alert-success" style="margin-bottom:10px;">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="auth-form">
                    @csrf
                    
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="input" value="{{ old('email') }}" required autofocus placeholder="nama@email.com">
                        @error('email') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-wrapper">
                            <input type="password" name="password" id="passwordInput" class="input" required placeholder="••••••••">
                            <button type="button" class="password-toggle" onclick="togglePassword('passwordInput', this)">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        @error('password') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="turnstile-wrapper">
                        <div id="turnstileContainer" style="width: 100%;"></div>
                    </div>
                    @error('cf-turnstile-response') <div class="form-error" style="text-align:center;">{{ $message }}</div> @enderror

                    <button type="submit" class="btn-auth">Masuk</button>
                </form>

                <div class="divider">atau</div>

                <a href="{{ route('auth.google') }}" class="btn-google">
                    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" width="18" height="18" alt="Google">
                    Masuk dengan Google
                </a>

                <div class="auth-footer">
                    Belum punya akun? <a href="{{ route('register') }}">Daftar</a>
                </div>
                
                <a href="{{ route('welcome') }}" class="back-home">
                    <i class="fas fa-arrow-left"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <script>
        let turnstileWidgetId = null;

        window.onTurnstileLoaded = function() {
            renderTurnstile();
        };

        function renderTurnstile() {
            const container = document.getElementById('turnstileContainer');
            if (!container || typeof turnstile === 'undefined') return;

            if (turnstileWidgetId !== null) {
                try {
                    turnstile.remove(turnstileWidgetId);
                } catch(e) {}
                turnstileWidgetId = null;
            }

            container.innerHTML = '';
            const isLight = document.documentElement.classList.contains('light-mode');

            try {
                turnstileWidgetId = turnstile.render('#turnstileContainer', {
                    sitekey: "{{ config('services.turnstile.site_key') }}",
                    theme: isLight ? 'light' : 'dark',
                    size: 'flexible'
                });
            } catch(e) {
                console.error('Turnstile render error:', e);
            }
        }

        function updateThemeIcon() {
            const isLight = document.documentElement.classList.contains('light-mode');
            const icon = document.getElementById('themeIcon');
            if (icon) icon.className = isLight ? 'fas fa-moon' : 'fas fa-sun';
        }

        function toggleAuthTheme() {
            const isLight = document.documentElement.classList.toggle('light-mode');
            localStorage.setItem('cloud-sahaja-theme', isLight ? 'light' : 'dark');
            updateThemeIcon();
            renderTurnstile();
        }

        document.addEventListener('DOMContentLoaded', function() {
            updateThemeIcon();
            if (typeof turnstile !== 'undefined' && turnstile.render && turnstileWidgetId === null) {
                renderTurnstile();
            }
        });

        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'far fa-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'far fa-eye';
            }
        }
    </script>
</body>
</html>
