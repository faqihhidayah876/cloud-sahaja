<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Tidak Tersedia — Cloud Sahaja</title>
    <link rel="icon" type="image/png" href="https://cdn.dbimg.app/TVHxIiqe.png">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        if (localStorage.getItem('cloud-sahaja-theme') === 'light') {
            document.documentElement.classList.add('light-mode');
        }
    </script>
    @vite(['resources/css/app.css'])
    
    <style>
        body, html {
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--bg-base);
            font-family: 'Outfit', sans-serif;
        }
        .error-card {
            background: var(--bg-elevated);
            border: 1px solid var(--border-medium);
            border-radius: var(--radius-xl);
            padding: 48px 32px;
            text-align: center;
            max-width: 420px;
            width: calc(100% - 32px);
            box-shadow: var(--shadow-lg);
        }
        .error-icon {
            font-size: 64px;
            color: var(--danger);
            margin-bottom: 24px;
            opacity: 0.8;
        }
        .error-title {
            font-size: 20px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 12px;
            line-height: 1.4;
        }
        .error-desc {
            font-size: 14px;
            color: var(--text-secondary);
            line-height: 1.6;
            margin-bottom: 32px;
        }
        .btn-home {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--accent);
            color: #fff;
            padding: 12px 24px;
            border-radius: var(--radius-md);
            text-decoration: none;
            font-weight: 500;
            transition: background 0.2s;
        }
        .btn-home:hover {
            background: var(--accent-hover);
        }
    </style>
</head>
<body>

    <div class="error-card">
        <div class="error-icon">
            <i class="fas fa-link-slash"></i>
        </div>
        <h1 class="error-title" id="errorTitle">Tidak dapat memuat pratinjau file.</h1>
        <p class="error-desc" id="errorDesc">Tautan mungkin sudah kadaluarsa, dicabut oleh pemilik, atau file telah dihapus.</p>
        
        <a href="https://cloud.sahaja-ai.my.id" class="btn-home">
            <i class="fas fa-home"></i> Kembali ke Beranda
        </a>
    </div>

    <script>
        // Logika untuk mengganti pesan saat di-refresh
        document.addEventListener('DOMContentLoaded', () => {
            const titleEl = document.getElementById('errorTitle');
            const descEl = document.getElementById('errorDesc');
            const hasRefreshed = sessionStorage.getItem('errorRefreshed');

            if (!hasRefreshed) {
                // Pesan Pertama Kali
                titleEl.innerHTML = 'Tidak dapat memuat pratinjau PDF di browser ini.';
                descEl.innerHTML = 'Silakan coba lagi nanti atau hubungi pemilik file untuk mendapatkan tautan baru.';
                sessionStorage.setItem('errorRefreshed', 'true');
            } else {
                // Pesan Setelah Refresh
                titleEl.innerHTML = 'Sepertinya ada yang salah dengan pratinjau file ini.';
                descEl.innerHTML = 'Kami tidak tahu dengan ini. Tautan mungkin sudah tidak valid atau server sedang sibuk.';
            }
        });
    </script>
</body>
</html>
