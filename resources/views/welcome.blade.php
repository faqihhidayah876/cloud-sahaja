<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cloud Sahaja — Penyimpanan Pribadi Anda</title>
    <link rel="icon" type="image/png" href="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700|jetbrains-mono:400,500" rel="stylesheet">
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

    <style>
        .welcome-page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: radial-gradient(ellipse 80% 50% at 50% 0%, rgba(59, 130, 246, 0.04) 0%, transparent 70%), var(--bg-base);
            color: var(--text-primary);
        }

        .welcome-navbar {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            padding: 20px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .welcome-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: var(--text-primary);
        }

        .welcome-brand img {
            width: 34px;
            height: 34px;
            border-radius: var(--radius-sm);
        }

        .welcome-brand-text {
            font-size: 16px;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .welcome-nav-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .welcome-container {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* Hero */
        .welcome-hero {
            padding: 64px 0 48px;
            text-align: center;
            max-width: 800px;
            margin: 0 auto;
        }

        .welcome-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            background: var(--bg-subtle);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-full);
            font-size: 12px;
            font-family: var(--font-mono);
            color: var(--text-secondary);
            margin-bottom: 24px;
        }

        .welcome-pill i {
            color: var(--accent);
            font-size: 11px;
        }

        .welcome-title {
            font-size: 40px;
            font-weight: 700;
            letter-spacing: -0.03em;
            line-height: 1.2;
            color: var(--text-primary);
            margin-bottom: 16px;
        }

        .welcome-subtitle {
            font-size: 17px;
            line-height: 1.6;
            color: var(--text-secondary);
            margin-bottom: 32px;
            max-width: 620px;
            margin-left: auto;
            margin-right: auto;
        }

        .welcome-cta-group {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        /* Highlights Bar */
        .welcome-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin: 48px auto 64px;
            max-width: 900px;
        }

        .welcome-stat-card {
            background: var(--bg-subtle);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            padding: 20px;
            text-align: center;
        }

        .welcome-stat-number {
            font-size: 24px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 4px;
            font-family: var(--font-mono);
        }

        .welcome-stat-label {
            font-size: 13px;
            color: var(--text-secondary);
        }

        /* Section */
        .welcome-section {
            padding: 48px 0;
        }

        .welcome-section-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .welcome-section-title {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-primary);
            letter-spacing: -0.02em;
            margin-bottom: 8px;
        }

        .welcome-section-desc {
            font-size: 14px;
            color: var(--text-secondary);
            max-width: 540px;
            margin: 0 auto;
        }

        /* Features Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .feature-card {
            background: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
            transition: border-color var(--duration-base) var(--ease), background-color var(--duration-base) var(--ease);
        }

        .feature-card:hover {
            border-color: var(--border-strong);
            background: var(--bg-hover);
        }

        .feature-icon-wrapper {
            width: 44px;
            height: 44px;
            border-radius: var(--radius-md);
            background: var(--accent-subtle);
            color: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .feature-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 6px;
        }

        .feature-text {
            font-size: 13px;
            line-height: 1.6;
            color: var(--text-secondary);
        }

        /* Why Sahaja Grid */
        .why-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .why-card {
            background: var(--bg-subtle);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            padding: 24px;
        }

        .why-card-icon {
            font-size: 20px;
            color: var(--accent);
            margin-bottom: 16px;
        }

        .why-card-title {
            font-size: 15px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .why-card-text {
            font-size: 13px;
            line-height: 1.6;
            color: var(--text-secondary);
        }

        /* Footer */
        .welcome-footer {
            margin-top: auto;
            border-top: 1px solid var(--border-subtle);
            background: var(--bg-subtle);
            padding: 32px 24px;
        }

        .welcome-footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            font-size: 13px;
            color: var(--text-secondary);
        }

        .welcome-footer-links {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .welcome-footer-links a:hover {
            color: var(--text-primary);
        }

        @media (max-width: 900px) {
            .features-grid,
            .why-grid,
            .welcome-stats {
                grid-template-columns: 1fr;
            }
            .welcome-title {
                font-size: 30px;
            }
            .welcome-subtitle {
                font-size: 15px;
            }
        }
    </style>
</head>
<body class="welcome-page">
    {{-- Header / Navbar --}}
    <header class="welcome-navbar">
        <a href="{{ route('welcome') }}" class="welcome-brand">
            <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" alt="Cloud Sahaja Logo">
            <span class="welcome-brand-text">Cloud Sahaja</span>
        </a>

        <div class="welcome-nav-actions">
            <button type="button" class="btn btn-secondary" onclick="toggleTheme()" title="Ubah Tema" style="width: 40px; padding: 0;">
                <i class="fas fa-sun" id="themeIcon"></i>
            </button>
            <a href="{{ route('login') }}" class="btn btn-secondary">
                Masuk
            </a>
            <a href="{{ route('register') }}" class="btn btn-primary">
                Mulai Gratis
            </a>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="welcome-container">
        {{-- Hero Section --}}
        <section class="welcome-hero">
            <!-- <div class="welcome-pill">
                <i class="fas fa-shield-halved"></i>
                <span>Cloud Storage Pribadi untuk Anda</span>
            </div> -->

            <h1 class="welcome-title">
                Cloud Sahaja — Penyimpanan Pribadi Anda
            </h1>

            <p class="welcome-subtitle">
                Simpan, kelola, dan bagikan file dengan aman. Dibuat untuk mahasiswa, developer, dan tim kecil.
            </p>

            <div class="welcome-cta-group">
                <a href="{{ route('register') }}" class="btn btn-primary" style="height: 44px; padding: 0 24px; font-size: 15px;">
                    <i class="fas fa-arrow-right"></i> Mulai Gratis
                </a>
                <a href="{{ route('login') }}" class="btn btn-secondary" style="height: 44px; padding: 0 24px; font-size: 15px;">
                    <i class="fas fa-right-to-bracket"></i> Masuk ke Akun
                </a>
            </div>
        </section>

        {{-- Stats / Highlights --}}
        <div class="welcome-stats">
            <div class="welcome-stat-card">
                <div class="welcome-stat-number">2 GB</div>
                <div class="welcome-stat-label">Penyimpanan Gratis Awal</div>
            </div>
            <div class="welcome-stat-card">
                <div class="welcome-stat-number">100%</div>
                <div class="welcome-stat-label">Privat & Tanpa Iklan</div>
            </div>
            <div class="welcome-stat-card">
                <div class="welcome-stat-number">30 Hari</div>
                <div class="welcome-stat-label">Retensi Pemulihan Sampah</div>
            </div>
        </div>

        {{-- Section Fitur --}}
        <section class="welcome-section">
            <div class="welcome-section-header">
                <h2 class="welcome-section-title">Fitur Dirancang untuk Kebutuhan Anda</h2>
                <p class="welcome-section-desc">
                    Semua kemudahan pengelolaan file modern dalam satu antarmuka yang bersih dan responsif.
                </p>
            </div>

            <div class="features-grid">
                {{-- 1. Upload Tanpa Batas Format --}}
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="fas fa-cloud-arrow-up"></i>
                    </div>
                    <div>
                        <div class="feature-title">Upload Tanpa Batas Format</div>
                        <div class="feature-text">
                            Dukungan penuh untuk seluruh format file: dokumen PDF/Word, arsip ZIP, gambar, video resolusi tinggi, hingga source code.
                        </div>
                    </div>
                </div>

                {{-- 2. Preview File Langsung --}}
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div>
                        <div class="feature-title">Preview File Langsung</div>
                        <div class="feature-text">
                            Lihat isi PDF interaktif, putar media audio dan video, serta inspeksi gambar langsung di browser tanpa perlu mengunduh terlebih dahulu.
                        </div>
                    </div>
                </div>

                {{-- 3. Share Link dengan Kontrol --}}
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="fas fa-share-nodes"></i>
                    </div>
                    <div>
                        <div class="feature-title">Share Link dengan Kontrol</div>
                        <div class="feature-text">
                            Bagikan berkas via link publik instan dengan opsi masa kedaluwarsa otomatis dan hak akses yang dapat dicabut kapan saja.
                        </div>
                    </div>
                </div>

                {{-- 4. Folder Nested --}}
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="fas fa-folder-tree"></i>
                    </div>
                    <div>
                        <div class="feature-title">Folder Nested</div>
                        <div class="feature-text">
                            Kelompokkan berkas secara berjenjang dan rapi dengan navigasi hierarki serta breadcrumb yang memudahkan pencarian file.
                        </div>
                    </div>
                </div>

                {{-- 5. Sampah 30 Hari --}}
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="fas fa-trash-can"></i>
                    </div>
                    <div>
                        <div class="feature-title">Sampah 30 Hari</div>
                        <div class="feature-text">
                            Berkas yang dihapus tidak langsung hilang permanen. Pulihkan dokumen penting yang tidak sengaja terhapus dengan satu klik.
                        </div>
                    </div>
                </div>

                {{-- 6. Kuota 2GB Gratis --}}
                <div class="feature-card">
                    <div class="feature-icon-wrapper">
                        <i class="fas fa-hard-drive"></i>
                    </div>
                    <div>
                        <div class="feature-title">Kuota 2GB Gratis</div>
                        <div class="feature-text">
                            Langsung dapatkan kuota 2GB cuma-cuma saat pendaftaran akun baru untuk menyimpan arsip tugas kuliah atau proyek coding Anda.
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Section Kenapa Cloud Sahaja --}}
        <section class="welcome-section" style="padding-bottom: 72px;">
            <div class="welcome-section-header">
                <h2 class="welcome-section-title">Kenapa Cloud Sahaja?</h2>
                <p class="welcome-section-desc">
                    Alternatif cloud storage yang fokus pada kesederhanaan, privasi, dan keandalan.
                </p>
            </div>

            <div class="why-grid">
                <div class="why-card">
                    <div class="why-card-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <div class="why-card-title">Cepat & Bebas Hambatan</div>
                    <div class="why-card-text">
                        Antarmuka ringan tanpa popup promosi atau alur rumit. Akses file Anda dalam hitungan detik dari perangkat apa pun.
                    </div>
                </div>

                <div class="why-card">
                    <div class="why-card-icon">
                        <i class="fas fa-shield"></i>
                    </div>
                    <div class="why-card-title">Privasi yang Dihormati</div>
                    <div class="why-card-text">
                        File Anda adalah milik Anda. Tidak ada pelacakan pihak ketiga, tidak ada monetisasi data pribadi, dan penyimpanan terlindungi.
                    </div>
                </div>

                <div class="why-card">
                    <div class="why-card-icon">
                        <i class="fas fa-circle-nodes"></i>
                    </div>
                    <div class="why-card-title">Ekosistem SAHAJA AI</div>
                    <div class="why-card-text">
                        Didesain selaras dengan visi produktivitas SAHAJA AI untuk menunjang kebutuhan belajar mahasiswa dan developer modern.
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- Footer --}}
    <footer class="welcome-footer">
        <div class="welcome-footer-inner">
            <div>
                &copy; {{ date('Y') }} Cloud Sahaja. Bagian dari
                <a href="https://sahaja-ai.my.id" target="_blank" rel="noopener noreferrer" style="color: var(--accent); font-weight: 500;">
                    SAHAJA AI
                </a>.
            </div>

            <div class="welcome-footer-links">
                <a href="{{ route('login') }}">Masuk</a>
                <a href="{{ route('register') }}">Daftar</a>
                <a href="https://sahaja-ai.my.id" target="_blank" rel="noopener noreferrer">Tentang SAHAJA AI</a>
            </div>
        </div>
    </footer>

    <script>
        function updateThemeIcon() {
            const isLight = document.documentElement.classList.contains('light-mode');
            const icon = document.getElementById('themeIcon');
            if (icon) {
                icon.className = isLight ? 'fas fa-moon' : 'fas fa-sun';
            }
        }

        function toggleTheme() {
            const html = document.documentElement;
            html.classList.toggle('light-mode');
            const isLight = html.classList.contains('light-mode');
            localStorage.setItem('cloud-sahaja-theme', isLight ? 'light' : 'dark');
            updateThemeIcon();
        }

        document.addEventListener('DOMContentLoaded', updateThemeIcon);
    </script>
</body>
</html>
