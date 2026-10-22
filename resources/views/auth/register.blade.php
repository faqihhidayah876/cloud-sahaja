<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Cloud Sahaja</title>
    <link rel="icon" type="image/png" href="https://cdn.dbimg.app/TVHxIiqe.png">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700|instrument-serif:400,400i" rel="stylesheet">
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
        <div class="auth-visual" style="background: linear-gradient(rgba(9, 9, 11, 0.8), rgba(9, 9, 11, 0.9)), url('https://images.unsplash.com/photo-1558494949-ef010cbdcc31?q=80&w=2000&auto=format&fit=crop') center/cover;">
            <div class="auth-visual-brand">
                <img src="https://cdn.dbimg.app/WborHsgI.png" alt="Cloud Sahaja">
            </div>
            <div class="auth-visual-content">
                <h1>Mulai Perjalanan Anda.<br>Gratis 2GB.</h1>
                <p>Bergabung dengan mahasiswa dan developer lainnya. Dapatkan ruang penyimpanan pribadi yang aman dan cepat.</p>
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
                    <img src="https://cdn.dbimg.app/FK7NFNaM.png" alt="Cloud Sahaja" style="height: 60px; margin-bottom: 16px; object-fit: contain;">
                    <h2>Buat Akun Baru</h2>
                    <p>Daftar ke Cloud Sahaja sekarang</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="auth-form">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="input" value="{{ old('name') }}" required autofocus placeholder="Nama Kamu">
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="input" value="{{ old('email') }}" required placeholder="nama@email.com">
                        @error('email') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="input-wrapper">
                            <input type="password" name="password" id="passwordInput" class="input" required placeholder="Minimal 8 karakter">
                            <button type="button" class="password-toggle" onclick="togglePassword('passwordInput', this)">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        @error('password') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password</label>
                        <div class="input-wrapper">
                            <input type="password" name="password_confirmation" id="passwordConfirm" class="input" required placeholder="Ulangi password">
                            <button type="button" class="password-toggle" onclick="togglePassword('passwordConfirm', this)">
                                <i class="far fa-eye"></i>
                            </button>
                        </div>
                        @error('password_confirmation') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="turnstile-wrapper">
                        <div id="turnstileContainer" style="width: 100%;"></div>
                    </div>
                    @error('cf-turnstile-response') <div class="form-error" style="text-align:center;">{{ $message }}</div> @enderror

                    <button type="submit" class="btn-auth">Daftar Sekarang</button>
                </form>

                <div class="divider">atau</div>

                <a href="{{ route('auth.google') }}" class="btn-google">
                    <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" width="18" height="18" alt="Google">
                    Daftar dengan Google
                </a>

                <div class="auth-footer">
                    Sudah punya akun? <a href="{{ route('login') }}">Masuk</a>
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
