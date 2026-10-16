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
</head>
<body style="display:flex; align-items:center; justify-content:center; min-height:100vh; padding:24px;">
    <div class="auth-card">
        <div style="text-align: center; margin-bottom: 32px;">
            <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" 
                 alt="Cloud Sahaja" style="width: 56px; height: 56px; border-radius: 12px; margin-bottom: 16px;">
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
                <input type="password" name="password" class="input" required autocomplete="new-password">
                @error('password') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="input" required autocomplete="new-password">
                @error('password_confirmation') <div class="form-error">{{ $message }}</div> @enderror
            </div>
            
            <button type="submit" class="btn btn-primary" style="width:100%; margin-top:8px;">
                Daftar
            </button>
        </form>
        
        <div style="text-align: center; margin-top: 24px; font-size: 13px; color: var(--text-secondary);">
            Sudah punya akun? <a href="{{ route('login') }}" style="color: var(--accent); font-weight: 500;">Masuk</a>
        </div>
        
        <div style="text-align: center; margin-top: 12px; font-size: 13px;">
            <a href="https://sahaja-ai.my.id" style="color: var(--text-tertiary);">
                <i class="fas fa-arrow-left"></i> Kembali ke SAHAJA AI
            </a>
        </div>
    </div>
</body>
</html>
