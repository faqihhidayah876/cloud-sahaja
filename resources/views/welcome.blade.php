<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cloud Sahaja — Penyimpanan Pribadi Anda</title>
    <link rel="icon" type="image/png" href="https://cdn.dbimg.app/TVHxIiqe.png">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700|instrument-serif:400,400i" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>
    <script>
        if (localStorage.getItem('cloud-sahaja-theme') === 'light') {
            document.documentElement.classList.add('light-mode');
        }
    </script>
    @vite(['resources/css/app.css'])

    {{-- ═══════════════════════════════════════════════════════════════
         OVERRIDE CSS — GLASSMORPHISM & HERO LEFT-ALIGN
         ═══════════════════════════════════════════════════════════════ --}}
    <style>
        /* 1. FIX HEADER GLASSMORPHISM (Hilangkan garis kasar) */
        html:not(.light-mode) body .sticky-navbar {
            background: linear-gradient(
                180deg,
                rgba(18, 22, 34, 0.5) 0%,
                rgba(10, 13, 22, 0.35) 100%
            ) !important;
            backdrop-filter: blur(30px) saturate(200%) brightness(1.05) !important;
            -webkit-backdrop-filter: blur(30px) saturate(200%) brightness(1.05) !important;
            /* HAPUS BORDER DAN GANTI DENGAN SHADOW LEMBUT */
            border: none !important;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2) !important;
        }

        html.light-mode body .sticky-navbar,
        body.light-mode .sticky-navbar {
            background: linear-gradient(
                180deg,
                rgba(255, 255, 255, 0.75) 0%,
                rgba(255, 255, 255, 0.55) 100%
            ) !important;
            backdrop-filter: blur(30px) saturate(180%) brightness(1.02) !important;
            -webkit-backdrop-filter: blur(30px) saturate(180%) brightness(1.02) !important;
            /* HAPUS BORDER DAN GANTI DENGAN SHADOW LEMBUT */
            border: none !important;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05) !important;
        }

        /* 2. FIX HERO SECTION JADI RATA KIRI (DESKTOP) */
        .hero-section {
            justify-content: flex-start !important; /* Pindah ke kiri */
            text-align: left !important;
        }

        .hero-content {
            margin: 0 !important; /* Hilangkan auto margin tengah */
            text-align: left !important;
            max-width: 700px;
        }

        .hero-desc {
            margin: 0 0 40px 0 !important; /* Margin bawah saja, kiri-kanan 0 */
        }

        .hero-actions {
            justify-content: flex-start !important; /* Tombol ke kiri */
        }

        /* 3. Video Background Overlay (Agar video lebih menyatu dengan teks) */
        .hero-video-overlay {
            background: linear-gradient(
                to right,
                var(--bg-base) 0%,
                var(--bg-base) 30%,
                rgba(9, 9, 11, 0.6) 60%,
                transparent 100%
            ) !important;
        }

        html.light-mode .hero-video-overlay {
            background: linear-gradient(
                to right,
                #ffffff 0%,
                #ffffff 30%,
                rgba(255, 255, 255, 0.8) 60%,
                transparent 100%
            ) !important;
        }
    </style>
</head>
<body>

    <!-- STICKY NAVBAR (Glassmorphism) -->
    <nav class="sticky-navbar">
        <a href="{{ route('welcome') }}">
            <img src="https://cdn.dbimg.app/FK7NFNaM.png" alt="Cloud Sahaja" class="navbar-logo">
        </a>

        <div class="nav-actions">
            <!-- Tombol Theme Toggle -->
            <button type="button" class="nav-icon-btn" id="themeToggleBtn" onclick="toggleTheme()" title="Ganti Tema">
                <i class="fas fa-moon" id="themeIcon"></i>
            </button>

            <!-- Tombol Login (Ikon Saja) -->
            <a href="{{ route('login') }}" class="nav-login-btn" title="Masuk / Daftar">
                <i class="fas fa-user-circle"></i>
            </a>
        </div>
    </nav>

    <!-- HERO SECTION (Video Bumi Baru) -->
    <section class="hero-section" style="position: relative; min-height: 100vh; display: flex; align-items: center; padding: 120px 48px;">
        <div class="hero-video-container">
            <!-- Link Video Bumi Baru (Pexels - Pasti Jalan) -->
            <video autoplay loop muted playsinline poster="https://images.unsplash.com/photo-1451187580459-43490279c0fa?q=80&w=2072&auto=format&fit=crop">
                <source src="https://videos.pexels.com/video-files/3129671/3129671-uhd_2560_1440_30fps.mp4" type="video/mp4">
            </video>
            <div class="hero-video-overlay"></div>
        </div>

        <div class="hero-content" style="position: relative; z-index: 5;">
            <h1 class="hero-title" style="font-size: clamp(48px, 6vw, 80px); font-weight: 700; line-height: 1.1; letter-spacing: -0.03em; margin-bottom: 24px;">
                Simpan Data.<br><span class="serif-italic" style="font-family: var(--font-serif); font-style: italic; color: var(--accent);">Akses</span> Tanpa Batas.
            </h1>
            <p class="hero-desc" style="font-size: 18px; color: var(--text-secondary); max-width: 500px; line-height: 1.6;">
                Penyimpanan awan pribadi yang dibangun untuk kecepatan, keamanan, dan privasi. Dibuat untuk mahasiswa, developer, dan tim kecil.
            </p>
            <div class="hero-actions" style="display: flex; gap: 16px;">
                <a href="{{ route('register') }}" class="btn-editorial-primary" style="padding: 16px 32px; font-size: 16px; display: inline-flex; align-items: center;">Mulai Gratis <i class="fas fa-arrow-right" style="margin-left:8px;"></i></a>
            </div>
        </div>
    </section>

    <!-- STATS SECTION -->
    <section class="stats-section">
        <div class="stat-box">
            <h3>2GB</h3>
            <p>Gratis Selamanya</p>
        </div>
        <div class="stat-box">
            <h3>∞</h3>
            <p>Ukuran per File</p>
        </div>
        <div class="stat-box">
            <h3>100%</h3>
            <p>Privasi Data</p>
        </div>
        <div class="stat-box">
            <h3>0</h3>
            <p>Iklan / Pelacak</p>
        </div>
    </section>

    <!-- FEATURES SHOWCASE (Z-PATTERN) -->
    <section class="features-showcase">
        <div class="showcase-header">
            <h2>Kenali Platform Kami</h2>
            <h3>Dirancang untuk Performa,<br>Dibangun untuk Privasi.</h3>
        </div>

        <div class="showcase-row">
            <div class="showcase-text">
                <span class="showcase-label">01 — Kecepatan Upload</span>
                <h3>Upload File Besar Tanpa Hambatan.</h3>
                <p>Teknologi Chunked Upload memotong file besar menjadi potongan kecil, lalu menyusunnya kembali di server.</p>
                <ul class="showcase-list">
                    <li><i class="fas fa-check"></i> Upload File besar hingga 2GB</li>
                    <li><i class="fas fa-check"></i> Progress bar real-time per chunk</li>
                </ul>
            </div>
            <div class="showcase-visual">
                <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?q=80&w=1000&auto=format&fit=crop" alt="Speed">
                <div class="badge top-right"><i class="fas fa-tachometer-alt"></i> Speed Tinggi</div>
                <div class="badge bottom-left"><i class="fas fa-check-circle"></i> Upload Selesai</div>
            </div>
        </div>

        <div class="showcase-row reverse">
            <div class="showcase-text">
                <span class="showcase-label">02 — Keamanan Berlapis</span>
                <h3>Proteksi Kelas Enterprise.</h3>
                <p>Data Anda dilindungi oleh keamanan berlapis sekaligus: Cloudflare Turnstile CAPTCHA dan sistem deteksi serangan otomatis dari RICO BOT Server kami.</p>
                <ul class="showcase-list">
                    <li><i class="fas fa-check"></i> Login via Google OAuth</li>
                    <li><i class="fas fa-check"></i> CAPTCHA bot prevention</li>
                </ul>
            </div>
            <div class="showcase-visual">
                <img src="https://i.ibb.co.com/7dpjzTnw/benchmark.jpg" alt="Security">
                <div class="badge top-right"><i class="fas fa-shield-alt"></i> 3 Layer Security</div>
            </div>
        </div>

        <div class="showcase-row">
            <div class="showcase-text">
                <span class="showcase-label">03 — Kolaborasi & Berbagi</span>
                <h3>Bagikan File dengan Mudah.</h3>
                <p>Buat tautan berbagi publik dengan masa aktif pilihan. Preview PDF, video, dan gambar langsung di browser — tanpa download dulu.</p>
                <ul class="showcase-list">
                    <li><i class="fas fa-check"></i> Share link dengan expiry time</li>
                    <li><i class="fas fa-check"></i> Preview file inline</li>
                </ul>
            </div>
            <div class="showcase-visual">
                <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=1000&auto=format&fit=crop" alt="Share">
                <div class="badge top-right"><i class="fas fa-link"></i> Link Dibuat</div>
            </div>
        </div>
    </section>

    <!-- FEATURES GRID -->
    <section style="padding: 100px 48px; max-width: 1300px; margin: 0 auto;">
        <div style="text-align: center; margin-bottom: 60px;">
            <h2 style="font-size: 40px; font-weight: 600;">Semua Fitur</h2>
            <p style="color: var(--text-secondary);">Fitur Tanpa Kompromi. Semua yang Anda butuhkan untuk mengelola file pribadi.</p>
        </div>
        <div class="bento-grid">
            <div class="bento-card large">
                <h3>Upload Tanpa Batas Ukuran</h3>
                <p style="margin-bottom: 24px;">Simpan file hingga Gigabyte dengan sistem chunked upload.</p>
                <div style="background: var(--bg-subtle); padding: 16px; border-radius: 8px;">
                    <div style="display:flex; justify-content:space-between; font-size:12px; margin-bottom:8px; color:var(--text-secondary);"><span>Video 4K.mp4</span><span>78%</span></div>
                    <div style="height: 6px; background: var(--bg-base); border-radius:3px; overflow:hidden;"><div style="width:78%; height:100%; background:var(--accent);"></div></div>
                </div>
            </div>
            <div class="bento-card">
                <h3>Keamanan Berlapis</h3>
                <p>Turnstile CAPTCHA dan proteksi server aktif 24/7. (RICO BOT)</p>
            </div>
            <div class="bento-card">
                <h3>Smart Trash</h3>
                <p>File yang dihapus otomatis dibersihkan setelah 30 hari. Aman, rapi, efisien.</p>
            </div>
            <div class="bento-card">
                <h3>Nested Folder</h3>
                <p>Kelola ratusan file dengan struktur folder bertingkat dan pencarian instan.</p>
            </div>
            <div class="bento-card">
                <h3>Share & Preview</h3>
                <p>Bagikan PDF, video, gambar dengan link. Preview langsung di browser.</p>
            </div>
        </div>
    </section>

    <!-- WHY CLOUD SAHAJA -->
    <section style="padding: 100px 48px; background: var(--bg-subtle); text-align: center;">
        <h2 style="font-size: 40px; font-weight: 600; margin-bottom: 16px;">Kenapa Cloud Sahaja?</h2>
        <p style="color: var(--text-secondary); max-width: 700px; margin: 0 auto 60px;">Kami membangun Cloud Sahaja dengan satu prinsip: data Anda adalah milik Anda, bukan kami. Tidak ada iklan, tidak ada pelacak, tidak ada komersialisasi data.</p>

        <div class="why-us-grid" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 32px; max-width: 1000px; margin: 0 auto;">
            <div>
                <h4 style="font-size: 20px; font-weight: 600; margin-bottom: 8px;">Privasi Absolut</h4>
                <p style="font-size: 14px; color: var(--text-secondary); line-height: 1.6;">Server dikelola sendiri (self-hosted). Kami tidak menjual data Anda ke pihak ketiga.</p>
            </div>
            <div>
                <h4 style="font-size: 20px; font-weight: 600; margin-bottom: 8px;">Dioptimalkan untuk Anda</h4>
                <p style="font-size: 14px; color: var(--text-secondary); line-height: 1.6;">Berjalan dengan efisien di server mandiri. Tetap cepat dan responsif.</p>
            </div>
            <div>
                <h4 style="font-size: 20px; font-weight: 600; margin-bottom: 8px;">Akses dari Mana Saja</h4>
                <p style="font-size: 14px; color: var(--text-secondary); line-height: 1.6;">Berbasis web sepenuhnya. Tidak perlu install aplikasi. Buka browser dan mulai bekerja.</p>
            </div>
        </div>
    </section>

    <!-- MEGA FOOTER -->
    <footer class="mega-footer">
        <div class="footer-grid">
            <div class="footer-col">
                <img src="https://cdn.dbimg.app/FK7NFNaM.png" alt="Cloud Sahaja" style="height: 40px; margin-bottom: 24px;">
                <p style="color: var(--text-secondary); line-height: 1.6; max-width: 300px;">Penyimpanan awan pribadi yang dibangun untuk kecepatan, keamanan, dan privasi. Bagian dari ekosistem SAHAJA AI.</p>
            </div>
            <div class="footer-col">
                <h4>Produk</h4>
                <a href="{{ route('register') }}">Mulai Gratis</a>
                <a href="{{ route('login') }}">Masuk</a>
            </div>
            <div class="footer-col">
                <h4>Sumber Daya</h4>
                <a href="#">Dokumentasi API</a>
                <a href="#">Status Server</a>
            </div>
            <div class="footer-col">
                <h4>Ekosistem</h4>
                <a href="https://sahaja-ai.my.id">SAHAJA AI</a>
                <a href="https://store.sahaja-ai.my.id">SAHAJA Store</a>
                <a href="https://github.com/faqihhidayah876/cloud-sahaja.git" target="_blank">Repository Github</a>
            </div>
        </div>
        <div class="footer-bottom" style="max-width: 1300px; margin: 60px auto 0; padding-top: 24px; border-top: 1px solid var(--border-subtle); display: flex; justify-content: space-between; color: var(--text-tertiary); font-size: 14px;">
            <span>&copy; {{ date('Y') }} Cloud Sahaja. Dibuat oleh Faqih Hidayah.</span>
        </div>
    </footer>

    <!-- GSAP Parallax Video & Text -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
                gsap.registerPlugin(ScrollTrigger);

                gsap.to('.hero-video-container video', {
                    yPercent: 20,
                    ease: 'none',
                    scrollTrigger: {
                        trigger: '.hero-section',
                        start: 'top top',
                        end: 'bottom top',
                        scrub: true
                    }
                });

                gsap.to('.hero-content', {
                    yPercent: -20,
                    opacity: 0.25,
                    ease: 'none',
                    scrollTrigger: {
                        trigger: '.hero-section',
                        start: 'top top',
                        end: 'bottom top',
                        scrub: true
                    }
                });
            }
        });
    </script>
    <script>
        function toggleTheme() {
            const isLight = document.documentElement.classList.toggle('light-mode');
            localStorage.setItem('cloud-sahaja-theme', isLight ? 'light' : 'dark');
            document.getElementById('themeIcon').className = isLight ? 'fas fa-sun' : 'fas fa-moon';
        }
        if (localStorage.getItem('cloud-sahaja-theme') === 'light') {
            document.getElementById('themeIcon').className = 'fas fa-sun';
        }
    </script>
</body>
</html>
