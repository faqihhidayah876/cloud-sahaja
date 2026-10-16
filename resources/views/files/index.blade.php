@extends('layouts.app')

@section('title', ($category === 'trash' ? 'Sampah' : ($category === 'starred' ? 'Favorit' : 'File Saya')) . ' — Cloud Sahaja')
@section('page-title', $category === 'trash' ? 'Sampah' : ($category === 'starred' ? 'Favorit' : 'File Saya'))

@section('content')
<div class="files-wrapper">
    {{-- Breadcrumb (kalau di dalam folder) --}}
    @if(!empty($breadcrumb))
        <div class="breadcrumb">
            <a href="{{ route('files.index') }}" class="breadcrumb-item">
                <i class="fas fa-home"></i> File Saya
            </a>
            @foreach($breadcrumb as $crumb)
                <i class="fas fa-chevron-right breadcrumb-sep"></i>
                <a href="{{ route('files.index', ['folder' => $crumb['id']]) }}" class="breadcrumb-item">
                    {{ $crumb['name'] }}
                </a>
            @endforeach
        </div>
    @endif
    
    {{-- Trash header (kalau di trash) --}}
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
    
    {{-- Upload Area (hanya di halaman File Saya, bukan trash/starred) --}}
    @if($category !== 'trash' && $category !== 'starred')
        <div class="upload-zone" id="uploadZone">
            <input type="file" id="fileInput" multiple style="display:none;">
            <div class="upload-icon">
                <i class="fas fa-cloud-arrow-up"></i>
            </div>
            <div class="upload-title">Tarik & lepas file di sini</div>
            <div class="upload-subtitle">atau</div>
            <button type="button" class="btn btn-primary" onclick="document.getElementById('fileInput').click()">
                <i class="fas fa-plus"></i> Pilih File
            </button>
            <div class="upload-hint">Maksimal 100MB per file &middot; Kuota total 2GB</div>
        </div>
    @endif
    
    {{-- Upload Progress --}}
    <div id="uploadProgressList" class="upload-progress-list"></div>
    
    {{-- Filter Bar (sembunyikan untuk trash & starred) --}}
    @if($category !== 'trash' && $category !== 'starred')
        <div class="filter-bar">
            <a href="{{ route('files.index', ['category' => 'all'] + ($folderId ? ['folder' => $folderId] : [])) }}" 
               class="filter-btn {{ $category === 'all' ? 'active' : '' }}">
                <i class="fas fa-layer-group"></i> Semua
            </a>
            <a href="{{ route('files.index', ['category' => 'image'] + ($folderId ? ['folder' => $folderId] : [])) }}" 
               class="filter-btn {{ $category === 'image' ? 'active' : '' }}">
                <i class="fas fa-image"></i> Gambar
            </a>
            <a href="{{ route('files.index', ['category' => 'document'] + ($folderId ? ['folder' => $folderId] : [])) }}" 
               class="filter-btn {{ $category === 'document' ? 'active' : '' }}">
                <i class="fas fa-file-lines"></i> Dokumen
            </a>
            <a href="{{ route('files.index', ['category' => 'video'] + ($folderId ? ['folder' => $folderId] : [])) }}" 
               class="filter-btn {{ $category === 'video' ? 'active' : '' }}">
                <i class="fas fa-video"></i> Video
            </a>
            <a href="{{ route('files.index', ['category' => 'audio'] + ($folderId ? ['folder' => $folderId] : [])) }}" 
               class="filter-btn {{ $category === 'audio' ? 'active' : '' }}">
                <i class="fas fa-music"></i> Audio
            </a>
        </div>
    @endif
    
    {{-- Files Grid --}}
    @if($files->count() > 0)
        <div class="files-grid" id="filesGrid">
            @foreach($files as $file)
                <div class="file-card" data-file-id="{{ $file->id }}" data-file-name="{{ $file->original_name }}" data-is-folder="{{ $file->is_folder ? '1' : '0' }}">
                    {{-- Star badge --}}
                    @if($file->is_starred && $category !== 'trash')
                        <div class="star-badge">
                            <i class="fas fa-star"></i>
                        </div>
                    @endif
                    
                    {{-- Preview --}}
                    <div class="file-preview"
                         @if(!$file->is_folder && $file->is_previewable)
                             onclick="openPreview({{ $file->id }}, '{{ addslashes($file->original_name) }}', '{{ $file->preview_type }}')"
                             style="cursor: pointer;"
                         @endif>
                        @if($file->is_folder)
                            <a href="{{ route('files.index', ['folder' => $file->id]) }}" style="display: contents;">
                                <i class="fas {{ $file->icon }}" style="color: {{ $file->icon_color }}; font-size: 36px;"></i>
                            </a>
                        @else
                            <i class="fas {{ $file->icon }}" style="color: {{ $file->icon_color }}; font-size: 36px;"></i>
                        @endif
                    </div>
                    
                    {{-- Info --}}
                    <div class="file-info">
                        <div class="file-name" title="{{ $file->original_name }}">
                            @if($file->is_folder && $category !== 'trash')
                                <a href="{{ route('files.index', ['folder' => $file->id]) }}" style="color: inherit;">
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
                    
                    {{-- Actions --}}
                    <div class="file-actions">
                        @if($category === 'trash')
                            <button class="icon-btn-sm" onclick="restoreFile({{ $file->id }})" title="Pulihkan">
                                <i class="fas fa-rotate-left"></i>
                            </button>
                            <button class="icon-btn-sm danger" data-name="{{ $file->original_name }}" onclick="forceDelete({{ $file->id }}, this.getAttribute('data-name'))" title="Hapus Permanen">
                                <i class="fas fa-trash-can"></i>
                            </button>
                        @else
                            <button class="icon-btn-sm {{ $file->is_starred ? 'starred' : '' }}" onclick="toggleStar({{ $file->id }}, this)" title="{{ $file->is_starred ? 'Hapus dari favorit' : 'Tandai favorit' }}">
                                <i class="{{ $file->is_starred ? 'fas' : 'far' }} fa-star"></i>
                            </button>
                            @if(!$file->is_folder)
                                <button class="icon-btn-sm" onclick="shareFile({{ $file->id }})" title="Bagikan">
                                    <i class="fas fa-share-nodes"></i>
                                </button>
                                <button class="icon-btn-sm" onclick="downloadFile({{ $file->id }})" title="Download">
                                    <i class="fas fa-download"></i>
                                </button>
                            @endif
                            <button class="icon-btn-sm danger" data-name="{{ $file->original_name }}" onclick="confirmDelete({{ $file->id }}, this.getAttribute('data-name'))" title="Hapus">
                                <i class="fas fa-trash-can"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="empty-state">
            <i class="fas fa-{{ $category === 'trash' ? 'trash-can' : ($category === 'starred' ? 'star' : 'folder-open') }}"></i>
            <h3>
                @if($category === 'trash') Sampah kosong
                @elseif($category === 'starred') Belum ada favorit
                @else Folder kosong
                @endif
            </h3>
            <p>
                @if($category === 'trash') File yang Anda hapus akan muncul di sini.
                @elseif($category === 'starred') Tandai file penting dengan bintang agar mudah diakses.
                @else Upload file pertama Anda dengan tombol di atas.
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
    
    {{-- Hidden input untuk image only --}}
    <input type="file" id="imageInput" accept="image/*" multiple style="display:none;">
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
    <div class="modal-content">
        <div class="modal-icon" style="background: var(--accent-subtle); color: var(--accent);">
            <i class="fas fa-share-nodes"></i>
        </div>
        <h3 class="modal-title">Bagikan File</h3>
        <p class="modal-text">Siapa saja yang memiliki link ini dapat mengunduh file.</p>
        <div style="display: flex; gap: 8px; margin: 16px 0;">
            <input type="text" id="shareLinkInput" class="input" readonly style="font-size: 13px;">
            <button class="btn btn-primary" onclick="copyShareLink()" style="flex-shrink: 0;">
                <i class="far fa-copy"></i> Salin
            </button>
        </div>
        <div class="modal-actions">
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
    
    // ========== UPLOAD ==========
    const uploadZone = document.getElementById('uploadZone');
    const fileInput = document.getElementById('fileInput');
    const imageInput = document.getElementById('imageInput');
    
    if (uploadZone) {
        ['dragenter', 'dragover'].forEach(ev => {
            uploadZone.addEventListener(ev, (e) => { e.preventDefault(); uploadZone.classList.add('dragover'); });
        });
        ['dragleave', 'drop'].forEach(ev => {
            uploadZone.addEventListener(ev, (e) => { e.preventDefault(); uploadZone.classList.remove('dragover'); });
        });
        uploadZone.addEventListener('drop', (e) => {
            Array.from(e.dataTransfer.files).forEach(f => uploadFile(f));
        });
    }
    
    fileInput?.addEventListener('change', (e) => {
        Array.from(e.target.files).forEach(f => uploadFile(f));
        fileInput.value = '';
    });
    
    imageInput?.addEventListener('change', (e) => {
        Array.from(e.target.files).forEach(f => uploadFile(f));
        imageInput.value = '';
    });
    
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
        
        const formData = new FormData();
        formData.append('file', file);
        if (CURRENT_FOLDER) formData.append('parent_id', CURRENT_FOLDER);
        
        try {
            const xhr = new XMLHttpRequest();
            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                    const percent = (e.loaded / e.total) * 100;
                    progressEl.querySelector('.progress-bar-fill').style.width = percent + '%';
                    progressEl.querySelector('.progress-status').textContent = Math.round(percent) + '%';
                }
            });
            
            const response = await new Promise((resolve, reject) => {
                xhr.onload = () => {
                    if (xhr.status >= 200 && xhr.status < 300) resolve(JSON.parse(xhr.responseText));
                    else { try { reject(JSON.parse(xhr.responseText)); } catch (e) { reject({ message: 'Upload gagal' }); } }
                };
                xhr.onerror = () => reject({ message: 'Koneksi terputus' });
                xhr.open('POST', '{{ route('files.store') }}');
                xhr.setRequestHeader('X-CSRF-TOKEN', CSRF);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.send(formData);
            });
            
            if (response.success) {
                progressEl.classList.add('success');
                progressEl.querySelector('.progress-status').innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(() => { progressEl.remove(); window.location.reload(); }, 800);
            } else {
                throw response;
            }
        } catch (err) {
            progressEl.classList.add('error');
            progressEl.querySelector('.progress-status').innerHTML = '<i class="fas fa-times"></i> ' + (err.message || 'Gagal');
            setTimeout(() => progressEl.remove(), 3000);
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
                const icon = btn.querySelector('i');
                if (data.is_starred) {
                    icon.className = 'fas fa-star';
                    btn.classList.add('starred');
                    showToast('Ditambahkan ke favorit', 'success');
                } else {
                    icon.className = 'far fa-star';
                    btn.classList.remove('starred');
                    showToast('Dihapus dari favorit', 'info');
                }
            }
        } catch (e) {
            showToast('Gagal mengubah favorit', 'error');
        }
    }
    
    // ========== SHARE ==========
    let currentShareId = null;
    
    async function shareFile(id) {
        currentShareId = id;
        try {
            const res = await fetch('/files/' + id + '/share', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            });
            const data = await res.json();
            
            if (data.success) {
                document.getElementById('shareLinkInput').value = data.url;
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
    
    // ========== DELETE ==========
    let pendingDeleteId = null;
    let pendingDeleteElement = null;
    
    function confirmDelete(id, name) {
        pendingDeleteId = id;
        pendingDeleteElement = document.querySelector(`[data-file-id="${id}"]`);
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
                pendingDeleteElement?.remove();
                showToast('File dipindah ke sampah', 'success');
                if (document.querySelectorAll('.file-card').length === 0) window.location.reload();
            }
        } catch (e) {
            showToast('Gagal menghapus', 'error');
        }
        closeDeleteModal();
    }
    
    // ========== FORCE DELETE ==========
    let pendingForceDeleteId = null;
    let pendingForceDeleteElement = null;
    
    function forceDelete(id, name) {
        pendingForceDeleteId = id;
        pendingForceDeleteElement = document.querySelector(`[data-file-id="${id}"]`);
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
                pendingForceDeleteElement?.remove();
                showToast('File dihapus permanen', 'success');
                if (document.querySelectorAll('.file-card').length === 0) window.location.reload();
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
                document.querySelector(`[data-file-id="${id}"]`)?.remove();
                showToast('File dipulihkan', 'success');
                if (document.querySelectorAll('.file-card').length === 0) window.location.reload();
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
        console.log('[PREVIEW] openPreview called:', id, name, type);
        
        if (!type || type === 'null' || type === '') {
            showToast('File ini tidak mendukung preview', 'info');
            return;
        }
        
        currentPreviewId = id;
        currentPreviewType = type;
        
        document.getElementById('previewFileName').textContent = name;
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
            console.error('[PREVIEW] siblings fetch error:', e);
            siblings = [];
            updateNavButtons();
        }
    }
    
    function renderPreview(id, type, name) {
        const body = document.getElementById('previewBody');
        body.innerHTML = '';
        const url = '/files/' + id + '/preview';
        
        if (type === 'image') {
            const img = document.createElement('img');
            img.src = url;
            img.alt = name;
            img.className = 'preview-image';
            body.appendChild(img);
        } 
        else if (type === 'video') {
            const video = document.createElement('video');
            video.src = url;
            video.controls = true;
            video.className = 'preview-video';
            body.appendChild(video);
        } 
        else if (type === 'audio') {
            const wrapper = document.createElement('div');
            wrapper.className = 'preview-audio-wrapper';
            wrapper.innerHTML = '<i class="fas fa-music" style="font-size: 64px; color: var(--accent); margin-bottom: 24px;"></i><div style="font-size: 16px; font-weight: 600; margin-bottom: 24px; color: var(--text-primary);">' + escapeHtml(name) + '</div>';
            const audio = document.createElement('audio');
            audio.src = url;
            audio.controls = true;
            audio.style.width = '100%';
            audio.style.maxWidth = '480px';
            wrapper.appendChild(audio);
            body.appendChild(wrapper);
        } 
        else if (type === 'pdf') {
            const iframe = document.createElement('iframe');
            iframe.src = url;
            iframe.className = 'preview-pdf';
            body.appendChild(iframe);
        } 
        else if (type === 'text') {
            const pre = document.createElement('pre');
            pre.className = 'preview-text';
            pre.textContent = 'Memuat...';
            body.appendChild(pre);
            fetch(url).then(r => r.text()).then(text => {
                if (text.length > 500000) {
                    text = text.substring(0, 500000) + '\n\n... [File dipotong 500KB]';
                }
                pre.textContent = text;
            }).catch(() => { pre.textContent = 'Gagal memuat file.'; });
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
        prevBtn.style.display = hasPrev ? 'flex' : 'none';
        nextBtn.style.display = hasNext ? 'flex' : 'none';
    }
    
    document.addEventListener('keydown', (e) => {
        const modal = document.getElementById('previewModal');
        if (!modal || !modal.classList.contains('show')) return;
        if (e.key === 'Escape') closePreview();
        if (e.key === 'ArrowLeft') navigatePreview(-1);
        if (e.key === 'ArrowRight') navigatePreview(1);
    });
</script>
@endpush
@endsection
