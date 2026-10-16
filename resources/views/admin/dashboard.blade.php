@extends('layouts.app')

@section('title', 'Admin Dashboard — Cloud Sahaja')
@section('page-title', 'Admin Dashboard')

@section('content')
<div class="admin-wrapper">
    {{-- Stats Row --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Total User</span>
                <i class="fas fa-users" style="color: var(--text-tertiary);"></i>
            </div>
            <div class="stat-value">{{ $stats['total_users'] }}</div>
            <div class="stat-desc">Pengguna terdaftar</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Total File</span>
                <i class="fas fa-file" style="color: var(--text-tertiary);"></i>
            </div>
            <div class="stat-value">{{ $stats['total_files'] }}</div>
            <div class="stat-desc">Semua file aktif</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Storage Terpakai</span>
                <i class="fas fa-database" style="color: var(--text-tertiary);"></i>
            </div>
            <div class="stat-value">{{ formatBytes($stats['total_size']) }}</div>
            <div class="stat-desc">Total penyimpanan</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">File Dibagikan</span>
                <i class="fas fa-share-nodes" style="color: var(--text-tertiary);"></i>
            </div>
            <div class="stat-value">{{ $stats['total_shared'] }}</div>
            <div class="stat-desc">Link publik aktif</div>
        </div>
    </div>
    
    {{-- Users Table --}}
    <div class="admin-table-card">
        <div class="admin-table-header">
            <h2 style="font-size: 16px; font-weight: 600;">Daftar Pengguna</h2>
        </div>
        
        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Pengguna</th>
                        <th>Files</th>
                        <th>Storage</th>
                        <th>Kuota</th>
                        <th>Status</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        @php
                            $used = $user->storage_used;
                            $quota = $user->storage_quota;
                            $percent = $quota > 0 ? min(100, ($used / $quota) * 100) : 0;
                            $barColor = $percent >= 90 ? 'var(--danger)' : ($percent >= 70 ? 'var(--warning)' : 'var(--accent)');
                        @endphp
                        <tr id="user-row-{{ $user->id }}">
                            <td>
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    <div class="admin-avatar">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight: 600; color: var(--text-primary);">
                                            {{ $user->name }}
                                            @if($user->is_admin)
                                                <span class="admin-badge">ADMIN</span>
                                            @endif
                                        </div>
                                        <div style="font-size: 12px; color: var(--text-secondary);">
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->file_count }}</td>
                            <td>{{ formatBytes($used) }}</td>
                            <td>
                                <div style="display: flex; flex-direction: column; gap: 4px;">
                                    <div style="font-size: 12px; color: var(--text-secondary);">
                                        {{ formatBytes($quota) }}
                                    </div>
                                    <div class="quota-bar">
                                        <div class="quota-bar-fill" style="width: {{ $percent }}%; background: {{ $barColor }};"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($user->is_disabled)
                                    <span class="status-badge disabled">Disabled</span>
                                @else
                                    <span class="status-badge active">Active</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                    <button type="button" class="admin-action-btn" 
                                        data-name="{{ $user->name }}"
                                        onclick="openQuotaModal({{ $user->id }}, this.getAttribute('data-name'), {{ $quota / 1073741824 }})"
                                        title="Atur Kuota">
                                        <i class="fas fa-sliders"></i>
                                    </button>
                                    <button type="button" class="admin-action-btn" 
                                        onclick="toggleDisable({{ $user->id }})"
                                        title="{{ $user->is_disabled ? 'Aktifkan' : 'Nonaktifkan' }}">
                                        <i class="fas {{ $user->is_disabled ? 'fa-play' : 'fa-pause' }}"></i>
                                    </button>
                                    <button type="button" class="admin-action-btn danger" 
                                        data-name="{{ $user->name }}"
                                        onclick="confirmResetFiles({{ $user->id }}, this.getAttribute('data-name'))"
                                        title="Reset Files">
                                        <i class="fas fa-broom"></i>
                                    </button>
                                    <button type="button" class="admin-action-btn danger" 
                                        data-name="{{ $user->name }}"
                                        onclick="confirmDeleteUser({{ $user->id }}, this.getAttribute('data-name'))"
                                        title="Hapus User">
                                        <i class="fas fa-trash-can"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-tertiary);">
                                Belum ada pengguna terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal: Quota --}}
<div class="modal-overlay" id="quotaModal">
    <div class="modal-content">
        <div class="modal-icon" style="background: var(--accent-subtle); color: var(--accent);">
            <i class="fas fa-sliders"></i>
        </div>
        <h3 class="modal-title">Atur Kuota</h3>
        <p class="modal-text">Ubah kuota penyimpanan untuk <strong id="quotaUserName"></strong>.</p>
        <div style="margin: 16px 0;">
            <label style="font-size: 13px; color: var(--text-secondary); display: block; margin-bottom: 6px; text-align: left;">
                Kuota (GB)
            </label>
            <input type="number" id="quotaInput" class="input" step="0.1" min="0.1" max="1000" placeholder="2.0">
        </div>
        <div class="modal-actions">
            <button class="btn btn-secondary" onclick="closeQuotaModal()">Batal</button>
            <button class="btn btn-primary" onclick="submitQuota()">Simpan</button>
        </div>
    </div>
</div>

{{-- Modal: Confirm Delete User --}}
<div class="modal-overlay" id="deleteUserModal">
    <div class="modal-content">
        <div class="modal-icon">
            <i class="fas fa-triangle-exclamation"></i>
        </div>
        <h3 class="modal-title">Hapus User?</h3>
        <p class="modal-text">
            <strong id="deleteUserName"></strong> dan <strong>semua filenya</strong> akan dihapus permanen. Tidak bisa dikembalikan.
        </p>
        <div class="modal-actions">
            <button class="btn btn-secondary" onclick="closeDeleteModal()">Batal</button>
            <button class="btn btn-danger" onclick="executeDeleteUser()">Ya, Hapus</button>
        </div>
    </div>
</div>

{{-- Modal: Confirm Reset Files --}}
<div class="modal-overlay" id="resetFilesModal">
    <div class="modal-content">
        <div class="modal-icon">
            <i class="fas fa-broom"></i>
        </div>
        <h3 class="modal-title">Reset Files?</h3>
        <p class="modal-text">
            Semua file milik <strong id="resetUserName"></strong> akan dihapus. Akun tetap ada.
        </p>
        <div class="modal-actions">
            <button class="btn btn-secondary" onclick="closeResetModal()">Batal</button>
            <button class="btn btn-danger" onclick="executeResetFiles()">Ya, Reset</button>
        </div>
    </div>
</div>

<div id="toastContainer" class="toast-container"></div>
@endsection

@push('scripts')
<script>
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;
    let currentQuotaUserId = null;
    let currentDeleteUserId = null;
    let currentResetUserId = null;

    // ========== QUOTA ==========
    function openQuotaModal(userId, userName, currentQuotaGB) {
        currentQuotaUserId = userId;
        document.getElementById('quotaUserName').textContent = userName;
        document.getElementById('quotaInput').value = parseFloat(currentQuotaGB).toFixed(1);
        document.getElementById('quotaModal').classList.add('show');
        setTimeout(() => document.getElementById('quotaInput').focus(), 100);
    }
    
    function closeQuotaModal() {
        document.getElementById('quotaModal').classList.remove('show');
        currentQuotaUserId = null;
    }
    
    async function submitQuota() {
        const quotaGB = parseFloat(document.getElementById('quotaInput').value);
        if (!quotaGB || quotaGB < 0.1) {
            showToast('Kuota minimal 0.1 GB', 'error');
            return;
        }
        
        try {
            const res = await fetch('/admin/users/' + currentQuotaUserId + '/quota', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: JSON.stringify({ quota_gb: quotaGB }),
            });
            const data = await res.json();
            if (data.success) {
                closeQuotaModal();
                showToast('Kuota berhasil diubah', 'success');
                setTimeout(() => window.location.reload(), 800);
            } else {
                showToast(data.message || 'Gagal', 'error');
            }
        } catch (e) {
            showToast('Gagal koneksi', 'error');
        }
    }
    
    // ========== TOGGLE DISABLE ==========
    async function toggleDisable(userId) {
        try {
            const res = await fetch('/admin/users/' + userId + '/toggle-disable', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            });
            const data = await res.json();
            if (data.success) {
                showToast('Status user diubah', 'success');
                setTimeout(() => window.location.reload(), 600);
            } else {
                showToast(data.message || 'Gagal', 'error');
            }
        } catch (e) {
            showToast('Gagal koneksi', 'error');
        }
    }
    
    // ========== DELETE USER ==========
    function confirmDeleteUser(userId, name) {
        currentDeleteUserId = userId;
        document.getElementById('deleteUserName').textContent = name;
        document.getElementById('deleteUserModal').classList.add('show');
    }
    
    function closeDeleteModal() {
        document.getElementById('deleteUserModal').classList.remove('show');
        currentDeleteUserId = null;
    }
    
    async function executeDeleteUser() {
        if (!currentDeleteUserId) return;
        try {
            const res = await fetch('/admin/users/' + currentDeleteUserId, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            });
            const data = await res.json();
            if (data.success) {
                closeDeleteModal();
                showToast('User dihapus', 'success');
                setTimeout(() => window.location.reload(), 600);
            } else {
                showToast(data.message || 'Gagal', 'error');
            }
        } catch (e) {
            showToast('Gagal koneksi', 'error');
        }
    }
    
    // ========== RESET FILES ==========
    function confirmResetFiles(userId, name) {
        currentResetUserId = userId;
        document.getElementById('resetUserName').textContent = name;
        document.getElementById('resetFilesModal').classList.add('show');
    }
    
    function closeResetModal() {
        document.getElementById('resetFilesModal').classList.remove('show');
        currentResetUserId = null;
    }
    
    async function executeResetFiles() {
        if (!currentResetUserId) return;
        try {
            const res = await fetch('/admin/users/' + currentResetUserId + '/reset-files', {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            });
            const data = await res.json();
            if (data.success) {
                closeResetModal();
                showToast('File user direset', 'success');
                setTimeout(() => window.location.reload(), 600);
            } else {
                showToast(data.message || 'Gagal', 'error');
            }
        } catch (e) {
            showToast('Gagal koneksi', 'error');
        }
    }
    
    // Utils
    function showToast(msg, type) {
        const container = document.getElementById('toastContainer');
        if (!container) return;
        const toast = document.createElement('div');
        toast.className = 'toast toast-' + (type || 'info');
        const icon = type === 'success' ? 'circle-check' : (type === 'error' ? 'circle-exclamation' : 'circle-info');
        toast.innerHTML = `<i class="fas fa-${icon}"></i> <span>${msg}</span>`;
        container.appendChild(toast);
        setTimeout(() => { toast.style.opacity = '0'; setTimeout(() => toast.remove(), 300); }, 3000);
    }

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeQuotaModal();
            closeDeleteModal();
            closeResetModal();
        }
    });
</script>
@endpush
