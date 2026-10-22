<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $file->original_name }} — Cloud Sahaja</title>
    <link rel="icon" type="image/png" href="https://cdn.dbimg.app/TVHxIiqe.png">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        (function() {
            if (localStorage.getItem('cloud-sahaja-theme') === 'light') {
                document.documentElement.classList.add('light-mode');
            }
        })();
    </script>
    @vite(['resources/css/app.css'])
    
    <style>
        /* Reset & Full Screen Layout */
        body, html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100vh;
            height: 100dvh;
            overflow: hidden;
            background-color: var(--bg-base);
            font-family: 'Inter', sans-serif;
            display: flex;
            flex-direction: column;
        }

        /* Top Bar Compact */
        .share-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            height: 56px;
            background: var(--bg-elevated);
            border-bottom: 1px solid var(--border-subtle);
            flex-shrink: 0;
            z-index: 10;
        }

        .share-file-info {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0; /* Penting untuk ellipsis */
            flex: 1;
        }

        .share-file-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            background: var(--accent-subtle);
            color: var(--accent);
            flex-shrink: 0;
        }

        .share-file-text {
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .share-file-name {
            font-size: 14px;
            font-weight: 600;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.2;
        }

        .share-file-brand {
            font-size: 11px;
            color: var(--text-tertiary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Kebab Menu & Dropdown */
        .share-actions {
            position: relative;
            margin-left: 12px;
            flex-shrink: 0;
        }

        .share-action-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: transparent;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: none;
            transition: background 150ms, color 150ms;
        }

        .share-action-btn:hover {
            background: var(--bg-hover);
            color: var(--text-primary);
        }

        .share-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 8px;
            background: var(--bg-elevated);
            border: 1px solid var(--border-medium);
            border-radius: var(--radius-md);
            padding: 4px;
            min-width: 160px;
            box-shadow: var(--shadow-lg);
            display: none;
            z-index: 50;
        }

        .share-dropdown.show {
            display: block;
            animation: fadeIn 150ms ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .share-dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            font-size: 13px;
            color: var(--text-primary);
            text-decoration: none;
            border-radius: var(--radius-sm);
            transition: background 150ms;
        }

        .share-dropdown-item:hover {
            background: var(--bg-hover);
        }

        .share-dropdown-item i {
            color: var(--text-secondary);
            width: 16px;
            text-align: center;
        }

        /* Area Preview Full Screen */
        .share-preview-area {
            flex: 1;
            width: 100%;
            height: 100%;
            position: relative;
            background: #1e1e1e;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            z-index: 1;
        }

        .share-preview-area.has-scrollable {
            display: block;
        }

        .share-preview-area iframe {
            width: 100%;
            height: 100%;
            border: none;
            background: #fff;
            pointer-events: auto !important;
            display: block;
        }

        /* PDF.js Viewer Styles */
        .pdf-scroll-container {
            width: 100%;
            height: 100%;
            overflow-y: auto;
            overflow-x: hidden;
            display: block;
            padding: 20px 12px 40px;
            box-sizing: border-box;
            text-align: center;
            -webkit-overflow-scrolling: touch;
            background: #18191c;
        }

        html.light-mode .pdf-scroll-container {
            background: #e2e8f0;
        }

        .pdf-page-wrapper {
            display: inline-block;
            margin: 0 auto 20px auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35);
            border-radius: 4px;
            background: #ffffff;
            max-width: 100%;
            box-sizing: border-box;
            vertical-align: top;
        }

        .pdf-canvas {
            display: block;
            width: 100%;
            height: 100%;
            border-radius: 4px;
        }

        .pdf-loading {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            color: var(--text-secondary);
            font-size: 14px;
            padding: 60px 20px;
        }

        .pdf-loading i {
            font-size: 32px;
            color: var(--accent);
        }

        /* Text Viewer Styles */
        .share-text-container {
            width: 100%;
            height: 100%;
            overflow-y: auto;
            padding: 24px 16px;
            background: #ffffff;
            box-sizing: border-box;
            display: flex;
            justify-content: center;
            -webkit-overflow-scrolling: touch;
        }

        html:not(.light-mode) .share-text-container {
            background: #141721;
        }

        .share-text-content {
            width: 100%;
            max-width: 900px;
            font-family: 'JetBrains Mono', 'Fira Code', 'Courier New', monospace;
            font-size: 13.5px;
            line-height: 1.6;
            white-space: pre-wrap;
            word-break: break-word;
            color: var(--text-primary);
            margin: 0;
        }

        /* Tampilan Jika File Tidak Bisa Di-preview */
        .share-fallback {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 32px 20px;
            text-align: center;
            color: var(--text-secondary);
            max-width: 480px;
            margin: 0 auto;
        }
        .share-fallback-icon {
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .share-fallback h2 {
            font-size: 18px;
            color: var(--text-primary);
            margin-bottom: 6px;
            word-break: break-word;
        }

        /* Light Mode Override untuk Background Viewer */
        html.light-mode .share-preview-area {
            background: #f5f5f5;
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="share-topbar">
        <div class="share-file-info">
            <div class="share-file-icon" style="background: {{ $file->icon_color }}20; color: {{ $file->icon_color }};">
                <i class="fas {{ $file->icon }}"></i>
            </div>
            <div class="share-file-text">
                <div class="share-file-name" title="{{ $file->original_name }}">{{ $file->original_name }}</div>
                <div class="share-file-brand">Dibagikan via Cloud Sahaja</div>
            </div>
        </div>
        <div class="share-actions">
            <button type="button" class="share-action-btn" onclick="toggleShareMenu(event)" title="Menu">
                <i class="fas fa-ellipsis-vertical"></i>
            </button>
            <div class="share-dropdown" id="shareDropdown">
                <a href="{{ route('files.sharedDownload', $file->share_token) }}" class="share-dropdown-item">
                    <i class="fas fa-download"></i> Download File
                </a>
            </div>
        </div>
    </div>

    @php $previewType = $file->preview_type; @endphp

    <!-- Preview Area -->
    <div class="share-preview-area {{ in_array($previewType, ['pdf', 'text']) ? 'has-scrollable' : '' }}">
        @if($previewType === 'image')
            <img src="{{ route('files.sharedPreview', $file->share_token) }}" alt="{{ $file->original_name }}">
            
        @elseif($previewType === 'video')
            <video src="{{ route('files.sharedPreview', $file->share_token) }}" controls autoplay muted></video>
            
        @elseif($previewType === 'pdf')
            <iframe src="{{ route('files.sharedPreview', $file->share_token) }}#toolbar=1&navpanes=0&view=FitH" 
                    style="width: 100%; height: 100%; border: none; background: #fff; pointer-events: auto;">
            </iframe>
            
        @elseif($previewType === 'text')
            <div class="share-text-container">
                <pre class="share-text-content" id="shareTextContent">Memuat dokumen teks...</pre>
            </div>
            
        @elseif($previewType === 'audio')
            <div class="share-fallback">
                <i class="fas fa-music" style="font-size: 56px; color: var(--accent); margin-bottom: 16px;"></i>
                <h2>{{ $file->original_name }}</h2>
                <audio src="{{ route('files.sharedPreview', $file->share_token) }}" controls autoplay style="width: 100%; max-width: 400px; margin-top: 20px;"></audio>
            </div>
            
        @else
            <div class="share-fallback">
                <div class="share-fallback-icon" style="background: {{ $file->icon_color }}15;">
                    <i class="fas {{ $file->icon }}" style="font-size: 38px; color: {{ $file->icon_color }};"></i>
                </div>
                <h2>{{ $file->original_name }}</h2>
                <p style="margin-top: 6px; font-size: 13.5px; color: var(--text-secondary);">{{ $file->human_size }} &bull; Pratinjau langsung tidak tersedia untuk format ini</p>
                <a href="{{ route('files.sharedDownload', $file->share_token) }}" class="btn btn-primary" style="margin-top: 24px; display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; border-radius: 10px; font-weight: 600;">
                    <i class="fas fa-download"></i> Download File ({{ $file->human_size }})
                </a>
            </div>
        @endif
    </div>

    <script>
        function toggleShareMenu(e) {
            if (e) e.stopPropagation();
            const dropdown = document.getElementById('shareDropdown');
            dropdown.classList.toggle('show');
        }

        // Tutup dropdown jika klik di luar area menu
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('shareDropdown');
            if (dropdown && dropdown.classList.contains('show') && !e.target.closest('.share-actions')) {
                dropdown.classList.remove('show');
            }
        });

        @if($previewType === 'text')
        (function() {
            fetch("{{ route('files.sharedPreview', $file->share_token) }}")
                .then(r => r.text())
                .then(text => {
                    const el = document.getElementById('shareTextContent');
                    if (el) el.textContent = text;
                })
                .catch(() => {
                    const el = document.getElementById('shareTextContent');
                    if (el) el.textContent = 'Gagal memuat isi dokumen teks.';
                });
        })();
        @endif


    </script>
</body>
</html>
