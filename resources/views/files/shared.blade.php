<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $file->original_name }} — Cloud Sahaja</title>
    <link rel="icon" type="image/png" href="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png">
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
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 24px;">
    <div class="shared-card">
        <div class="shared-icon" style="background: {{ $file->icon_color }}20; color: {{ $file->icon_color }};">
            <i class="fas {{ $file->icon }}"></i>
        </div>
        <h1 class="shared-title">{{ $file->original_name }}</h1>
        <div class="shared-meta">
            <span><i class="fas fa-hdd"></i> {{ $file->human_size }}</span>
            <span><i class="fas fa-clock"></i> {{ $file->created_at->format('d M Y') }}</span>
        </div>
        <a href="{{ route('files.sharedDownload', $file->share_token) }}" class="btn btn-primary" style="width: 100%;">
            <i class="fas fa-download"></i> Download File
        </a>
        <div style="margin-top: 24px; font-size: 12px; color: var(--text-tertiary); text-align: center;">
            Dibagikan via <a href="https://sahaja-ai.my.id" style="color: var(--accent);">Cloud Sahaja</a>
        </div>
    </div>
</body>
</html>
