@extends('layouts.app')

@push('styles')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
@endpush

@section('title', ($category === 'trash' ? 'Sampah' : ($category === 'starred' ? 'Favorit' : 'File Saya')) . ' — Cloud Sahaja')
@section('page-title', $category === 'trash' ? 'Sampah' : ($category === 'starred' ? 'Favorit' : 'File Saya'))

@section('content')
<div class="files-wrapper">
    {{-- Top Bar: Breadcrumb + Search + Filter Dropdown + View Mode Toggle --}}
    <div class="files-topbar">
        <div class="files-topbar-left">
            <div class="breadcrumb">
                @if($category === 'trash')
                    <span class="breadcrumb-item" style="color: var(--text-primary);"><i class="fas fa-trash-can"></i> Sampah</span>
                @elseif($category === 'starred')
                    <span class="breadcrumb-item" style="color: var(--text-primary);"><i class="fas fa-star" style="color: #F59E0B;"></i> Favorit</span>
                @else
                    <a href="{{ route('files.index') }}" class="breadcrumb-item">
                        <i class="fas fa-folder"></i> File Saya
                    </a>
                    @if(!empty($breadcrumb))
                        @foreach($breadcrumb as $crumb)
                            <i class="fas fa-chevron-right breadcrumb-sep"></i>
                            <a href="{{ route('files.index', ['folder' => $crumb['id']]) }}" class="breadcrumb-item">
                                {{ $crumb['name'] }}
                            </a>
                        @endforeach
                    @endif
                @endif
            </div>
        </div>

        <div class="files-topbar-right">
            {{-- Search Bar --}}
            <div class="search-bar-wrapper">
                <i class="fas fa-magnifying-glass search-icon"></i>
                <input type="text"
                       id="filesSearchInput"
                       class="search-input"
                       placeholder="Cari file..."
                       value="{{ request('search') }}"
                       autocomplete="off">
                <button type="button"
                        class="search-clear"
                        id="searchClearBtn"
                        onclick="clearSearch()"
                        style="display: {{ request('search') ? 'flex' : 'none' }};">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            {{-- Action buttons wrapper --}}
            <div class="topbar-actions">
                {{-- Filter Button --}}
                @if($category !== 'trash' && $category !== 'starred')
                    <button type="button" class="view-toggle-btn"
                            onclick="toggleFilterDropdown(event)"
                            title="Filter Kategori">
                        <i class="fas fa-filter"></i>
                        @if(request('category') && request('category') !== 'all')
                            <span class="filter-active-dot"></span>
                        @endif
                    </button>

                    {{-- Filter Dropdown --}}
                    <div class="filter-dropdown" id="filterDropdown">
                        <div class="filter-dropdown-header">Filter Kategori</div>
                        <a href="{{ route('files.index', array_merge(request()->query(), ['category' => 'all'])) }}"
                           class="filter-item {{ request('category', 'all') === 'all' ? 'active' : '' }}">
                            <i class="fas fa-layer-group"></i>
                            <span>Semua</span>
                            @if(request('category', 'all') === 'all')
                                <i class="fas fa-check" style="margin-left: auto;"></i>
                            @endif
                        </a>
                        <a href="{{ route('files.index', array_merge(request()->query(), ['category' => 'image'])) }}"
                           class="filter-item {{ request('category') === 'image' ? 'active' : '' }}">
                            <i class="fas fa-image"></i>
                            <span>Gambar</span>
                            @if(request('category') === 'image')
                                <i class="fas fa-check" style="margin-left: auto;"></i>
                            @endif
                        </a>
                        <a href="{{ route('files.index', array_merge(request()->query(), ['category' => 'document'])) }}"
                           class="filter-item {{ request('category') === 'document' ? 'active' : '' }}">
                            <i class="fas fa-file-lines"></i>
                            <span>Dokumen</span>
                            @if(request('category') === 'document')
                                <i class="fas fa-check" style="margin-left: auto;"></i>
                            @endif
                        </a>
                        <a href="{{ route('files.index', array_merge(request()->query(), ['category' => 'video'])) }}"
                           class="filter-item {{ request('category') === 'video' ? 'active' : '' }}">
                            <i class="fas fa-video"></i>
                            <span>Video</span>
                            @if(request('category') === 'video')
                                <i class="fas fa-check" style="margin-left: auto;"></i>
                            @endif
                        </a>
                        <a href="{{ route('files.index', array_merge(request()->query(), ['category' => 'audio'])) }}"
                           class="filter-item {{ request('category') === 'audio' ? 'active' : '' }}">
                            <i class="fas fa-music"></i>
                            <span>Audio</span>
                            @if(request('category') === 'audio')
                                <i class="fas fa-check" style="margin-left: auto;"></i>
                            @endif
                        </a>
                    </div>

                    {{-- Divider --}}
                    <div class="topbar-divider"></div>
                @endif

                {{-- Grid/List Toggle --}}
                <div class="view-toggle-group">
                    <button type="button" class="view-toggle-btn active" data-view="grid" onclick="setViewMode('grid')" title="Tampilan Grid">
                        <i class="fas fa-table-cells-large"></i>
                    </button>
                    <button type="button" class="view-toggle-btn" data-view="list" onclick="setViewMode('list')" title="Tampilan Daftar">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Trash Header --}}
    @if($category === 'trash' && $files->count() > 0)
        <div class="trash-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-triangle-exclamation" style="color: var(--warning);"></i>
                <span style="font-size: 13px; color: var(--text-secondary);">
                    File di sampah akan otomatis dihapus permanen setelah 30 hari.
                </span>
            </div>
            <button class="btn btn-danger" onclick="confirmEmptyTrash()">
                <i class="fas fa-trash-can"></i> Kosongkan Sampah
            </button>
        </div>
    @endif

    {{-- Hidden file inputs untuk upload --}}
    <input type="file" id="fileInput" multiple style="display:none;">
    <input type="file" id="imageInput" accept="image/*" multiple style="display:none;">

    {{-- Upload Progress --}}
    <div id="uploadProgressList" class="upload-progress-list"></div>

    @if($files->count() > 0)
        {{-- GRID VIEW --}}
        <div class="files-grid" id="filesGrid">
            @foreach($files as $file)
                <div class="file-card file-card-item" data-file-id="{{ $file->id }}" data-file-name="{{ $file->original_name }}" data-is-folder="{{ $file->is_folder ? '1' : '0' }}">
                    {{-- Star badge --}}
                    @if($file->is_starred && $category !== 'trash')
                        <div class="star-badge star-badge-{{ $file->id }}">
                            <i class="fas fa-star"></i>
                        </div>
                    @endif

                    {{-- Preview Area (160px) --}}
                    <div class="file-preview"
                         @if(!$file->is_folder && $file->is_previewable)
                             onclick="openPreview({{ $file->id }}, '{{ addslashes($file->original_name) }}', '{{ $file->preview_type }}')"
                             style="cursor: pointer;"
                         @endif>
                        @if($file->is_folder)
                            <a href="{{ route('files.index', ['folder' => $file->id]) }}" style="display: contents;">
                                <i class="fas {{ $file->icon }}" style="color: {{ $file->icon_color }}; font-size: 52px;"></i>
                            </a>
                        @elseif($file->preview_type === 'image')
                            @if($file->thumbnail_name)
                                <div class="img-wrapper">
                                    <img src="{{ route('files.thumbnail', $file->id) }}" 
                                         data-src="{{ route('files.preview', $file->id) }}" 
                                         class="lazy-blur-img"
                                         onload="loadHighRes(this)"
                                         style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                                </div>
                            @else
                                <img src="{{ route('files.preview', $file->id) }}" 
                                     style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
                            @endif
                        @elseif($file->preview_type === 'video')
                            <div class="video-preview-wrapper">
                                <i class="fas {{ $file->icon }}" style="color: {{ $file->icon_color }}; font-size: 52px;"></i>
                                <div class="video-play-overlay">
                                    <i class="fas fa-play" style="margin-left: 2px;"></i>
                                </div>
                            </div>
                        @else
                            <i class="fas {{ $file->icon }}" style="color: {{ $file->icon_color }}; font-size: 52px;"></i>
                        @endif
                    </div>

                    {{-- File Info --}}
                    <div class="file-info">
                        <div class="file-name" title="{{ $file->original_name }}">
                            @if($file->is_folder && $category !== 'trash')
                                <a href="{{ route('files.index', ['folder' => $file->id]) }}" style="color: inherit;">
                                    {{ $file->original_name }}
                                </a>
                            @elseif(!$file->is_folder && $file->is_previewable)
                                <a href="javascript:void(0)" onclick="openPreview({{ $file->id }}, '{{ addslashes($file->original_name) }}', '{{ $file->preview_type }}')" style="color: inherit;">
                                    {{ $file->original_name }}
                                </a>
                            @else
                                {{ $file->original_name }}
                            @endif
                        </div>
                        <div class="file-meta">
                            @if($category === 'trash')
                                Dihapus {{ $file->deleted_at->diffForHumans() }}
                            @else
                                {{ $file->human_size }}
                                @if(!$file->is_folder)
                                    &middot; {{ $file->created_at->diffForHumans() }}
                                @endif
                            @endif
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="file-actions">
                        @if($category === 'trash')
                            <button class="icon-btn-sm" onclick="event.stopPropagation(); restoreFile({{ $file->id }})" title="Pulihkan">
                                <i class="fas fa-rotate-left"></i>
                            </button>
                            <button class="icon-btn-sm danger" data-name="{{ $file->original_name }}" onclick="event.stopPropagation(); forceDelete({{ $file->id }}, this.getAttribute('data-name'))" title="Hapus Permanen">
                                <i class="fas fa-trash-can"></i>
                            </button>
                        @else
                            <button class="icon-btn-sm star-btn-{{ $file->id }} {{ $file->is_starred ? 'starred' : '' }}" onclick="event.stopPropagation(); toggleStar({{ $file->id }}, this)" title="{{ $file->is_starred ? 'Hapus dari favorit' : 'Tandai favorit' }}">
                                <i class="{{ $file->is_starred ? 'fas' : 'far' }} fa-star"></i>
                            </button>
                            @if(!$file->is_folder)
                                <button class="icon-btn-sm" onclick="event.stopPropagation(); shareFile({{ $file->id }})" title="Bagikan">
                                    <i class="fas fa-share-nodes"></i>
                                </button>
                                <button class="icon-btn-sm" onclick="event.stopPropagation(); downloadFile({{ $file->id }})" title="Download">
                                    <i class="fas fa-download"></i>
                                </button>
                            @endif
                            <button class="icon-btn-sm danger" data-name="{{ $file->original_name }}" onclick="event.stopPropagation(); confirmDelete({{ $file->id }}, this.getAttribute('data-name'))" title="Hapus">
                                <i class="fas fa-trash-can"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- LIST VIEW --}}
        <div class="files-list-table-wrapper" id="filesList" style="display: none;">
            <table class="files-list-table">
                <thead>
                    <tr>
                        <th style="min-width: 240px;">Nama</th>
                        <th style="width: 140px;">Ukuran</th>
                        <th style="width: 160px;">Dimodifikasi</th>
                        <th style="width: 140px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($files as $file)
                        <tr class="file-row-item" data-file-id="{{ $file->id }}" data-file-name="{{ $file->original_name }}" data-is-folder="{{ $file->is_folder ? '1' : '0' }}">
                            {{-- Nama Kolom --}}
                            <td>
                                <div class="files-list-name-col"
                                     @if($file->is_folder && $category !== 'trash')
                                         onclick="window.location.href='{{ route('files.index', ['folder' => $file->id]) }}'"
                                     @elseif(!$file->is_folder && $file->is_previewable)
                                         onclick="openPreview({{ $file->id }}, '{{ addslashes($file->original_name) }}', '{{ $file->preview_type }}')"
                                     @elseif(!$file->is_folder)
                                         onclick="downloadFile({{ $file->id }})"
                                     @endif>
                                    @if($file->preview_type === 'image')
                                        <img src="{{ $file->thumbnail_name ? route('files.thumbnail', $file->id) : route('files.preview', $file->id) }}" alt="" class="files-list-thumb" loading="lazy">
                                    @else
                                        <i class="fas {{ $file->icon }} file-icon" style="color: {{ $file->icon_color }};"></i>
                                    @endif
                                    <span class="files-list-name-text" title="{{ $file->original_name }}">
                                        {{ $file->original_name }}
                                    </span>
                                </div>
                            </td>

                            {{-- Ukuran --}}
                            <td>{{ $file->human_size }}</td>

                            {{-- Dimodifikasi --}}
                            <td>{{ $file->updated_at ? $file->updated_at->diffForHumans() : $file->created_at->diffForHumans() }}</td>

                            {{-- Aksi --}}
                            <td>
                                <div class="files-list-actions">
                                    @if($category === 'trash')
                                        <button class="icon-btn-sm" onclick="event.stopPropagation(); restoreFile({{ $file->id }})" title="Pulihkan">
                                            <i class="fas fa-rotate-left"></i>
                                        </button>
                                        <button class="icon-btn-sm danger" data-name="{{ $file->original_name }}" onclick="event.stopPropagation(); forceDelete({{ $file->id }}, this.getAttribute('data-name'))" title="Hapus Permanen">
                                            <i class="fas fa-trash-can"></i>
                                        </button>
                                    @else
                                        <button class="icon-btn-sm star-btn-{{ $file->id }} {{ $file->is_starred ? 'starred' : '' }}" onclick="event.stopPropagation(); toggleStar({{ $file->id }}, this)" title="{{ $file->is_starred ? 'Hapus dari favorit' : 'Tandai favorit' }}">
                                            <i class="{{ $file->is_starred ? 'fas' : 'far' }} fa-star"></i>
                                        </button>
                                        @if(!$file->is_folder)
                                            <button class="icon-btn-sm" onclick="event.stopPropagation(); shareFile({{ $file->id }})" title="Bagikan">
                                                <i class="fas fa-share-nodes"></i>
                                            </button>
                                            <button class="icon-btn-sm" onclick="event.stopPropagation(); downloadFile({{ $file->id }})" title="Download">
                                                <i class="fas fa-download"></i>
                                            </button>
                                        @endif
                                        <button class="icon-btn-sm danger" data-name="{{ $file->original_name }}" onclick="event.stopPropagation(); confirmDelete({{ $file->id }}, this.getAttribute('data-name'))" title="Hapus">
                                            <i class="fas fa-trash-can"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-{{ $category === 'trash' ? 'trash-can' : ($category === 'starred' ? 'star' : 'folder-open') }}"></i>
            <h3>
                @if(request('search')) Tidak ada file yang cocok
                @elseif($category === 'trash') Sampah kosong
                @elseif($category === 'starred') Belum ada favorit
                @else Folder kosong
                @endif
            </h3>
            <p>
                @if(request('search')) Coba kata kunci lain atau hapus filter pencarian.
                @elseif($category === 'trash') File yang Anda hapus akan muncul di sini.
                @elseif($category === 'starred') Tandai file penting dengan bintang agar mudah diakses.
                @else Tarik dan lepas file ke halaman ini atau gunakan tombol tambah di kanan bawah.
                @endif
            </p>
        </div>
    @endif
</div>

{{-- FAB (Floating Action Button) --}}
@if($category !== 'trash' && $category !== 'starred')
    <div class="fab-container" id="fabContainer">
        <div class="fab-menu" id="fabMenu">
            <button class="fab-menu-item" onclick="triggerUpload()">
                <span class="fab-menu-label">Upload File</span>
                <span class="fab-menu-icon"><i class="fas fa-file-arrow-up"></i></span>
            </button>
            <button class="fab-menu-item" onclick="triggerImageUpload()">
                <span class="fab-menu-label">Upload Gambar</span>
                <span class="fab-menu-icon"><i class="fas fa-image"></i></span>
            </button>
            <button class="fab-menu-item" onclick="openCreateFolderModal()">
                <span class="fab-menu-label">Buat Folder</span>
                <span class="fab-menu-icon"><i class="fas fa-folder-plus"></i></span>
            </button>
        </div>
        <button class="fab-main" id="fabMain" onclick="toggleFab()">
            <i class="fas fa-plus" id="fabIcon"></i>
        </button>
    </div>
@endif

{{-- Modal: Create Folder --}}
<div class="modal-overlay" id="createFolderModal">
    <div class="modal-content">
        <div class="modal-icon" style="background: var(--accent-subtle); color: var(--accent);">
            <i class="fas fa-folder-plus"></i>
        </div>
        <h3 class="modal-title">Buat Folder Baru</h3>
        <div style="margin: 16px 0 24px; text-align: left;">
            <label style="font-size: 13px; color: var(--text-secondary); display: block; margin-bottom: 6px;">Nama Folder</label>
            <input type="text" id="folderNameInput" class="input" placeholder="Contoh: Dokumen Kerja">
        </div>
        <div class="modal-actions">
            <button class="btn btn-secondary" onclick="closeCreateFolderModal()">Batal</button>
            <button class="btn btn-primary" onclick="createFolder()">Buat</button>
        </div>
    </div>
</div>

{{-- Modal: Share --}}
<div class="modal-overlay" id="shareModal">
    <div class="modal-content" style="max-width: 520px;">
        <div class="modal-icon" style="background: var(--accent-subtle); color: var(--accent);">
            <i class="fas fa-share-nodes"></i>
        </div>
        <h3 class="modal-title">Bagikan File</h3>
        <p class="modal-text">Siapa saja yang memiliki link atau QR Code ini dapat mengunduh file.</p>
        
        <div style="display: flex; gap: 20px; margin: 20px 0; text-align: left;" class="share-modal-body">
            <!-- Kolom Kiri: Link & Expiry -->
            <div style="flex: 1.2;">
                <label style="font-size: 13px; color: var(--text-secondary); display: block; margin-bottom: 6px;">Kadaluarsa Link</label>
                <select id="shareExpiry" class="input" style="height: 40px; cursor: pointer; margin-bottom: 16px;">
                    <option value="never">Tidak pernah</option>
                    <option value="1h">1 jam</option>
                    <option value="1d">1 hari</option>
                    <option value="7d">7 hari</option>
                    <option value="30d">30 hari</option>
                </select>

                <label style="font-size: 13px; color: var(--text-secondary); display: block; margin-bottom: 6px;">Tautan</label>
                <div style="display: flex; gap: 6px;">
                    <input type="text" id="shareLinkInput" class="input" readonly style="font-size: 12px; flex: 1; height: 40px;">
                    <button class="btn btn-primary" onclick="copyShareLink()" style="flex-shrink: 0; height: 40px; padding: 0 12px;">
                        <i class="far fa-copy"></i>
                    </button>
                </div>
                <p id="shareExpiryInfo" style="font-size: 11px; color: var(--text-tertiary); margin-top: 6px;">
                    Link aktif selamanya
                </p>
            </div>

            <!-- Kolom Kanan: QR Code -->
            <div class="share-qr-col" style="flex: 0.8; display: flex; flex-direction: column; align-items: center; justify-content: center; border-left: 1px solid var(--border-subtle); padding-left: 20px;">
                <label style="font-size: 13px; color: var(--text-secondary); display: block; margin-bottom: 10px;">QR Code</label>
                <div id="qrcode" style="background: #fff; padding: 8px; border-radius: 8px; box-shadow: var(--shadow-sm);"></div>
            </div>
        </div>
        
        <div class="modal-actions" style="margin-top: 10px;">
            <button class="btn btn-secondary" onclick="closeShareModal()">Tutup</button>
            <button class="btn btn-danger" onclick="unshareFile()">Cabut Link</button>
        </div>
    </div>
</div>

{{-- Modal: Delete Confirmation --}}
<div class="modal-overlay" id="deleteModal">
    <div class="modal-content">
        <div class="modal-icon">
            <i class="fas fa-trash-can"></i>
        </div>
        <h3 class="modal-title">Hapus?</h3>
        <p class="modal-text">
            <strong id="deleteFileName"></strong> akan dipindah ke sampah.
        </p>
        <div class="modal-actions">
            <button class="btn btn-secondary" onclick="closeDeleteModal()">Batal</button>
            <button class="btn btn-danger" onclick="executeDelete()">Ya, Hapus</button>
        </div>
    </div>
</div>

{{-- Modal: Force Delete Confirmation --}}
<div class="modal-overlay" id="forceDeleteModal">
    <div class="modal-content">
        <div class="modal-icon">
            <i class="fas fa-triangle-exclamation"></i>
        </div>
        <h3 class="modal-title">Hapus Permanen?</h3>
        <p class="modal-text">
            <strong id="forceDeleteFileName"></strong> akan dihapus <strong>permanen</strong> dan tidak bisa dikembalikan.
        </p>
        <div class="modal-actions">
            <button class="btn btn-secondary" onclick="closeForceDeleteModal()">Batal</button>
            <button class="btn btn-danger" onclick="executeForceDelete()">Ya, Hapus</button>
        </div>
    </div>
</div>

{{-- Modal: Empty Trash --}}
<div class="modal-overlay" id="emptyTrashModal">
    <div class="modal-content">
        <div class="modal-icon">
            <i class="fas fa-triangle-exclamation"></i>
        </div>
        <h3 class="modal-title">Kosongkan Sampah?</h3>
        <p class="modal-text">
            Semua file di sampah akan dihapus <strong>permanen</strong> dan tidak bisa dikembalikan.
        </p>
        <div class="modal-actions">
            <button class="btn btn-secondary" onclick="closeEmptyTrashModal()">Batal</button>
            <button class="btn btn-danger" onclick="executeEmptyTrash()">Ya, Kosongkan</button>
        </div>
    </div>
</div>

{{-- Toast Container --}}
<div id="toastContainer" class="toast-container"></div>

{{-- Preview Modal --}}
@include('files.partials.preview-modal')

@push('scripts')
<script>
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;
    const CURRENT_FOLDER = {{ $folderId ?? 'null' }};
    
    // ========== VIEW TOGGLE (GRID / LIST) ==========
    function setViewMode(mode) {
        const grid = document.getElementById('filesGrid');
        const list = document.getElementById('filesList');
        const btnGrid = document.querySelector('.view-toggle-btn[data-view="grid"]');
        const btnList = document.querySelector('.view-toggle-btn[data-view="list"]');
        
        if (mode === 'list') {
            if (grid) grid.style.display = 'none';
            if (list) list.style.display = 'block';
            btnGrid?.classList.remove('active');
            btnList?.classList.add('active');
            localStorage.setItem('cloud-sahaja-view-mode', 'list');
        } else {
            if (grid) grid.style.display = 'grid';
            if (list) list.style.display = 'none';
            btnGrid?.classList.add('active');
            btnList?.classList.remove('active');
            localStorage.setItem('cloud-sahaja-view-mode', 'grid');
        }
    }

    // Inisialisasi state view mode dari localStorage
    (function initViewMode() {
        const savedMode = localStorage.getItem('cloud-sahaja-view-mode') || 'grid';
        setViewMode(savedMode);
    })();

    // ========== SEARCH BAR ==========
    const searchInput = document.getElementById('filesSearchInput');
    let searchTimeout = null;

    searchInput?.addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        const value = e.target.value.trim();
        const clearBtn = document.getElementById('searchClearBtn');
        if (clearBtn) clearBtn.style.display = value ? 'flex' : 'none';

        searchTimeout = setTimeout(() => {
            const url = new URL(window.location.href);
            if (value) url.searchParams.set('search', value);
            else url.searchParams.delete('search');
            window.location.href = url.toString();
        }, 500);
    });

    searchInput?.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            clearTimeout(searchTimeout);
            const url = new URL(window.location.href);
            const value = e.target.value.trim();
            if (value) url.searchParams.set('search', value);
            else url.searchParams.delete('search');
            window.location.href = url.toString();
        }
    });

    function clearSearch() {
        const url = new URL(window.location.href);
        url.searchParams.delete('search');
        window.location.href = url.toString();
    }

    // ========== FILTER DROPDOWN ==========
    function toggleFilterDropdown(e) {
        if (e) e.stopPropagation();
        const dropdown = document.getElementById('filterDropdown');
        if (!dropdown) return;
        dropdown.classList.toggle('show');
    }

    document.addEventListener('click', (e) => {
        const dropdown = document.getElementById('filterDropdown');
        if (dropdown && !e.target.closest('.filter-dropdown') && !e.target.closest('.view-toggle-btn')) {
            dropdown.classList.remove('show');
        }
    });

    // ========== FAB ==========
    let fabOpen = false;
    
    function toggleFab() {
        fabOpen = !fabOpen;
        const fab = document.getElementById('fabContainer');
        const icon = document.getElementById('fabIcon');
        
        if (fabOpen) {
            fab?.classList.add('open');
            if (icon) icon.className = 'fas fa-xmark';
        } else {
            fab?.classList.remove('open');
            if (icon) icon.className = 'fas fa-plus';
        }
    }
    
    function triggerUpload() {
        toggleFab();
        document.getElementById('fileInput').click();
    }
    
    function triggerImageUpload() {
        toggleFab();
        document.getElementById('imageInput').click();
    }
    
    function openCreateFolderModal() {
        toggleFab();
        document.getElementById('folderNameInput').value = '';
        document.getElementById('createFolderModal').classList.add('show');
        setTimeout(() => document.getElementById('folderNameInput').focus(), 100);
    }
    
    function closeCreateFolderModal() {
        document.getElementById('createFolderModal').classList.remove('show');
    }
    
    async function createFolder() {
        const name = document.getElementById('folderNameInput').value.trim();
        if (!name) return showToast('Nama folder wajib diisi', 'error');
        
        try {
            const res = await fetch('{{ route('files.createFolder') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    name: name,
                    parent_id: CURRENT_FOLDER,
                }),
            });
            const data = await res.json();
            
            if (data.success) {
                closeCreateFolderModal();
                showToast('Folder berhasil dibuat', 'success');
                setTimeout(() => window.location.reload(), 500);
            } else {
                showToast(data.message || 'Gagal membuat folder', 'error');
            }
        } catch (e) {
            showToast('Gagal membuat folder', 'error');
        }
    }
    
    // ========== DRAG & DROP KE .files-wrapper & UPLOAD ==========
    const dropZone = document.querySelector('.files-wrapper');
    const fileInput = document.getElementById('fileInput');
    const imageInput = document.getElementById('imageInput');

    if (dropZone) {
        ['dragenter', 'dragover'].forEach(ev => {
            dropZone.addEventListener(ev, (e) => {
                e.preventDefault();
                dropZone.style.border = '2px dashed var(--accent)';
                dropZone.style.borderRadius = 'var(--radius-lg)';
            });
        });
        ['dragleave', 'drop'].forEach(ev => {
            dropZone.addEventListener(ev, (e) => {
                e.preventDefault();
                dropZone.style.border = '';
                dropZone.style.borderRadius = '';
            });
        });
        dropZone.addEventListener('drop', (e) => {
            addFilesToQueue(Array.from(e.dataTransfer.files));
        });
    }
    
    fileInput?.addEventListener('change', (e) => {
        addFilesToQueue(Array.from(e.target.files));
        fileInput.value = '';
    });
    
    imageInput?.addEventListener('change', (e) => {
        addFilesToQueue(Array.from(e.target.files));
        imageInput.value = '';
    });
    
    // ========== UPLOAD QUEUE SYSTEM ==========
    let uploadQueue = [];
    let isUploading = false;

    function addFilesToQueue(files) {
        uploadQueue.push(...files);
        if (!isUploading) {
            processQueue();
        }
    }

    async function processQueue() {
        if (uploadQueue.length === 0) {
            isUploading = false;
            // Reload halaman jika semua antrean selesai dan tidak ada error
            if (!document.querySelector('.upload-progress-item.error')) {
                setTimeout(() => window.location.reload(), 1000);
            }
            return;
        }

        isUploading = true;
        const file = uploadQueue.shift(); // Ambil file pertama di antrean
        await uploadFile(file);           // Tunggu sampai file ini selesai
        processQueue();                   // Lanjut ke file berikutnya
    }

    function loadHighRes(imgElement) {
        if (imgElement.dataset.loaded) return;
        imgElement.dataset.loaded = 'true';
        
        const highResUrl = imgElement.dataset.src;
        const highResImage = new Image();
        
        highResImage.onload = () => {
            imgElement.src = highResUrl;
            imgElement.classList.remove('lazy-blur-img');
            imgElement.classList.add('lazy-img-loaded');
        };
        highResImage.src = highResUrl;
    }

    function generateThumbnail(file) {
        return new Promise((resolve) => {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = new Image();
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    const MAX = 400; // Max width/height thumbnail
                    let w = img.width, h = img.height;
                    if (w > h && w > MAX) { h *= MAX / w; w = MAX; }
                    else if (h > MAX) { w *= MAX / h; h = MAX; }
                    
                    canvas.width = w;
                    canvas.height = h;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, w, h);
                    resolve(canvas.toDataURL('image/jpeg', 0.7)); // Kualitas 70%
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(file);
        });
    }

    async function uploadFile(file) {
        const progressId = 'progress-' + Date.now() + '-' + Math.random();
        const progressEl = document.createElement('div');
        progressEl.className = 'upload-progress-item';
        progressEl.id = progressId;
        progressEl.innerHTML = `
            <div class="progress-info">
                <i class="fas fa-file"></i>
                <span class="progress-name">${escapeHtml(file.name)}</span>
                <span class="progress-status">0%</span>
            </div>
            <div class="progress-bar"><div class="progress-bar-fill" style="width: 0%"></div></div>
        `;
        document.getElementById('uploadProgressList').appendChild(progressEl);

        const CHUNK_SIZE = 5 * 1024 * 1024; // 5MB per chunk (Aman dari limit Cloudflare)
        const totalChunks = Math.ceil(file.size / CHUNK_SIZE);
        const uploadId = Date.now() + '-' + Math.random().toString(36).substring(7);

        let uploadedSize = 0;
        const progressBar = progressEl.querySelector('.progress-bar-fill');
        const progressStatus = progressEl.querySelector('.progress-status');

        let thumbnailBase64 = null;
        if (file.type.startsWith('image/')) {
            thumbnailBase64 = await generateThumbnail(file);
        }

        try {
            for (let i = 0; i < totalChunks; i++) {
                const start = i * CHUNK_SIZE;
                const end = Math.min(start + CHUNK_SIZE, file.size);
                const chunk = file.slice(start, end);

                const formData = new FormData();
                formData.append('file', chunk);
                formData.append('chunk_index', i);
                formData.append('total_chunks', totalChunks);
                formData.append('file_name', file.name);
                formData.append('upload_id', uploadId);
                if (CURRENT_FOLDER) formData.append('parent_id', CURRENT_FOLDER);
                if (i === totalChunks - 1 && thumbnailBase64) {
                    formData.append('thumbnail_base64', thumbnailBase64);
                }

                const response = await new Promise((resolve, reject) => {
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '{{ route('files.uploadChunk') }}');
                    xhr.setRequestHeader('X-CSRF-TOKEN', CSRF);
                    xhr.setRequestHeader('Accept', 'application/json');
                    
                    xhr.onload = () => {
                        if (xhr.status >= 200 && xhr.status < 300) {
                            resolve(JSON.parse(xhr.responseText));
                        } else {
                            try { reject(JSON.parse(xhr.responseText)); } 
                            catch (e) { reject({ message: 'Upload chunk gagal' }); }
                        }
                    };
                    xhr.onerror = () => reject({ message: 'Koneksi terputus saat mengirim chunk' });
                    xhr.send(formData);
                });

                if (!response.success) throw response;

                uploadedSize += chunk.size;
                const percent = Math.round((uploadedSize / file.size) * 100);
                progressBar.style.width = percent + '%';
                progressStatus.textContent = percent + '%';
            }

            progressEl.classList.add('success');
            progressStatus.innerHTML = '<i class="fas fa-check"></i>';
            setTimeout(() => { progressEl.remove(); }, 800);

        } catch (err) {
            progressEl.classList.add('error');
            progressStatus.innerHTML = '<i class="fas fa-times"></i> ' + (err.message || 'Gagal');
        }
    }
    
    // ========== STAR ==========
    async function toggleStar(id, btn) {
        try {
            const res = await fetch('/files/' + id + '/star', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            });
            const data = await res.json();
            
            if (data.success) {
                const buttons = document.querySelectorAll('.star-btn-' + id);
                const badges = document.querySelectorAll('.star-badge-' + id);
                
                buttons.forEach(b => {
                    const icon = b.querySelector('i');
                    if (data.is_starred) {
                        if (icon) icon.className = 'fas fa-star';
                        b.classList.add('starred');
                    } else {
                        if (icon) icon.className = 'far fa-star';
                        b.classList.remove('starred');
                    }
                });

                badges.forEach(bg => {
                    bg.style.display = data.is_starred ? 'block' : 'none';
                });

                showToast(data.is_starred ? 'Ditambahkan ke favorit' : 'Dihapus dari favorit', data.is_starred ? 'success' : 'info');
            }
        } catch (e) {
            showToast('Gagal mengubah favorit', 'error');
        }
    }
    
    // ========== SHARE ==========
    let currentShareId = null;
    let currentQrCode = null; // Variabel untuk menyimpan instance QR
    
    async function shareFile(id) {
        currentShareId = id;
        try {
            const expirySelect = document.getElementById('shareExpiry');
            if (expirySelect) expirySelect.value = 'never';

            const res = await fetch('/files/' + id + '/share', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            });
            const data = await res.json();
            
            if (data.success) {
                document.getElementById('shareLinkInput').value = data.url;
                
                // Render QR Code
                const qrContainer = document.getElementById('qrcode');
                if (qrContainer) {
                    qrContainer.innerHTML = ''; // Bersihkan QR sebelumnya
                    if (typeof QRCode !== 'undefined') {
                        currentQrCode = new QRCode(qrContainer, {
                            text: data.url,
                            width: 128,
                            height: 128,
                            colorDark : "#000000",
                            colorLight : "#ffffff",
                            correctLevel : QRCode.CorrectLevel.H
                        });
                    }
                }

                // Update info expiry
                const info = document.getElementById('shareExpiryInfo');
                if (data.expires_human && info) {
                    info.textContent = 'Link kadaluarsa dalam ' + data.expires_human;
                } else if (info) {
                    info.textContent = 'Link aktif selamanya';
                }

                document.getElementById('shareModal').classList.add('show');
            } else {
                showToast(data.message || 'Gagal membuat link', 'error');
            }
        } catch (e) {
            showToast('Gagal membuat link', 'error');
        }
    }
    
    function copyShareLink() {
        const input = document.getElementById('shareLinkInput');
        input.select();
        document.execCommand('copy');
        showToast('Link disalin ke clipboard', 'success');
    }
    
    function closeShareModal() {
        document.getElementById('shareModal').classList.remove('show');
        currentShareId = null;
    }
    
    async function unshareFile() {
        if (!currentShareId) return;
        try {
            await fetch('/files/' + currentShareId + '/share', {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            });
            closeShareModal();
            showToast('Link dicabut', 'success');
        } catch (e) {
            showToast('Gagal mencabut link', 'error');
        }
    }

    document.getElementById('shareExpiry')?.addEventListener('change', async (e) => {
        if (!currentShareId) return;
        const expiry = e.target.value;
        try {
            const res = await fetch('/files/' + currentShareId + '/share', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ expiry: expiry }),
            });
            const data = await res.json();
            const info = document.getElementById('shareExpiryInfo');
            if (expiry === 'never') {
                if (info) info.textContent = 'Link aktif selamanya';
            } else {
                const labels = { '1h': '1 jam', '1d': '1 hari', '7d': '7 hari', '30d': '30 hari' };
                if (info) info.textContent = 'Link kadaluarsa dalam ' + (data.expires_human || labels[expiry]);
            }
            showToast('Pengaturan kadaluarsa link diperbarui', 'success');
        } catch (err) {
            showToast('Gagal mengubah kadaluarsa link', 'error');
        }
    });
    
    // ========== DELETE ==========
    let pendingDeleteId = null;
    
    function confirmDelete(id, name) {
        pendingDeleteId = id;
        document.getElementById('deleteFileName').textContent = name;
        document.getElementById('deleteModal').classList.add('show');
    }
    
    function closeDeleteModal() {
        pendingDeleteId = null;
        document.getElementById('deleteModal').classList.remove('show');
    }
    
    async function executeDelete() {
        if (!pendingDeleteId) return;
        try {
            const res = await fetch('/files/' + pendingDeleteId, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            });
            const data = await res.json();
            if (data.success) {
                document.querySelectorAll(`[data-file-id="${pendingDeleteId}"]`).forEach(el => el.remove());
                showToast('File dipindah ke sampah', 'success');
                if (document.querySelectorAll('.file-card-item').length === 0) window.location.reload();
            }
        } catch (e) {
            showToast('Gagal menghapus', 'error');
        }
        closeDeleteModal();
    }
    
    // ========== FORCE DELETE ==========
    let pendingForceDeleteId = null;
    
    function forceDelete(id, name) {
        pendingForceDeleteId = id;
        document.getElementById('forceDeleteFileName').textContent = name;
        document.getElementById('forceDeleteModal').classList.add('show');
    }
    
    function closeForceDeleteModal() {
        pendingForceDeleteId = null;
        document.getElementById('forceDeleteModal').classList.remove('show');
    }
    
    async function executeForceDelete() {
        if (!pendingForceDeleteId) return;
        try {
            const res = await fetch('/files/' + pendingForceDeleteId + '/force', {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            });
            const data = await res.json();
            if (data.success) {
                document.querySelectorAll(`[data-file-id="${pendingForceDeleteId}"]`).forEach(el => el.remove());
                showToast('File dihapus permanen', 'success');
                if (document.querySelectorAll('.file-card-item').length === 0) window.location.reload();
            }
        } catch (e) {
            showToast('Gagal menghapus', 'error');
        }
        closeForceDeleteModal();
    }
    
    // ========== RESTORE ==========
    async function restoreFile(id) {
        try {
            const res = await fetch('/files/' + id + '/restore', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            });
            const data = await res.json();
            if (data.success) {
                document.querySelectorAll(`[data-file-id="${id}"]`).forEach(el => el.remove());
                showToast('File dipulihkan', 'success');
                if (document.querySelectorAll('.file-card-item').length === 0) window.location.reload();
            }
        } catch (e) {
            showToast('Gagal memulihkan', 'error');
        }
    }
    
    // ========== EMPTY TRASH ==========
    function confirmEmptyTrash() {
        document.getElementById('emptyTrashModal').classList.add('show');
    }
    
    function closeEmptyTrashModal() {
        document.getElementById('emptyTrashModal').classList.remove('show');
    }
    
    async function executeEmptyTrash() {
        try {
            const res = await fetch('{{ route('files.emptyTrash') }}', {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            });
            const data = await res.json();
            if (data.success) {
                showToast('Sampah dikosongkan', 'success');
                setTimeout(() => window.location.reload(), 500);
            }
        } catch (e) {
            showToast('Gagal mengosongkan sampah', 'error');
        }
        closeEmptyTrashModal();
    }
    
    // ========== DOWNLOAD ==========
    function downloadFile(id) {
        window.location.href = '/files/' + id + '/download';
    }
    
    // ========== UTILS ==========
    function escapeHtml(str) {
        return String(str).replace(/[&<>"']/g, m => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m]));
    }
    
    function showToast(message, type = 'info') {
        const container = document.getElementById('toastContainer');
        if (!container) return;
        const toast = document.createElement('div');
        toast.className = 'toast toast-' + type;
        const icon = type === 'success' ? 'circle-check' : (type === 'error' ? 'circle-exclamation' : 'circle-info');
        toast.innerHTML = `<i class="fas fa-${icon}"></i> <span>${escapeHtml(message)}</span>`;
        container.appendChild(toast);
        setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 3000);
    }
    
    // ========== CLOSE MODAL ON ESC ==========
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeCreateFolderModal();
            closeShareModal();
            closeDeleteModal();
            closeForceDeleteModal();
            closeEmptyTrashModal();
            const filterDropdown = document.getElementById('filterDropdown');
            if (filterDropdown) filterDropdown.classList.remove('show');
        }
    });
    
    // Close FAB saat klik di luar
    document.addEventListener('click', (e) => {
        if (fabOpen && !e.target.closest('.fab-container')) {
            toggleFab();
        }
    });

    // ========== PREVIEW ==========
    let currentPreviewId = null;
    let currentPreviewType = null;
    let siblings = [];
    let siblingsIndex = 0;

    async function openPreview(id, name, type) {
        if (!type || type === 'null' || type === '') {
            showToast('File ini tidak mendukung preview', 'info');
            return;
        }
        
        currentPreviewId = id;
        currentPreviewType = type;
        
        document.getElementById('previewFileName').textContent = name;
        document.getElementById('previewFileIcon').innerHTML = `<i class="fas ${type === 'pdf' ? 'fa-file-pdf' : (type === 'video' ? 'fa-file-video' : (type === 'image' ? 'fa-file-image' : 'fa-file'))}"></i>`;
        document.getElementById('previewDownloadBtn').href = '/files/' + id + '/download';
        
        renderPreview(id, type, name);
        
        const modal = document.getElementById('previewModal');
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
        
        try {
            const res = await fetch('/files/' + id + '/siblings');
            const data = await res.json();
            if (data.success) {
                siblings = data.files;
                siblingsIndex = siblings.findIndex(f => f.id === id);
                updateNavButtons();
            }
        } catch (e) {
            siblings = [];
            updateNavButtons();
        }
    }
    
    function renderPreview(id, type, name) {
        const body = document.getElementById('previewBody');
        body.innerHTML = '';
        const url = '/files/' + id + '/preview';
        
        const spinner = document.createElement('div');
        spinner.className = 'preview-spinner';
        spinner.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i>';
        
        if (type === 'image') {
            body.appendChild(spinner);
            const img = document.createElement('img');
            img.className = 'preview-image';
            img.alt = name;
            img.onload = () => { spinner.remove(); };
            img.onerror = () => {
                spinner.remove();
                body.innerHTML = '<div style="color: var(--text-secondary); text-align: center;"><i class="fas fa-image" style="font-size: 48px; opacity: 0.3; margin-bottom: 12px; display: block;"></i>Gagal memuat gambar.</div>';
            };
            img.src = url;
            body.appendChild(img);
        } 
        else if (type === 'video') {
            body.appendChild(spinner);
            const video = document.createElement('video');
            video.src = url;
            video.controls = true;
            video.autoplay = true;
            video.className = 'preview-video';
            video.onloadeddata = () => { spinner.remove(); };
            video.onerror = () => {
                spinner.remove();
                body.innerHTML = '<div style="color: var(--text-secondary); text-align: center;"><i class="fas fa-video-slash" style="font-size: 48px; opacity: 0.3; margin-bottom: 12px; display: block;"></i>Gagal memutar video.</div>';
            };
            body.appendChild(video);
        } 
        else if (type === 'audio') {
            const wrapper = document.createElement('div');
            wrapper.className = 'preview-audio-wrapper';
            wrapper.innerHTML = '<i class="fas fa-music" style="font-size: 64px; color: var(--accent); margin-bottom: 24px;"></i><div style="font-size: 16px; font-weight: 600; margin-bottom: 24px; color: var(--text-primary);">' + escapeHtml(name) + '</div>';
            const audio = document.createElement('audio');
            audio.src = url;
            audio.controls = true;
            audio.autoplay = true;
            audio.style.width = '100%';
            audio.style.maxWidth = '480px';
            wrapper.appendChild(audio);
            body.appendChild(wrapper);
        } 
        else if (type === 'pdf') {
            body.appendChild(spinner);
            const container = document.createElement('div');
            container.className = 'pdf-container';
            const iframe = document.createElement('iframe');
            iframe.className = 'preview-pdf';
            iframe.onload = () => { spinner.remove(); };
            iframe.onerror = () => {
                spinner.remove();
                body.innerHTML = '<div style="color: var(--text-secondary); text-align: center;"><i class="fas fa-file-pdf" style="font-size: 48px; opacity: 0.3; margin-bottom: 12px; display: block;"></i>Gagal memuat dokumen PDF.</div>';
            };
            iframe.src = url + '#toolbar=1&navpanes=0&view=FitH';
            container.appendChild(iframe);
            body.appendChild(container);
        } 
        else if (type === 'text') {
            body.appendChild(spinner);
            fetch(url).then(r => {
                if (!r.ok) throw new Error();
                return r.text();
            }).then(text => {
                spinner.remove();
                const pre = document.createElement('pre');
                pre.className = 'preview-text';
                if (text.length > 500000) {
                    text = text.substring(0, 500000) + '\n\n... [File dipotong 500KB]';
                }
                pre.textContent = text;
                body.appendChild(pre);
            }).catch(() => {
                spinner.remove();
                body.innerHTML = '<div style="color: var(--text-secondary); text-align: center;"><i class="fas fa-file-lines" style="font-size: 48px; opacity: 0.3; margin-bottom: 12px; display: block;"></i>Gagal memuat file teks.</div>';
            });
        } 
        else {
            body.innerHTML = '<div style="color: var(--text-secondary);">Tipe file tidak didukung.</div>';
        }
    }
    
    function closePreview() {
        const modal = document.getElementById('previewModal');
        modal.classList.remove('show');
        document.body.style.overflow = '';
        const media = modal.querySelectorAll('video, audio');
        media.forEach(m => m.pause());
        currentPreviewId = null;
        currentPreviewType = null;
        document.getElementById('previewBody').innerHTML = '';
        const dropdown = document.getElementById('previewDropdown');
        if (dropdown) dropdown.classList.remove('show');
    }
    
    function navigatePreview(delta) {
        if (siblings.length === 0) return;
        let nextIdx = siblingsIndex + delta;
        while (nextIdx >= 0 && nextIdx < siblings.length && !siblings[nextIdx].is_previewable) {
            nextIdx += delta;
        }
        if (nextIdx < 0 || nextIdx >= siblings.length) return;
        siblingsIndex = nextIdx;
        const f = siblings[siblingsIndex];
        currentPreviewId = f.id;
        currentPreviewType = f.preview_type;
        document.getElementById('previewFileName').textContent = f.name;
        const iconEl = document.getElementById('previewFileIcon');
        if (iconEl) {
            iconEl.innerHTML = `<i class="fas ${f.preview_type === 'pdf' ? 'fa-file-pdf' : (f.preview_type === 'video' ? 'fa-file-video' : (f.preview_type === 'image' ? 'fa-file-image' : 'fa-file'))}"></i>`;
        }
        document.getElementById('previewDownloadBtn').href = '/files/' + f.id + '/download';
        renderPreview(f.id, f.preview_type, f.name);
        updateNavButtons();
    }
    
    function updateNavButtons() {
        const prevBtn = document.getElementById('previewPrev');
        const nextBtn = document.getElementById('previewNext');
        let hasPrev = false, hasNext = false;
        for (let i = siblingsIndex - 1; i >= 0; i--) {
            if (siblings[i].is_previewable) { hasPrev = true; break; }
        }
        for (let i = siblingsIndex + 1; i < siblings.length; i++) {
            if (siblings[i].is_previewable) { hasNext = true; break; }
        }
        if (prevBtn) prevBtn.style.display = hasPrev ? 'flex' : 'none';
        if (nextBtn) nextBtn.style.display = hasNext ? 'flex' : 'none';
    }
    
    document.addEventListener('keydown', (e) => {
        const modal = document.getElementById('previewModal');
        if (!modal || !modal.classList.contains('show')) return;
        if (e.key === 'Escape') closePreview();
        if (e.key === 'ArrowLeft') navigatePreview(-1);
        if (e.key === 'ArrowRight') navigatePreview(1);
    });

    const previewModalEl = document.getElementById('previewModal');
    if (previewModalEl) {
        previewModalEl.addEventListener('click', (e) => {
            if (e.target.id === 'previewModal' || e.target.id === 'previewBody') {
                closePreview();
            }
        });
    }

    function togglePreviewMenu(e) {
        if (e) e.stopPropagation();
        const dropdown = document.getElementById('previewDropdown');
        if (dropdown) dropdown.classList.toggle('show');
    }

    // Tutup dropdown saat klik di luar
    document.addEventListener('click', (e) => {
        const dropdown = document.getElementById('previewDropdown');
        if (dropdown && dropdown.classList.contains('show') && !e.target.closest('.preview-kebab-wrapper')) {
            dropdown.classList.remove('show');
        }
    });
</script>
@endpush
@endsection
