<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $file->original_name }} — Cloud Sahaja</title>
    <link rel="icon" type="image/png" href="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png">
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

        /* Tombol Download Ikon Saja */
        .share-download-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--accent);
            color: #fff;
            text-decoration: none;
            flex-shrink: 0;
            transition: background 150ms, transform 150ms;
            margin-left: 12px;
        }
        .share-download-btn:hover {
            background: var(--accent-hover);
            transform: scale(1.05);
        }
        .share-download-btn i {
            font-size: 16px;
        }

        /* Area Preview Full Screen */
        .share-preview-area {
            flex: 1;
            width: 100%;
            height: 100%;
            position: relative;
            background: #1e1e1e; /* Background gelap untuk viewer */
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Styling Spesifik Preview */
        .share-preview-area iframe {
            width: 100%;
            height: 100%;
            border: none;
            background: #fff;
        }

        .share-preview-area img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            display: block;
        }

        .share-preview-area video {
            max-width: 100%;
            max-height: 100%;
            outline: none;
            background: #000;
        }

        /* Tampilan Jika File Tidak Bisa Di-preview */
        .share-fallback {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 24px;
            text-align: center;
            color: var(--text-secondary);
        }
        .share-fallback i {
            font-size: 64px;
            color: var(--text-tertiary);
            margin-bottom: 16px;
            opacity: 0.5;
        }
        .share-fallback h2 {
            font-size: 18px;
            color: var(--text-primary);
            margin-bottom: 8px;
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
        <a href="{{ route('files.sharedDownload', $file->share_token) }}" class="share-download-btn" title="Download File">
            <i class="fas fa-download"></i>
        </a>
    </div>

    <!-- Preview Area -->
    <div class="share-preview-area">
        @php $previewType = $file->preview_type; @endphp

        @if($previewType === 'image')
            <img src="{{ route('files.sharedPreview', $file->share_token) }}" alt="{{ $file->original_name }}">
            
        @elseif($previewType === 'video')
            <video src="{{ route('files.sharedPreview', $file->share_token) }}" controls autoplay muted></video>
            
        @elseif($previewType === 'pdf')
            <iframe src="{{ route('files.sharedPreview', $file->share_token) }}#toolbar=1&navpanes=0"></iframe>
            
        @elseif($previewType === 'audio')
            <div class="share-fallback">
                <i class="fas fa-music"></i>
                <h2>{{ $file->original_name }}</h2>
                <audio src="{{ route('files.sharedPreview', $file->share_token) }}" controls autoplay style="width: 100%; max-width: 400px; margin-top: 20px;"></audio>
            </div>
            
        @else
            <div class="share-fallback">
                <i class="fas {{ $file->icon }}"></i>
                <h2>{{ $file->original_name }}</h2>
                <p style="margin-top: 8px; font-size: 14px;">File ini tidak mendukung pratinjau langsung.</p>
                <a href="{{ route('files.sharedDownload', $file->share_token) }}" class="btn btn-primary" style="margin-top: 24px;">
                    <i class="fas fa-download"></i> Download File
                </a>
            </div>
        @endif
    </div>
</body>
</html>
