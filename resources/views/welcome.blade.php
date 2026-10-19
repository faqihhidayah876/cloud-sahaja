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
            min-width: 0;
            flex-shrink: 1;
        }

        .welcome-brand img {
            width: 34px;
            height: 34px;
            border-radius: var(--radius-sm);
            flex-shrink: 0;
        }

        .welcome-brand-text {
            font-size: 16px;
            font-weight: 700;
            letter-spacing: -0.01em;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
            flex-shrink: 1;
        }

        .brand-title {
            font-size: 16px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (max-width: 480px) {
            .brand-title,
            .welcome-brand-text {
                display: none;
            }
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
            gap: 20px;
            margin: 48px auto 64px;
            max-width: 960px;
        }

        .welcome-stat-card {
            background: var(--bg-subtle);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            padding: 24px 20px;
            text-align: center;
            position: relative;
            overflow: hidden;
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .welcome-stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--accent), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .welcome-stat-card:hover {
            transform: translateY(-6px);
            border-color: var(--accent-border);
            box-shadow: 0 16px 36px rgba(0, 0, 0, 0.25);
        }

        .welcome-stat-card:hover::before {
            opacity: 1;
        }

        .welcome-stat-number {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 6px;
            font-family: var(--font-mono);
            background: linear-gradient(135deg, var(--text-primary) 30%, var(--accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
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
            font-size: 26px;
            font-weight: 700;
            color: var(--text-primary);
            letter-spacing: -0.02em;
            margin-bottom: 8px;
        }

        .welcome-section-desc {
            font-size: 14px;
            color: var(--text-secondary);
            max-width: 560px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* Features Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .feature-card {
            background: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            padding: 28px;
            display: flex;
            flex-direction: column;
            gap: 18px;
            position: relative;
            overflow: hidden;
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1),
                        border-color 0.3s ease,
                        background-color 0.3s ease,
                        box-shadow 0.3s ease;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--accent), transparent);
            opacity: 0;
            transition: opacity 0.35s ease;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            border-color: var(--accent);
            background: var(--bg-hover);
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.12);
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: var(--radius-md);
            background: var(--accent-subtle);
            color: var(--accent);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), background-color 0.3s ease, color 0.3s ease, box-shadow 0.3s ease;
        }

        .feature-card:hover .feature-icon-wrapper {
            transform: scale(1.15) rotate(-6deg);
            background: var(--accent);
            color: #ffffff;
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
        }

        .feature-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
            transition: color 0.2s ease;
        }

        .feature-card:hover .feature-title {
            color: var(--accent);
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
            gap: 24px;
        }

        .why-card {
            background: var(--bg-subtle);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-lg);
            padding: 28px;
            position: relative;
            overflow: hidden;
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .why-card:hover {
            transform: translateY(-6px);
            border-color: var(--accent-border);
            box-shadow: 0 18px 36px rgba(0, 0, 0, 0.2);
        }

        .why-card-icon {
            font-size: 24px;
            color: var(--accent);
            margin-bottom: 16px;
            display: inline-block;
            transition: transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .why-card:hover .why-card-icon {
            transform: scale(1.2) translateY(-2px);
        }

        .why-card-title {
            font-size: 16px;
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

        /* Hero Title Animation */
        .welcome-title {
            font-size: 40px;
            font-weight: 700;
            letter-spacing: -0.03em;
            line-height: 1.2;
            color: var(--text-primary);
            margin-bottom: 16px;
            display: inline-block;
            cursor: pointer;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), filter 0.3s ease;
            position: relative;
        }

        .welcome-title:hover {
            transform: scale(1.04) translateY(-2px);
            filter: drop-shadow(0 6px 24px rgba(59, 130, 246, 0.45));
        }

        .brand-gradient-text {
            background: linear-gradient(135deg, #60A5FA 0%, #3B82F6 45%, #818CF8 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: inline-block;
        }

        body.light-mode .brand-gradient-text {
            background: linear-gradient(135deg, #1D4ED8 0%, #2563EB 50%, #4F46E5 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Custom Cursor Follower - ONLY appears when hovering the hero title */
        .custom-cursor-follower {
            position: fixed;
            top: 0;
            left: 0;
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.32) 0%, rgba(96, 165, 250, 0.14) 55%, transparent 75%);
            border: 2px solid rgba(96, 165, 250, 0.9);
            box-shadow: 0 0 35px rgba(59, 130, 246, 0.5), inset 0 0 20px rgba(59, 130, 246, 0.25);
            pointer-events: none;
            transform: translate(-50%, -50%) scale(0.3);
            z-index: 99999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), visibility 0.25s ease;
            backdrop-filter: blur(2px);
        }

        .custom-cursor-follower.cursor-expanded {
            opacity: 1;
            visibility: visible;
            transform: translate(-50%, -50%) scale(1);
        }

        body.light-mode .custom-cursor-follower {
            background: radial-gradient(circle, rgba(37, 99, 235, 0.28) 0%, rgba(59, 130, 246, 0.12) 55%, transparent 75%);
            border: 2px solid rgba(37, 99, 235, 0.85);
            box-shadow: 0 0 30px rgba(37, 99, 235, 0.3);
        }

        @media (hover: none) and (pointer: coarse) {
            .custom-cursor-follower {
                display: none !important;
            }
        }

        .animated-bg-glow {
            position: fixed;
            inset: -50%;
            z-index: -1;
            background: radial-gradient(circle at 30% 30%, var(--accent-subtle) 0%, transparent 40%),
                        radial-gradient(circle at 70% 70%, rgba(59, 130, 246, 0.08) 0%, transparent 40%);
            animation: bgRotate 20s linear infinite;
            pointer-events: none;
            opacity: 0.6;
        }
        body.light-mode .animated-bg-glow {
            opacity: 0.3;
        }
        @keyframes bgRotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="welcome-page">
    <div class="custom-cursor-follower" id="cursorFollower"></div>
    <div class="animated-bg-glow"></div>

    {{-- Header / Navbar --}}
    <header class="welcome-navbar">
        <a href="{{ route('welcome') }}" class="welcome-brand header-brand">
            <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" alt="Cloud Sahaja Logo">
            <span class="welcome-brand-text brand-title">Cloud Sahaja</span>
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
            {{-- <div class="welcome-pill">
                <i class="fas fa-sparkles"></i>
                <span>Platform Cloud Storage Modern & Cepat</span>
            </div> --}}

            <h1 class="welcome-title" id="heroTitle">
                <span class="brand-gradient-text">Cloud Sahaja</span> — Penyimpanan Pribadi Anda
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

        // Custom Cursor Follower Logic (Only visible on hero title hover)
        (function() {
            const cursor = document.getElementById('cursorFollower');
            const heroTitle = document.getElementById('heroTitle');
            if (!cursor || !heroTitle || !window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;

            let mouseX = -200, mouseY = -200;
            let currentX = -200, currentY = -200;
            let isMoving = false;

            window.addEventListener('mousemove', function(e) {
                mouseX = e.clientX;
                mouseY = e.clientY;
                if (!isMoving) {
                    currentX = mouseX;
                    currentY = mouseY;
                    isMoving = true;
                }
            });

            function updateCursor() {
                // Smooth interpolation (lerp)
                currentX += (mouseX - currentX) * 0.22;
                currentY += (mouseY - currentY) * 0.22;
                cursor.style.left = currentX + 'px';
                cursor.style.top = currentY + 'px';
                requestAnimationFrame(updateCursor);
            }
            requestAnimationFrame(updateCursor);

            heroTitle.addEventListener('mouseenter', function(e) {
                currentX = e.clientX;
                currentY = e.clientY;
                cursor.style.left = currentX + 'px';
                cursor.style.top = currentY + 'px';
                cursor.classList.add('cursor-expanded');
            });

            heroTitle.addEventListener('mouseleave', function() {
                cursor.classList.remove('cursor-expanded');
            });
        })();
    </script>
</body>
</html>
