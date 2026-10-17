<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $file->original_name }} — Cloud Sahaja</title>
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
        .shared-page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background: var(--bg-base);
            color: var(--text-primary);
        }

        .shared-topbar {
            position: sticky;
            top: 0;
            padding: 12px 24px;
            background: var(--bg-base);
            border-bottom: 1px solid var(--border-subtle);
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10;
        }

        .shared-topbar-left {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 0;
        }

        .shared-topbar-title {
            font-size: 14px;
            font-weight: 600;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 480px;
        }

        .shared-body {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 24px;
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .shared-card {
            background: var(--bg-elevated);
            border: 1px solid var(--border-subtle);
            border-radius: var(--radius-xl);
            padding: 36px 32px;
            max-width: 440px;
            width: 100%;
            text-align: center;
            box-shadow: var(--shadow-md);
        }

        .shared-icon {
            width: 72px;
            height: 72px;
            border-radius: var(--radius-lg);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 20px;
        }

        .shared-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 12px;
            word-break: break-word;
        }

        .shared-meta {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            font-size: 13px;
            color: var(--text-secondary);
            margin-bottom: 28px;
        }
    </style>
</head>
<body class="shared-page">
    @if($file->isShareExpired())
        {{-- Top Bar saat Link Expired --}}
        <div class="shared-topbar">
            <div class="shared-topbar-left">
                <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" 
                     alt="Cloud Sahaja" style="width: 32px; height: 32px; border-radius: 6px;">
                <div>
                    <div class="shared-topbar-title">{{ $file->original_name }}</div>
                    <div style="font-size: 12px; color: var(--text-tertiary);">
                        Dibagikan via Cloud Sahaja
                    </div>
                </div>
            </div>
            <a href="https://sahaja-ai.my.id" class="btn btn-secondary" style="font-size: 13px;">
                <i class="fas fa-arrow-left"></i> SAHAJA AI
            </a>
        </div>

        {{-- Expired Message --}}
        <div class="shared-body">
            <div style="text-align: center; padding: 60px 24px; max-width: 480px;">
                <div style="width: 80px; height: 80px; border-radius: 50%; background: var(--bg-subtle); display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                    <i class="fas fa-clock" style="font-size: 40px; color: var(--warning);"></i>
                </div>
                <h2 style="font-size: 22px; font-weight: 600; margin-bottom: 8px;">Link Sudah Kadaluarsa</h2>
                <p style="color: var(--text-secondary); font-size: 14px; line-height: 1.6;">
                    Masa berlaku link berbagi untuk file ini telah berakhir atau akses telah dicabut oleh pemilik file.
                </p>
                <div style="margin-top: 28px;">
                    <a href="{{ route('welcome') }}" class="btn btn-secondary">
                        <i class="fas fa-house"></i> Halaman Utama
                    </a>
                </div>
            </div>
        </div>
    @else
        {{-- Top Bar Active --}}
        <div class="shared-topbar">
            <div class="shared-topbar-left">
                <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" 
                     alt="Cloud Sahaja" style="width: 32px; height: 32px; border-radius: 6px;">
                <div>
                    <div class="shared-topbar-title">{{ $file->original_name }}</div>
                    <div style="font-size: 12px; color: var(--text-tertiary);">
                        Dibagikan via Cloud Sahaja
                    </div>
                </div>
            </div>
            <a href="{{ route('files.sharedDownload', $file->share_token) }}" class="btn btn-primary">
                <i class="fas fa-download"></i> Download
            </a>
        </div>

        {{-- Body Preview / Card --}}
        <div class="shared-body">
            @php $previewType = $file->preview_type; @endphp

            @if($previewType === 'image')
                <div style="display: flex; justify-content: center; align-items: center; max-width: 100%;">
                    <img src="{{ route('files.sharedPreview', $file->share_token) }}"
                         alt="{{ $file->original_name }}"
                         style="max-width: 100%; max-height: 80vh; object-fit: contain; border-radius: 12px; box-shadow: var(--shadow-lg);">
                </div>
            @elseif($previewType === 'video')
                <div style="display: flex; justify-content: center; align-items: center; width: 100%;">
                    <video src="{{ route('files.sharedPreview', $file->share_token) }}"
                           controls 
                           style="max-width: 100%; max-height: 80vh; border-radius: 12px; background: #000; box-shadow: var(--shadow-lg);"></video>
                </div>
            @elseif($previewType === 'pdf')
                <div style="width: 100%; height: 82vh;">
                    <iframe src="{{ route('files.sharedPreview', $file->share_token) }}#toolbar=1&navpanes=0"
                            style="width: 100%; height: 100%; border: none; border-radius: 12px; background: #fff; box-shadow: var(--shadow-lg);"></iframe>
                </div>
            @elseif($previewType === 'audio')
                <div class="shared-card">
                    <div class="shared-icon" style="background: var(--accent-subtle); color: var(--accent);">
                        <i class="fas fa-music"></i>
                    </div>
                    <h1 class="shared-title">{{ $file->original_name }}</h1>
                    <div class="shared-meta">
                        <span><i class="fas fa-hdd"></i> {{ $file->human_size }}</span>
                        <span><i class="fas fa-clock"></i> {{ $file->created_at->format('d M Y') }}</span>
                    </div>
                    <audio src="{{ route('files.sharedPreview', $file->share_token) }}"
                           controls style="width: 100%; margin-bottom: 24px;"></audio>
                    <a href="{{ route('files.sharedDownload', $file->share_token) }}"
                       class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-download"></i> Download File
                    </a>
                </div>
            @else
                <div class="shared-card">
                    <div class="shared-icon" style="background: {{ $file->icon_color }}20; color: {{ $file->icon_color }};">
                        <i class="fas {{ $file->icon }}"></i>
                    </div>
                    <h1 class="shared-title">{{ $file->original_name }}</h1>
                    <div class="shared-meta">
                        <span><i class="fas fa-hdd"></i> {{ $file->human_size }}</span>
                        <span><i class="fas fa-clock"></i> {{ $file->created_at->format('d M Y') }}</span>
                    </div>
                    <a href="{{ route('files.sharedDownload', $file->share_token) }}"
                       class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-download"></i> Download File
                    </a>
                </div>
            @endif
        </div>
    @endif
</body>
</html>
