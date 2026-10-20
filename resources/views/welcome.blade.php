<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cloud Sahaja — Penyimpanan Pribadi Anda</title>
    <meta name="description" content="Cloud Sahaja: Penyimpanan awan pribadi yang aman, cepat, dan privat. Simpan, akses, dan bagikan file dari mana saja.">
    <link rel="icon" type="image/png" href="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js"></script>

    <!-- Theme Init (avoid FOUC) -->
    <script>
        if (localStorage.getItem('cloud-sahaja-theme') === 'light') {
            document.documentElement.classList.add('light-mode');
        }
    </script>
    @vite(['resources/css/app.css'])

    <style>
        /* ============================================================
           DESIGN TOKENS
        ============================================================ */
        :root {
            --bg-primary:      #060913;
            --bg-secondary:    #0B1120;
            --bg-card:         rgba(255,255,255,0.04);
            --bg-glass:        rgba(255,255,255,0.06);
            --border:          rgba(255,255,255,0.08);
            --border-strong:   rgba(255,255,255,0.15);
            --accent:          #2563EB;
            --accent-light:    #60A5FA;
            --accent-glow:     rgba(37,99,235,0.35);
            --accent2:         #06B6D4;
            --text-primary:    #F0F4F8;
            --text-secondary:  #94A3B8;
            --text-muted:      #64748B;
            --font-main:       'Plus Jakarta Sans', sans-serif;
            --font-alt:        'Space Grotesk', sans-serif;
            --transition:      cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        html.light-mode, body.light-mode {
            --bg-primary:    #F8FAFC;
            --bg-secondary:  #FFFFFF;
            --bg-card:       rgba(255,255,255,0.9);
            --bg-glass:      rgba(255,255,255,0.85);
            --border:        rgba(15,23,42,0.08);
            --border-strong: rgba(15,23,42,0.15);
            --accent:        #2563EB;
            --accent-light:  #3B82F6;
            --accent-glow:   rgba(37,99,235,0.22);
            --text-primary:  #0F172A;
            --text-secondary:#475569;
            --text-muted:    #94A3B8;
        }

        /* Light mode text color overrides to ensure contrast */
        html.light-mode .hero-title,
        body.light-mode .hero-title,
        html.light-mode .hero-title .title-sub,
        body.light-mode .hero-title .title-sub {
            color: #0F172A !important;
        }
        html.light-mode .hero-desc,
        body.light-mode .hero-desc {
            color: #475569 !important;
        }
        html.light-mode .story-text h2,
        body.light-mode .story-text h2,
        html.light-mode .section-title,
        body.light-mode .section-title,
        html.light-mode .bento-card h3,
        body.light-mode .bento-card h3,
        html.light-mode .trust-card h4,
        body.light-mode .trust-card h4 {
            color: #0F172A !important;
        }

        /* ============================================================
           RESET & BASE
        ============================================================ */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: var(--font-main);
            background: var(--bg-primary);
            color: var(--text-primary);
            overflow-x: hidden;
            transition: background 0.4s, color 0.4s;
            cursor: default;
        }
        img { max-width: 100%; display: block; }
        a { text-decoration: none; }
        button, a, [role="button"] { cursor: pointer; }

        /* ============================================================
           GRAIN OVERLAY
        ============================================================ */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
            opacity: 0.4;
        }

        /* ============================================================
           NAVBAR
        ============================================================ */
        .site-header {
            position: fixed;
            top: 0; left: 0;
            width: 100%;
            height: 68px;
            z-index: 1000;
            padding: 0 28px;
            background: rgba(6, 9, 19, 0.75) !important;
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: none !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.07) !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2) !important;
            pointer-events: auto;
            display: flex;
            align-items: center;
        }
        html.light-mode .site-header,
        body.light-mode .site-header {
            background: rgba(255, 255, 255, 0.88) !important;
            border-bottom: 1px solid rgba(15, 23, 42, 0.07) !important;
            box-shadow: 0 2px 14px rgba(0, 0, 0, 0.04) !important;
        }
        .welcome-navbar {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            height: 100%;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: transparent !important;
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            backdrop-filter: none !important;
            -webkit-backdrop-filter: none !important;
            pointer-events: auto;
        }
        .welcome-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-primary);
        }
        .welcome-brand img { width: 30px; height: 30px; border-radius: 8px; }
        .welcome-brand-text {
            font-family: var(--font-alt);
            font-size: 15px;
            font-weight: 700;
            letter-spacing: -0.02em;
        }
        .welcome-nav-actions { display: flex; align-items: center; gap: 10px; }
        .header-icon-btn {
            width: 40px; height: 40px;
            border-radius: 12px;
            display: inline-flex; align-items: center; justify-content: center;
            background: var(--bg-card);
            border: 1px solid var(--border);
            color: var(--text-secondary);
            font-size: 15px;
            transition: all 0.25s var(--transition);
        }
        .header-icon-btn:hover {
            color: var(--text-primary);
            background: var(--bg-glass);
            border-color: var(--border-strong);
            transform: translateY(-2px);
        }
        .header-btn-register {
            background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%) !important;
            color: #ffffff !important;
            border: 1px solid rgba(59, 130, 246, 0.4) !important;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.35);
        }
        .header-btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 22px rgba(37, 99, 235, 0.55);
            color: #ffffff !important;
        }

        /* DeepSeek-style Circular Cursor Lens Follower */
        .custom-cursor-follower {
            position: fixed;
            top: 0;
            left: 0;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(37, 99, 235, 0.3) 0%, rgba(6, 182, 212, 0.16) 45%, transparent 75%);
            border: 1.5px solid rgba(96, 165, 250, 0.75);
            box-shadow: 0 0 35px rgba(37, 99, 235, 0.45), inset 0 0 20px rgba(37, 99, 235, 0.25);
            pointer-events: none;
            transform: translate(-50%, -50%) scale(0);
            z-index: 99999;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.25s ease, transform 0.35s cubic-bezier(0.34, 1.56, 0.64, 1), visibility 0.25s ease;
            backdrop-filter: blur(3px);
            -webkit-backdrop-filter: blur(3px);
            mix-blend-mode: screen;
        }
        .custom-cursor-follower.cursor-expanded {
            opacity: 1;
            visibility: visible;
            transform: translate(-50%, -50%) scale(1);
        }
        html.light-mode .custom-cursor-follower,
        body.light-mode .custom-cursor-follower {
            background: radial-gradient(circle, rgba(37, 99, 235, 0.2) 0%, rgba(6, 182, 212, 0.1) 45%, transparent 75%);
            border: 1.5px solid rgba(37, 99, 235, 0.55);
            box-shadow: 0 0 25px rgba(37, 99, 235, 0.3);
            mix-blend-mode: multiply;
        }
        @media (hover: none) and (pointer: coarse) {
            .custom-cursor-follower { display: none !important; }
        }

        /* ============================================================
           HERO SECTION
        ============================================================ */
        .hero-section {
            position: relative;
            min-height: 100svh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 180px 24px 90px;
            overflow: hidden;
        }
        .hero-orb {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            filter: blur(80px);
            z-index: 0;
        }
        .orb-1 {
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(37,99,235,0.2) 0%, transparent 70%);
            top: -200px; left: -150px;
            animation: orbFloat1 12s ease-in-out infinite;
        }
        .orb-2 {
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(6,182,212,0.12) 0%, transparent 70%);
            bottom: -100px; right: -100px;
            animation: orbFloat2 15s ease-in-out infinite;
        }
        .orb-3 {
            width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(96,165,250,0.12) 0%, transparent 70%);
            top: 50%; left: 50%;
            transform: translate(-50%,-50%);
            animation: orbFloat3 10s ease-in-out infinite;
        }
        @keyframes orbFloat1 {
            0%,100% { transform: translate(0,0) scale(1); }
            50%      { transform: translate(40px, 60px) scale(1.1); }
        }
        @keyframes orbFloat2 {
            0%,100% { transform: translate(0,0) scale(1); }
            50%      { transform: translate(-50px, -40px) scale(1.08); }
        }
        @keyframes orbFloat3 {
            0%,100% { transform: translate(-50%,-50%) scale(1); }
            50%      { transform: translate(-50%,-50%) scale(1.15); }
        }
        .hero-grid {
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(ellipse 80% 70% at 50% 50%, black 40%, transparent 100%);
            -webkit-mask-image: radial-gradient(ellipse 80% 70% at 50% 50%, black 40%, transparent 100%);
            z-index: 0;
        }
        html.light-mode .hero-grid {
            background-image:
                linear-gradient(rgba(0,0,0,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0,0,0,0.04) 1px, transparent 1px);
        }
        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 900px;
            margin: 32px auto 0;
        }
        .hero-title {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-family: var(--font-alt);
            margin: 0 auto 28px;
            color: var(--text-primary);
            position: relative;
            cursor: pointer;
            user-select: none;
        }
        .hero-title .title-brand {
            display: block;
            font-size: clamp(38px, 7vw, 86px);
            font-weight: 800;
            line-height: 1.18;
            letter-spacing: -0.03em;
            background: linear-gradient(135deg, #A855F7 0%, #6366F1 25%, #38BDF8 50%, #34D399 75%, #F472B6 100%);
            background-size: 250% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 14px;
            animation: titleRainbow 8s ease infinite;
            transform-origin: center center;
            transition: transform 0.4s cubic-bezier(0.34, 1.56, 0.64, 1), filter 0.4s ease;
        }
        .hero-title .title-brand:hover {
            transform: scale(1.08);
            filter: drop-shadow(0 10px 28px rgba(37, 99, 235, 0.45));
        }
        @keyframes titleRainbow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .hero-title .title-sub {
            display: block;
            font-size: clamp(26px, 5.2vw, 68px);
            font-weight: 700;
            line-height: 1.25;
            letter-spacing: -0.03em;
            color: var(--text-primary);
            margin: 0;
            max-width: 900px;
            transition: color 0.4s;
        }
        .hero-desc {
            font-size: clamp(15px, 2vw, 18px);
            color: var(--text-secondary);
            line-height: 1.7;
            max-width: 580px;
            margin: 0 auto 36px;
        }
        .hero-actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .btn-hero-primary {
            display: inline-flex; align-items: center; gap: 8px;
            font-family: var(--font-alt);
            font-size: 15px;
            font-weight: 600;
            color: #fff;
            background: linear-gradient(135deg, #2563EB 0%, #1D4ED8 100%);
            padding: 14px 28px;
            border-radius: 14px;
            border: 1px solid rgba(59, 130, 246, 0.4);
            box-shadow: 0 4px 24px rgba(37, 99, 235, 0.35), inset 0 1px 0 rgba(255,255,255,0.15);
            transition: all 0.3s;
        }
        .btn-hero-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 36px rgba(37, 99, 235, 0.55), inset 0 1px 0 rgba(255,255,255,0.2);
        }
        .btn-hero-secondary {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 15px;
            font-weight: 500;
            color: var(--text-secondary);
            background: var(--bg-card);
            padding: 14px 28px;
            border-radius: 14px;
            border: 1px solid var(--border);
            transition: all 0.3s;
        }
        .btn-hero-secondary:hover {
            color: var(--text-primary);
            background: var(--bg-glass);
            border-color: var(--border-strong);
            transform: translateY(-2px);
        }
        .hero-stats {
            position: relative; z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 40px;
            margin-top: 64px;
            flex-wrap: wrap;
        }
        .hero-stat { text-align: center; }
        .hero-stat-num {
            font-family: var(--font-alt);
            font-size: 28px;
            font-weight: 700;
            background: linear-gradient(135deg, var(--accent-light) 0%, var(--accent2) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-stat-label { font-size: 12px; color: var(--text-muted); margin-top: 2px; letter-spacing: 0.04em; }
        .hero-stat-sep { width: 1px; height: 40px; background: var(--border); }
        .scroll-indicator {
            position: absolute;
            bottom: 32px; left: 50%;
            transform: translateX(-50%);
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            color: var(--text-muted);
            font-size: 11px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            animation: scrollBounce 2.5s ease-in-out infinite;
        }
        .scroll-indicator i { font-size: 16px; }
        @keyframes scrollBounce {
            0%,100% { opacity: 0.4; transform: translateX(-50%) translateY(0); }
            50%      { opacity: 0.9; transform: translateX(-50%) translateY(8px); }
        }

        /* ============================================================
           FLOATING STORIES SECTION
        ============================================================ */
        .stories-section {
            padding: 120px 24px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .stories-header { text-align: center; margin-bottom: 100px; }
        .section-label {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--accent-light);
            margin-bottom: 16px;
        }
        .section-title {
            font-family: var(--font-alt);
            font-size: clamp(28px, 5vw, 52px);
            font-weight: 700;
            letter-spacing: -0.03em;
            line-height: 1.15;
            color: var(--text-primary);
        }
        .section-desc {
            font-size: clamp(14px, 2vw, 17px);
            color: var(--text-secondary);
            line-height: 1.7;
            max-width: 540px;
        }
        .story-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            margin-bottom: 120px;
        }
        .story-row.reverse { direction: rtl; }
        .story-row.reverse > * { direction: ltr; }
        .story-text .step-num {
            font-size: 11px; font-weight: 700;
            letter-spacing: 0.12em; text-transform: uppercase;
            color: var(--accent-light); margin-bottom: 16px; display: block;
        }
        .story-text h2 {
            font-family: var(--font-alt);
            font-size: clamp(26px, 4vw, 44px);
            font-weight: 700;
            letter-spacing: -0.03em;
            line-height: 1.15;
            margin-bottom: 20px;
        }
        .story-text p {
            font-size: clamp(14px, 1.8vw, 16px);
            color: var(--text-secondary);
            line-height: 1.8;
            margin-bottom: 28px;
        }
        .story-feature-list { list-style: none; display: flex; flex-direction: column; gap: 10px; }
        .story-feature-list li {
            display: flex; align-items: center; gap: 10px;
            font-size: 14px; color: var(--text-secondary);
        }
        .story-feature-list li i {
            width: 20px; height: 20px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent2) 100%);
            color: #fff;
            font-size: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .story-visual { position: relative; }
        .floating-wrapper {
            position: relative;
            perspective: 1000px;
        }
        .floating-card {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            border: 1px solid var(--border);
            background: var(--bg-card);
            box-shadow: 0 20px 50px rgba(0,0,0,0.25), inset 0 1px 0 rgba(255,255,255,0.06);
            transform: perspective(1000px) rotateY(-6deg) rotateX(2deg);
            transition: transform 0.7s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.7s cubic-bezier(0.16, 1, 0.3, 1), border-color 0.4s ease;
            will-change: transform;
            cursor: pointer;
        }
        .story-row.reverse .floating-card { transform: perspective(1000px) rotateY(6deg) rotateX(2deg); }
        .floating-card:hover {
            transform: perspective(1000px) rotateY(0deg) rotateX(0deg) translateY(-10px) scale(1.02);
            box-shadow: 0 35px 80px rgba(0,0,0,0.38), 0 0 45px var(--accent-glow);
            border-color: var(--accent-light);
        }
        .floating-card img {
            width: 100%; height: 340px; object-fit: cover;
            transition: transform 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .floating-card:hover img { transform: scale(1.06); }
        .floating-card-overlay {
            position: absolute; bottom: 0; left: 0; right: 0;
            padding: 24px;
            background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 100%);
        }
        .floating-card-badge {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 12px; font-weight: 600;
            color: rgba(255,255,255,0.9);
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 100px; padding: 5px 12px;
        }
        @keyframes floatSoft {
            0%,100% { transform: translateY(0px); }
            50%      { transform: translateY(-12px); }
        }
        @keyframes floatSoftRev {
            0%,100% { transform: translateY(0px); }
            50%      { transform: translateY(12px); }
        }
        .levitate          { animation: floatSoft 6s ease-in-out infinite; }
        .levitate-reverse  { animation: floatSoftRev 7s ease-in-out infinite; }
        .levitate-center   { animation: floatSoft 6.5s ease-in-out infinite; }
        .story-deco {
            position: absolute;
            border-radius: 16px;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            padding: 12px 18px;
            border: 1px solid var(--border);
            background: var(--bg-glass);
            box-shadow: 0 8px 32px rgba(0,0,0,0.2);
            font-size: 13px; font-weight: 500; white-space: nowrap;
            animation: decoFloat 4s ease-in-out infinite; z-index: 2;
        }
        .story-deco-1 { top: -24px; right: -20px; animation-delay: 0s; }
        .story-deco-2 { bottom: -20px; left: -20px; animation-delay: 1s; }
        @keyframes decoFloat {
            0%,100% { transform: translateY(0px); }
            50%      { transform: translateY(-8px); }
        }
        .deco-icon { display: inline-flex; align-items: center; gap: 8px; color: var(--text-primary); }
        .deco-icon i { color: var(--accent-light); }

        /* ============================================================
           BENTO GRID
        ============================================================ */
        .bento-section { padding: 100px 24px; background: var(--bg-secondary); }
        .bento-inner { max-width: 1200px; margin: 0 auto; }
        .bento-header { text-align: center; margin-bottom: 60px; }
        .bento-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 20px;
        }
        .bento-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: 24px; padding: 32px;
            position: relative; overflow: hidden;
            transition: all 0.4s var(--transition);
        }
        .bento-card::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, var(--accent-glow) 0%, transparent 60%);
            opacity: 0; transition: opacity 0.4s; border-radius: inherit;
        }
        .bento-card:hover::before { opacity: 1; }
        .bento-card:hover {
            border-color: var(--border-strong);
            transform: translateY(-4px);
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }
        .bento-col-8  { grid-column: span 8; }
        .bento-col-4  { grid-column: span 4; }
        .bento-col-6  { grid-column: span 6; }
        .bento-col-12 { grid-column: span 12; }
        .bento-icon-wrap {
            width: 52px; height: 52px; border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 20px; font-size: 22px;
        }
        .bento-icon-wrap.purple, .bento-icon-wrap.blue { background: rgba(37,99,235,0.15); color: var(--accent-light); }
        .bento-icon-wrap.cyan   { background: rgba(6,182,212,0.15);   color: var(--accent2); }
        .bento-icon-wrap.green  { background: rgba(52,211,153,0.15);  color: #34D399; }
        .bento-icon-wrap.orange { background: rgba(251,146,60,0.15);  color: #FB923C; }
        .bento-icon-wrap.pink   { background: rgba(236,72,153,0.15);  color: #EC4899; }
        .bento-card h3 {
            font-family: var(--font-alt); font-size: 18px; font-weight: 700;
            letter-spacing: -0.02em; margin-bottom: 10px;
        }
        .bento-card p { font-size: 14px; color: var(--text-secondary); line-height: 1.7; }
        .bento-card-img {
            width: 100%; border-radius: 14px; margin-top: 20px;
            opacity: 0.85; transition: opacity 0.3s, transform 0.4s; object-fit: cover;
        }
        .bento-card:hover .bento-card-img { opacity: 1; transform: scale(1.02); }
        .bento-progress { margin-top: 20px; }
        .bento-progress-label { display: flex; justify-content: space-between; font-size: 12px; color: var(--text-muted); margin-bottom: 8px; }
        .bento-progress-bar { height: 4px; border-radius: 100px; background: var(--border); overflow: hidden; }
        .bento-progress-fill {
            height: 100%; border-radius: 100px;
            background: linear-gradient(90deg, var(--accent) 0%, var(--accent2) 100%);
            transition: width 1.2s var(--transition);
        }
        .bento-shield { position: absolute; right: 24px; bottom: 24px; font-size: 80px; color: rgba(6,182,212,0.07); line-height: 1; }
        .tag-row { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 16px; }
        .tag {
            font-size: 11px; font-weight: 600;
            padding: 4px 10px; border-radius: 100px;
            border: 1px solid var(--border); color: var(--text-secondary);
            background: var(--bg-card);
        }

        /* ============================================================
           TRUST / WHY SECTION
        ============================================================ */
        .trust-section { padding: 100px 24px; max-width: 1200px; margin: 0 auto; }
        .trust-header {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: 60px; align-items: center; margin-bottom: 60px;
        }
        .trust-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; }
        .trust-card {
            padding: 28px; background: var(--bg-card);
            border: 1px solid var(--border); border-radius: 20px; transition: all 0.3s;
        }
        .trust-card:hover { border-color: var(--border-strong); transform: translateY(-3px); }
        .trust-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px; margin-bottom: 16px; }
        .trust-icon.purple, .trust-icon.blue { background: rgba(37,99,235,0.12); color: var(--accent-light); }
        .trust-icon.cyan   { background: rgba(6,182,212,0.12);   color: var(--accent2); }
        .trust-icon.green  { background: rgba(52,211,153,0.12);  color: #34D399; }
        .trust-card h4 { font-family: var(--font-alt); font-size: 16px; font-weight: 700; margin-bottom: 8px; }
        .trust-card p { font-size: 14px; color: var(--text-secondary); line-height: 1.7; }

        /* ============================================================
           FOOTER (Professional Modern Multi-Column)
        ============================================================ */
        .welcome-footer {
            border-top: 1px solid var(--border);
            padding: 80px 24px 36px;
            background: var(--bg-secondary);
            position: relative;
            overflow: hidden;
            transition: background 0.4s, border-color 0.4s;
        }
        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 2.2fr 1fr 1fr 1.2fr;
            gap: 48px;
            margin-bottom: 56px;
        }
        .footer-col-brand {
            max-width: 380px;
        }
        .footer-brand {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-family: var(--font-alt);
            font-size: 19px;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 16px;
            text-decoration: none;
        }
        .footer-brand img { width: 32px; height: 32px; border-radius: 8px; }
        .footer-badge {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 3px 8px;
            border-radius: 100px;
            background: rgba(37,99,235,0.12);
            color: var(--accent-light);
            border: 1px solid rgba(37,99,235,0.25);
            margin-left: 6px;
        }
        .footer-desc {
            font-size: 13.5px;
            color: var(--text-secondary);
            line-height: 1.7;
            margin-bottom: 20px;
        }
        .footer-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 100px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            font-size: 12px;
            color: var(--text-secondary);
        }
        .status-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #10B981;
            box-shadow: 0 0 10px #10B981;
            animation: pulseDot 2s infinite;
        }
        @keyframes pulseDot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.4; transform: scale(0.85); }
        }
        .footer-col h4 {
            font-family: var(--font-alt);
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-primary);
            margin-bottom: 18px;
        }
        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .footer-links a, .footer-links span {
            font-size: 13.5px;
            color: var(--text-secondary);
            transition: color 0.25s, transform 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .footer-links a:hover {
            color: var(--accent-light);
            transform: translateX(3px);
        }
        .footer-links i {
            font-size: 13px;
            width: 16px;
            color: var(--accent-light);
            opacity: 0.85;
        }
        .footer-bottom {
            border-top: 1px solid var(--border);
            padding-top: 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }
        .footer-copy {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.6;
        }
        .footer-copy a {
            color: var(--accent-light);
            text-decoration: none;
            font-weight: 600;
        }
        .footer-tags {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        .footer-tech-tag {
            font-size: 11px;
            font-weight: 500;
            padding: 5px 11px;
            border-radius: 8px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            color: var(--text-muted);
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        /* ============================================================
           REVEAL CLASSES
        ============================================================ */
        .gsap-reveal       { opacity: 0; transform: translateY(40px); }
        .gsap-reveal-left  { opacity: 0; transform: translateX(-40px); }
        .gsap-reveal-right { opacity: 0; transform: translateX(40px); }
        .gsap-reveal-scale { opacity: 0; transform: scale(0.95); }

        /* ============================================================
           MOBILE RESPONSIVE
        ============================================================ */
        @media (max-width: 900px) {
            .story-row {
                grid-template-columns: 1fr;
                gap: 40px;
                direction: ltr !important;
            }
            .floating-card, .story-row.reverse .floating-card {
                transform: perspective(1000px) rotateY(0) rotateX(2deg);
            }
            .floating-card:hover {
                transform: translateY(-6px) scale(1.01) !important;
            }
            .bento-col-8, .bento-col-4, .bento-col-6, .bento-col-12 { grid-column: span 12; }
            .trust-header { grid-template-columns: 1fr; gap: 24px; }
            .story-deco { display: none; }
            .hero-stats { gap: 20px; }
            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 36px;
            }
            .footer-col-brand {
                grid-column: span 2;
                max-width: 100%;
            }
        }
        @media (max-width: 600px) {
            .site-header {
                padding: 0 16px;
                height: 58px;
            }
            .welcome-navbar {
                margin: 0;
                height: 100%;
                padding: 0;
                border: none !important;
                border-radius: 0 !important;
                box-shadow: none !important;
            }
            .hero-section {
                padding: 135px 16px 45px;
                min-height: auto;
            }
            .hero-content {
                margin-top: 10px;
                max-width: 100%;
            }
            /* MOBILE TITLE: BOLD, LARGE, IMPACTFUL - NO MORE "MENCIUT" */
            .hero-title {
                margin-bottom: 16px;
            }
            .hero-title .title-brand {
                font-size: clamp(42px, 11.5vw, 56px);
                line-height: 1.15;
                margin-bottom: 10px;
                font-weight: 800;
                word-break: break-word;
            }
            .hero-title .title-sub {
                font-size: clamp(26px, 7.2vw, 36px);
                line-height: 1.25;
                font-weight: 700;
                word-break: break-word;
                text-wrap: balance;
            }
            .hero-desc {
                font-size: 14.5px;
                line-height: 1.65;
                margin-bottom: 30px;
                padding: 0 6px;
            }
            .hero-actions {
                flex-direction: column;
                width: 100%;
                gap: 12px;
            }
            .btn-hero-primary, .btn-hero-secondary {
                width: 100%;
                justify-content: center;
                padding: 15px 24px;
                font-size: 15px;
            }
            .hero-stats {
                margin-top: 44px;
                gap: 12px;
                width: 100%;
            }
            .hero-stat-num {
                font-size: 24px;
            }
            .hero-stat-label {
                font-size: 11px;
            }
            .hero-stat-sep { display: none; }
            /* HIDE SCROLL INDICATOR ON MOBILE TO PREVENT OVERLAPPING STATS */
            .scroll-indicator {
                display: none !important;
            }
            /* FOOTER MOBILE */
            .welcome-footer {
                padding: 50px 18px 30px;
            }
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 32px;
                margin-bottom: 40px;
            }
            .footer-col-brand {
                grid-column: span 1;
            }
            .footer-bottom {
                flex-direction: column;
                text-align: center;
                gap: 16px;
            }
            .footer-tags {
                justify-content: center;
            }
        }
    </style>
</head>
<body>

    <!-- DeepSeek Circular Cursor Lens (Expands on hero title hover) -->
    <div id="cursorFollower" class="custom-cursor-follower"></div>

    <!-- NAVBAR -->
    <header class="site-header">
        <nav class="welcome-navbar" id="mainNav">
            <a href="{{ route('welcome') }}" class="welcome-brand">
                <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" alt="Cloud Sahaja Logo">
                <span class="welcome-brand-text">Cloud Sahaja</span>
            </a>
            <div class="welcome-nav-actions">
                <button type="button" class="header-icon-btn" onclick="toggleTheme()" title="Ubah Tema" aria-label="Ubah Tema">
                    <i class="fas fa-sun" id="themeIcon"></i>
                </button>
                <a href="{{ route('register') }}" class="header-icon-btn header-btn-register" title="Daftar Akun Baru" aria-label="Daftar Akun Baru">
                    <i class="fas fa-user-plus"></i>
                </a>
            </div>
        </nav>
    </header>

    <!-- HERO SECTION -->
    <section class="hero-section" id="heroSection">
        <div class="hero-orb orb-1"></div>
        <div class="hero-orb orb-2"></div>
        <div class="hero-orb orb-3"></div>
        <div class="hero-grid"></div>

        <div class="hero-content">
            <h1 class="hero-title" id="heroTitle">
                <span class="title-brand">Cloud Sahaja</span>
                <span class="title-sub">Penyimpanan Pribadi Anda</span>
            </h1>
            <p class="hero-desc" id="heroDesc">
                Penyimpanan awan pribadi yang dibangun untuk kecepatan, keamanan, dan privasi penuh. Untuk mahasiswa, developer, dan tim kecil.
            </p>
            <div class="hero-actions" id="heroActions">
                <a href="{{ route('register') }}" class="btn-hero-primary">
                    Mulai Gratis <i class="fas fa-arrow-right"></i>
                </a>
                <a href="{{ route('login') }}" class="btn-hero-secondary">
                    <i class="fas fa-sign-in-alt"></i> Masuk ke Akun
                </a>
            </div>
        </div>

        <div class="hero-stats" id="heroStats">
            <div class="hero-stat">
                <div class="hero-stat-num">2GB</div>
                <div class="hero-stat-label">Gratis Selamanya</div>
            </div>
            <div class="hero-stat-sep"></div>
            <div class="hero-stat">
                <div class="hero-stat-num">&infin;</div>
                <div class="hero-stat-label">Ukuran per File</div>
            </div>
            <div class="hero-stat-sep"></div>
            <div class="hero-stat">
                <div class="hero-stat-num">100%</div>
                <div class="hero-stat-label">Privasi Data</div>
            </div>
            <div class="hero-stat-sep"></div>
            <div class="hero-stat">
                <div class="hero-stat-num">0</div>
                <div class="hero-stat-label">Iklan / Pelacak</div>
            </div>
        </div>

        <div class="scroll-indicator">
            <span>Scroll</span>
            <i class="fas fa-chevron-down"></i>
        </div>
    </section>

    <!-- FLOATING STORIES SECTION -->
    <section class="stories-section">
        <div class="stories-header gsap-reveal">
            <span class="section-label">Kenali Platform Kami</span>
            <h2 class="section-title" style="margin-bottom: 16px;">Dirancang untuk Performa,<br>Dibangun untuk Privasi.</h2>
        </div>

        <!-- Story 1: Speed -->
        <div class="story-row gsap-reveal">
            <div class="story-text">
                <span class="step-num">01 &mdash; Kecepatan Upload</span>
                <h2>Upload File Besar<br>Tanpa Hambatan.</h2>
                <p>Teknologi <strong>Chunked Upload</strong> memotong file besar menjadi potongan kecil, lalu menyusunnya kembali di server. Upload file berukuran Gigabyte tanpa khawatir koneksi terputus.</p>
                <ul class="story-feature-list">
                    <li><i class="fas fa-check"></i> Bypass batas upload Cloudflare 100MB</li>
                    <li><i class="fas fa-check"></i> Progress bar real-time per chunk</li>
                </ul>
            </div>
            <div class="story-visual">
                <div class="floating-wrapper levitate">
                    <div class="floating-card">
                        <img src="https://images.unsplash.com/photo-1558494949-ef010cbdcc31?q=80&w=900&auto=format&fit=crop" alt="Kecepatan Upload">
                        <div class="floating-card-overlay">
                            <span class="floating-card-badge"><i class="fas fa-bolt" style="color:#FB923C"></i> Chunked Upload Aktif</span>
                        </div>
                    </div>
                    <div class="story-deco story-deco-1">
                        <span class="deco-icon"><i class="fas fa-tachometer-alt"></i> 300 MBps</span>
                    </div>
                    <div class="story-deco story-deco-2">
                        <span class="deco-icon"><i class="fas fa-check" style="color:#34D399"></i> Upload Selesai</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Story 2: Security -->
        <div class="story-row reverse gsap-reveal">
            <div class="story-text">
                <span class="step-num">02 &mdash; Keamanan Berlapis</span>
                <h2>Proteksi Kelas<br>Enterprise.</h2>
                <p>Data Anda dilindungi oleh tiga lapis keamanan sekaligus: OAuth Google, Cloudflare Turnstile CAPTCHA, dan sistem deteksi serangan otomatis dari CrowdSec.</p>
                <ul class="story-feature-list">
                    <li><i class="fas fa-check"></i> Login via Google OAuth &mdash; tanpa password lemah</li>
                    <li><i class="fas fa-check"></i> CAPTCHA bot prevention (Cloudflare Turnstile)</li>
                    <li><i class="fas fa-check"></i> RICO BOT Satpam Server Blokir IP Bot Otomatis</li>
                </ul>
            </div>
            <div class="story-visual">
                <div class="floating-wrapper levitate-reverse">
                    <div class="floating-card">
                        <img src="https://i.ibb.co.com/F4d8110n/benchmark1.jpg" alt="Keamanan">
                        <div class="floating-card-overlay">
                            <span class="floating-card-badge"><i class="fas fa-shield-alt" style="color:#34D399"></i> 3 Layer Security</span>
                        </div>
                    </div>
                    <div class="story-deco story-deco-1">
                        <span class="deco-icon"><i class="fas fa-lock"></i> Enkripsi Aktif</span>
                    </div>
                    <div class="story-deco story-deco-2">
                        <span class="deco-icon"><i class="fas fa-user-shield" style="color:#06B6D4"></i> OAuth Verified</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Story 3: Share -->
        <div class="story-row gsap-reveal">
            <div class="story-text">
                <span class="step-num">03 &mdash; Kolaborasi & Berbagi</span>
                <h2>Bagikan File dengan<br>Kendali Penuh.</h2>
                <p>Buat tautan berbagi publik dengan masa aktif yang bisa diatur. Preview PDF, video, dan gambar langsung di browser &mdash; tanpa download dulu.</p>
                <ul class="story-feature-list">
                    <li><i class="fas fa-check"></i> Share link dengan expiry date</li>
                    <li><i class="fas fa-check"></i> Preview PDF, video, gambar inline</li>
                </ul>
            </div>
            <div class="story-visual">
                <div class="floating-wrapper levitate-center">
                    <div class="floating-card">
                        <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?q=80&w=900&auto=format&fit=crop" alt="Kolaborasi">
                        <div class="floating-card-overlay">
                            <span class="floating-card-badge"><i class="fas fa-share-alt" style="color:#60A5FA"></i> Share Link Aktif</span>
                        </div>
                    </div>
                    <div class="story-deco story-deco-1">
                        <span class="deco-icon"><i class="fas fa-link"></i> Link Dibuat</span>
                    </div>
                    <div class="story-deco story-deco-2">
                        <span class="deco-icon"><i class="fas fa-eye" style="color:#2563EB"></i> Preview Instan</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- BENTO GRID FEATURES -->
    <section class="bento-section" id="fitur">
        <div class="bento-inner">
            <div class="bento-header gsap-reveal">
                <span class="section-label">Semua Fitur</span>
                <h2 class="section-title" style="margin-bottom: 12px;">Fitur Tanpa Kompromi.</h2>
                <p class="section-desc" style="margin: 0 auto;">Semua yang Anda butuhkan untuk mengelola file pribadi, dalam satu antarmuka yang bersih.</p>
            </div>
            <div class="bento-grid">
                <!-- Large upload card -->
                <div class="bento-card bento-col-8 gsap-reveal-scale">
                    <div style="display:flex; gap:24px; align-items:flex-start; flex-wrap:wrap;">
                        <div style="flex:1; min-width:200px;">
                            <div class="bento-icon-wrap purple"><i class="fas fa-cloud-upload-alt"></i></div>
                            <h3>Upload Tanpa Batas Ukuran</h3>
                            <p>Simpan file hingga Gigabyte dengan sistem chunked upload yang anti-gagal.</p>
                            <div class="bento-progress">
                                <div class="bento-progress-label"><span>Video 4K.mp4</span><span>78%</span></div>
                                <div class="bento-progress-bar"><div class="bento-progress-fill" style="width:78%"></div></div>
                            </div>
                        </div>
                        {{-- <img src="https://images.unsplash.com/photo-1618401471353-b98afee0b2eb?q=80&w=500&auto=format&fit=crop" class="bento-card-img" alt="Upload" style="flex-shrink:0; width:180px; height:160px; object-fit:cover;"> --}}
                    </div>
                </div>
                <!-- Security card -->
                <div class="bento-card bento-col-4 gsap-reveal-scale">
                    <div class="bento-icon-wrap cyan"><i class="fas fa-shield-alt"></i></div>
                    <h3>Keamanan Berlapis</h3>
                    <p>OAuth Google, Turnstile CAPTCHA, dan proteksi DDoS aktif 24/7.</p>
                    <div class="bento-shield"><i class="fas fa-shield-alt"></i></div>
                    <div class="tag-row">
                        <span class="tag">OAuth</span>
                        <span class="tag">Turnstile</span>
                        <span class="tag">CrowdSec</span>
                    </div>
                </div>
                <!-- Smart Trash -->
                <div class="bento-card bento-col-4 gsap-reveal-scale">
                    <div class="bento-icon-wrap orange"><i class="fas fa-trash-restore"></i></div>
                    <h3>Smart Trash</h3>
                    <p>File yang dihapus otomatis dibersihkan setelah 30 hari. Aman, rapi, efisien.</p>
                </div>
                <!-- Folder nested -->
                <div class="bento-card bento-col-4 gsap-reveal-scale">
                    <div class="bento-icon-wrap green"><i class="fas fa-folder-tree"></i></div>
                    <h3>Nested Folder</h3>
                    <p>Kelola ribuan file dengan struktur folder bertingkat dan pencarian instan.</p>
                </div>
                <!-- Share card -->
                <div class="bento-card bento-col-4 gsap-reveal-scale">
                    <div class="bento-icon-wrap pink"><i class="fas fa-share-alt"></i></div>
                    <h3>Share & Preview</h3>
                    <p>Bagikan PDF, video, gambar dengan link. Preview langsung di browser.</p>
                </div>
                <!-- Storage quota card -->
                <div class="bento-card bento-col-12 gsap-reveal-scale" style="display:flex; align-items:center; gap:32px; flex-wrap:wrap;">
                    <div style="flex:1; min-width:200px;">
                        <div class="bento-icon-wrap purple" style="display:inline-flex; margin-bottom:16px;"><i class="fas fa-database"></i></div>
                        <h3>2GB Gratis Selamanya</h3>
                        <p>Mulai tanpa biaya apapun. Kuota bisa ditingkatkan oleh admin.</p>
                    </div>
                    <div style="flex:2; min-width:240px;">
                        <div style="display:flex; justify-content:space-between; font-size:13px; color:var(--text-muted); margin-bottom:10px;"><span>Penggunaan Storage</span><span>0 / 2GB</span></div>
                        <div class="bento-progress-bar" style="height:8px;"><div class="bento-progress-fill" style="width:5%;"></div></div>
                        <div style="display:flex; gap:20px; margin-top:16px; flex-wrap:wrap;">
                            <div style="font-size:13px; color:var(--text-secondary);"><i class="fas fa-file-alt" style="color:var(--accent-light); margin-right:6px;"></i>Dokumen</div>
                            <div style="font-size:13px; color:var(--text-secondary);"><i class="fas fa-image" style="color:#FB923C; margin-right:6px;"></i>Foto</div>
                            <div style="font-size:13px; color:var(--text-secondary);"><i class="fas fa-video" style="color:#EC4899; margin-right:6px;"></i>Video</div>
                            <div style="font-size:13px; color:var(--text-secondary);"><i class="fas fa-code" style="color:#06B6D4; margin-right:6px;"></i>Kode</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- WHY CLOUD SAHAJA -->
    <section class="trust-section">
        <div class="trust-header">
            <div class="gsap-reveal-left">
                <span class="section-label">Kenapa Cloud Sahaja?</span>
                <h2 class="section-title">Dibuat dengan<br>Prioritas yang Tepat.</h2>
            </div>
            <div class="gsap-reveal-right">
                <p class="section-desc">Kami membangun Cloud Sahaja dengan satu prinsip: data Anda adalah milik Anda, bukan kami. Tidak ada iklan, tidak ada pelacak, tidak ada komersialisasi data.</p>
            </div>
        </div>
        <div class="trust-grid">
            <div class="trust-card gsap-reveal">
                <div class="trust-icon purple"><i class="fas fa-user-secret"></i></div>
                <h4>Privasi Absolut</h4>
                <p>Server dikelola sendiri (self-hosted). Kami tidak menjual atau berbagi data Anda ke pihak ketiga manapun.</p>
            </div>
            <div class="trust-card gsap-reveal">
                <div class="trust-icon cyan"><i class="fas fa-bolt"></i></div>
                <h4>Dioptimalkan untuk Server Kecil</h4>
                <p>Berjalan efisien bahkan di server i386 dengan resource terbatas. Tetap cepat dan responsif.</p>
            </div>
            <div class="trust-card gsap-reveal">
                <div class="trust-icon green"><i class="fas fa-globe"></i></div>
                <h4>Akses dari Mana Saja</h4>
                <p>Berbasis web sepenuhnya. Tidak perlu install aplikasi. Buka browser dan langsung mulai bekerja.</p>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="welcome-footer">
        <div class="footer-container">
            <div class="footer-grid">
                <!-- Col 1: Brand & Status -->
                <div class="footer-col-brand">
                    <a href="{{ route('welcome') }}" class="footer-brand">
                        <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" alt="Cloud Sahaja Logo">
                        <span>Cloud Sahaja</span>
                        {{-- <span class="footer-badge">v2.0 Beta</span> --}}
                    </a>
                    <p class="footer-desc">
                        Penyimpanan awan pribadi mandiri (self-hosted) berkecepatan tinggi dengan enkripsi file, preview dokumen inline, dan kendali privasi data 100% di tangan Anda.
                    </p>
                    {{-- <div class="footer-status-pill">
                        <span class="status-dot"></span>
                        <span>Sistem Operasional &bull; 100% Uptime</span>
                    </div> --}}
                </div>

                <!-- Col 2: Fitur Utama -->
                <div class="footer-col">
                    <h4>Fitur Utama</h4>
                    <ul class="footer-links">
                        <li><a href="#fitur"></i> Chunked Upload</a></li>
                        <li><a href="#fitur"></i> Inline Doc Preview</a></li>
                        <li><a href="#fitur"></i> Public Link Share</a></li>
                        <li><a href="#fitur"></i> Nested Folders</a></li>
                        <li><a href="#fitur"></i> Smart 30-Day Trash</a></li>
                        <li><a href="#fitur"></i> Kuota Gratis 2GB</a></li>
                    </ul>
                </div>

                <!-- Col 3: Keamanan & Privasi -->
                <div class="footer-col">
                    <h4>Keamanan & Trust</h4>
                    <ul class="footer-links">
                        <li><span></i> Cloudflare Turnstile</span></li>
                        <li><span></i> Google OAuth</span></li>
                        <li><span></i> Self-Hosted Server</span></li>
                        <li><span></i> Tanpa Pelacak / Iklan</span></li>
                        <li><span></i> SSL / HTTPS Enforced</span></li>
                    </ul>
                </div>

                <!-- Col 4: Navigasi & Ekosistem -->
                <div class="footer-col">
                    <h4>Ekosistem & Akses</h4>
                    <ul class="footer-links">
                        <li><a href="{{ route('login') }}"></i> Masuk ke Akun</a></li>
                        <li><a href="{{ route('register') }}"></i> Buat Akun Baru</a></li>
                        <li><a href="https://sahaja-ai.my.id" target="_blank" rel="noopener noreferrer"></i> Portal SAHAJA AI</a></li>
                        <li><a href="https://github.com/faqihhidayah876/cloud-sahaja.git" target="_blank" rel="noopener noreferrer"><i class="fab fa-github"></i> Repository GitHub</a></li>
                        <li><span><i class="fas fa-code"></i> Dibuat oleh Faqih Hidayah</span></li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-copy">
                    &copy; {{ date('Y') }} <strong>Cloud Sahaja</strong>. Bagian dari ekosistem inovasi <a href="https://sahaja-ai.my.id" target="_blank" rel="noopener noreferrer">SAHAJA AI</a>. Dibuat dengan dedikasi oleh <strong>Faqih Hidayah</strong>.
                </div>
                {{-- <div class="footer-tags">
                    <span class="footer-tech-tag"><i class="fab fa-laravel" style="color: #FF2D20;"></i> Laravel 11</span>
                    <span class="footer-tech-tag"><i class="fab fa-php" style="color: #777BB4;"></i> PHP 8.2</span>
                    <span class="footer-tech-tag"><i class="fas fa-feather-alt" style="color: #38BDF8;"></i> GSAP Motion</span>
                    <span class="footer-tech-tag"><i class="fas fa-microchip" style="color: #10B981;"></i> i386 Optimized</span>
                </div> --}}
            </div>
        </div>
    </footer>

    <!-- SCRIPTS -->
    <script>
        /* THEME TOGGLE */
        function updateThemeIcon() {
            const isLight = document.documentElement.classList.contains('light-mode');
            const icon = document.getElementById('themeIcon');
            if (icon) icon.className = isLight ? 'fas fa-moon' : 'fas fa-sun';
        }
        function toggleTheme() {
            document.documentElement.classList.toggle('light-mode');
            if (document.body) document.body.classList.toggle('light-mode');
            const isLight = document.documentElement.classList.contains('light-mode');
            localStorage.setItem('cloud-sahaja-theme', isLight ? 'light' : 'dark');
            updateThemeIcon();
        }
        document.addEventListener('DOMContentLoaded', () => {
            if (document.documentElement.classList.contains('light-mode') && document.body)
                document.body.classList.add('light-mode');
            updateThemeIcon();
        });
    </script>

    <script>
        /* DeepSeek-style Circular Cursor Lens Follower (Only expands on hero title hover) */
        (function() {
            const follower = document.getElementById('cursorFollower');
            const heroTitle = document.getElementById('heroTitle');
            if (!follower || !heroTitle || !window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;

            let mouseX = -300, mouseY = -300;
            let currentX = -300, currentY = -300;
            let isMoving = false;

            window.addEventListener('mousemove', (e) => {
                mouseX = e.clientX;
                mouseY = e.clientY;
                if (!isMoving) {
                    currentX = mouseX;
                    currentY = mouseY;
                    isMoving = true;
                }
            });

            function render() {
                currentX += (mouseX - currentX) * 0.18;
                currentY += (mouseY - currentY) * 0.18;
                follower.style.left = currentX + 'px';
                follower.style.top  = currentY + 'px';
                requestAnimationFrame(render);
            }
            requestAnimationFrame(render);

            heroTitle.addEventListener('mouseenter', (e) => {
                currentX = e.clientX;
                currentY = e.clientY;
                follower.style.left = currentX + 'px';
                follower.style.top  = currentY + 'px';
                follower.classList.add('cursor-expanded');
            });

            heroTitle.addEventListener('mouseleave', () => {
                follower.classList.remove('cursor-expanded');
            });
        })();
    </script>

    <script>
        /* GSAP ANIMATIONS */
        document.addEventListener('DOMContentLoaded', () => {
            gsap.registerPlugin(ScrollTrigger);

            /* 1. Hero entrance */
            const tlHero = gsap.timeline({ delay: 0.1 });
            tlHero
                .from('.title-brand', { y: 35, opacity: 0, duration: 0.9, ease: 'power3.out' })
                .from('.title-sub',   { y: 30, opacity: 0, duration: 0.9, ease: 'power3.out' }, '-=0.5')
                .from('#heroDesc',   { y: 24, opacity: 0, duration: 0.8, ease: 'power3.out' }, '-=0.5')
                .from('#heroActions', { y: 24, opacity: 0, duration: 0.8, ease: 'power3.out' }, '-=0.5')
                .from('#heroStats .hero-stat', { y: 20, opacity: 0, stagger: 0.1, duration: 0.6, ease: 'power2.out' }, '-=0.4')
                .from('.scroll-indicator', { opacity: 0, duration: 0.8 }, '-=0.2');

            /* 2. Navbar */
            gsap.from('#mainNav', { y: -20, opacity: 0, duration: 0.8, ease: 'power3.out', delay: 0.05 });

            /* 3. Mouse-parallax on orbs */
            document.addEventListener('mousemove', (e) => {
                const cx = window.innerWidth / 2;
                const cy = window.innerHeight / 2;
                const dx = (e.clientX - cx) / cx;
                const dy = (e.clientY - cy) / cy;
                gsap.to('.orb-1', { x: dx * 30, y: dy * 20, duration: 2, ease: 'power1.out' });
                gsap.to('.orb-2', { x: dx * -20, y: dy * -15, duration: 2.5, ease: 'power1.out' });
                gsap.to('.orb-3', { x: dx * 10, y: dy * 10, duration: 3, ease: 'power1.out' });
            });

            /* 4. Scroll reveals */
            gsap.utils.toArray('.gsap-reveal').forEach(el => {
                gsap.fromTo(el, { y: 40, opacity: 0 }, { y: 0, opacity: 1, duration: 0.9, ease: 'power3.out',
                    scrollTrigger: { trigger: el, start: 'top 85%', toggleActions: 'play none none none' }
                });
            });
            gsap.utils.toArray('.gsap-reveal-left').forEach(el => {
                gsap.fromTo(el, { x: -40, opacity: 0 }, { x: 0, opacity: 1, duration: 0.9, ease: 'power3.out',
                    scrollTrigger: { trigger: el, start: 'top 85%', toggleActions: 'play none none none' }
                });
            });
            gsap.utils.toArray('.gsap-reveal-right').forEach(el => {
                gsap.fromTo(el, { x: 40, opacity: 0 }, { x: 0, opacity: 1, duration: 0.9, ease: 'power3.out',
                    scrollTrigger: { trigger: el, start: 'top 85%', toggleActions: 'play none none none' }
                });
            });
            gsap.utils.toArray('.gsap-reveal-scale').forEach((el, i) => {
                gsap.fromTo(el, { scale: 0.93, opacity: 0 }, { scale: 1, opacity: 1, duration: 0.8, ease: 'power3.out', delay: i * 0.08,
                    scrollTrigger: { trigger: el, start: 'top 88%', toggleActions: 'play none none none' }
                });
            });

            /* 5. Floating wrapper scroll parallax */
            gsap.utils.toArray('.floating-wrapper').forEach((wrap, i) => {
                gsap.to(wrap, {
                    yPercent: i % 2 === 0 ? -8 : -5,
                    ease: 'none',
                    scrollTrigger: {
                        trigger: wrap.closest('.story-row'),
                        start: 'top bottom',
                        end: 'bottom top',
                        scrub: 1.5
                    }
                });
            });

            /* 6. Story text slide in */
            gsap.utils.toArray('.story-text').forEach((el, i) => {
                const isReverse = el.closest('.story-row.reverse');
                gsap.from(el, {
                    x: isReverse ? 30 : -30, opacity: 0, duration: 1, ease: 'power3.out',
                    scrollTrigger: { trigger: el, start: 'top 80%', toggleActions: 'play none none none' }
                });
            });

            /* 7. Progress bars animate in */
            ScrollTrigger.create({
                trigger: '.bento-section', start: 'top 70%',
                onEnter: () => {
                    document.querySelectorAll('.bento-progress-fill').forEach(el => {
                        const target = el.style.width;
                        el.style.width = '0%';
                        setTimeout(() => { el.style.width = target; }, 300);
                    });
                }
            });
        });
    </script>

</body>
</html>
