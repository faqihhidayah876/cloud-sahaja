<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar — Cloud Sahaja</title>
    <link rel="icon" type="image/png" href="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Theme init anti-FOUC --}}
    <script>
        (function() {
            if (localStorage.getItem('cloud-sahaja-theme') === 'light') {
                document.documentElement.classList.add('light-mode');
            }
        })();
    </script>

    @vite(['resources/css/app.css'])
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
</head>
<body style="display:flex; align-items:center; justify-content:center; min-height:100vh; padding:24px;">
    <div class="auth-card">
        <div style="text-align: center; margin-bottom: 32px;">
            <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png"
                 alt="Cloud Sahaja" style="width: 56px; height: 56px; border-radius: 12px; margin: 0 auto 16px; display: block;">
            <h1 style="font-size: 24px; font-weight: 600; letter-spacing: -0.02em;">
                Buat Akun Baru
            </h1>
            <p style="font-size: 14px; color: var(--text-secondary); margin-top: 6px;">
                Daftar ke Cloud Sahaja
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="name" class="input" value="{{ old('name') }}" required autofocus autocomplete="name">
                @error('name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="input" value="{{ old('email') }}" required autocomplete="username">
                @error('email') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Password</label>
                <div style="position: relative;">
                    <input type="password" name="password" id="passwordInput" class="input" required autocomplete="new-password"
                           style="padding-right: 44px;">
                    <button type="button" onclick="togglePassword('passwordInput', this)"
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
                                   background: none; border: none; color: var(--text-tertiary);
                                   cursor: pointer; padding: 4px; width: 28px; height: 28px;
                                   display: flex; align-items: center; justify-content: center;">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
                @error('password') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Konfirmasi Password</label>
                <div style="position: relative;">
                    <input type="password" name="password_confirmation" id="passwordConfirmInput" class="input" required autocomplete="new-password"
                           style="padding-right: 44px;">
                    <button type="button" onclick="togglePassword('passwordConfirmInput', this)"
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
                                   background: none; border: none; color: var(--text-tertiary);
                                   cursor: pointer; padding: 4px; width: 28px; height: 28px;
                                   display: flex; align-items: center; justify-content: center;">
                        <i class="far fa-eye"></i>
                    </button>
                </div>
                @error('password_confirmation') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="cf-turnstile" data-sitekey="{{ config('services.turnstile.site_key') }}" style="margin-bottom: 16px;"></div>

            <button type="submit" class="btn btn-primary" style="width:100%; margin-top:8px;">
                Daftar
            </button>
        </form>

        <div style="display: flex; align-items: center; margin: 20px 0; gap: 12px; color: var(--text-tertiary); font-size: 12px;">
            <span style="flex: 1; height: 1px; background: var(--border-subtle);"></span>
            <span>atau</span>
            <span style="flex: 1; height: 1px; background: var(--border-subtle);"></span>
        </div>

        <a href="{{ route('auth.google') }}" class="btn btn-secondary" style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px;">
            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" width="20" height="20" alt="Google">
            Masuk dengan Google
        </a>

        <div style="text-align: center; margin-top: 24px; font-size: 13px; color: var(--text-secondary);">
            Sudah punya akun? <a href="{{ route('login') }}" style="color: var(--accent); font-weight: 500;">Masuk</a>
        </div>

        <div style="text-align: center; margin-top: 12px; font-size: 13px;">
            <a href="{{ route('welcome') }}" style="color: var(--text-tertiary);">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>

    <script>
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
