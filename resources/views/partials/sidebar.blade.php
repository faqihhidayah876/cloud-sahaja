<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <img src="https://i.ibb.co.com/jZZ0648R/Logo-SAHAJA-AI.png" 
             alt="Cloud Sahaja" style="width: 28px; height: 28px; border-radius: 6px;">
        <div style="flex: 1; margin-left: 10px;">
            <div style="font-weight: 700; font-size: 15px; color: var(--text-primary);">Cloud Sahaja</div>
            <div style="font-size: 11px; color: var(--text-tertiary);">Cloud Storage Pribadi</div>
        </div>
    </div>
    
    <nav class="sidebar-nav">
        <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-house"></i> <span>Beranda</span>
        </a>
        <a href="{{ route('files.index') }}" class="nav-item {{ request()->routeIs('files.index') ? 'active' : '' }}">
            <i class="fas fa-folder"></i> <span>File Saya</span>
        </a>
        <a href="{{ route('files.starred') }}" class="nav-item {{ request()->routeIs('files.starred') ? 'active' : '' }}">
            <i class="fas fa-star"></i> <span>Favorit</span>
        </a>
        <a href="{{ route('files.trash') }}" class="nav-item {{ request()->routeIs('files.trash') ? 'active' : '' }}">
            <i class="fas fa-trash-can"></i> <span>Sampah</span>
        </a>
        @if(Auth::user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.*') ? 'active' : '' }}" style="border-top: 1px solid var(--border-subtle); margin-top: 8px; padding-top: 16px;">
                <i class="fas fa-shield-halved"></i> <span>Admin Panel</span>
            </a>
        @endif
    </nav>
    
    <div class="sidebar-footer">
        @php
            $used = Auth::user()->storage_used ?? 0;
            $quota = Auth::user()->storage_quota ?? (2 * 1024 * 1024 * 1024);
            $percent = $quota > 0 ? min(100, ($used / $quota) * 100) : 0;
            
            // Human readable
            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
            $humanSize = function($bytes) use ($units) {
                $i = 0;
                while ($bytes >= 1024 && $i < count($units) - 1) {
                    $bytes /= 1024;
                    $i++;
                }
                return round($bytes, 2) . ' ' . $units[$i];
            };
        @endphp

        <div class="storage-info">
            <div style="font-size: 11px; color: var(--text-tertiary); margin-bottom: 6px;">
                Penyimpanan
            </div>
            <div class="storage-bar">
                <div class="storage-bar-fill" style="width: {{ $percent }}%;"></div>
            </div>
            <div style="font-size: 11px; color: var(--text-secondary); margin-top: 6px;">
                {{ $humanSize($used) }} / {{ $humanSize($quota) }}
            </div>
        </div>
    </div>
</aside>
