@extends('layouts.app')

@section('title', 'Profil Pengguna — Cloud Sahaja')
@section('page-title', 'Profil Pengguna')

@section('content')
<div class="profile-wrapper">
    {{-- Status Alerts --}}
    @if (session('status') === 'profile-updated')
        <div class="alert-success">
            <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
            Informasi profil Anda berhasil diperbarui.
        </div>
    @elseif (session('status') === 'password-updated')
        <div class="alert-success">
            <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
            Kata sandi Anda berhasil diperbarui.
        </div>
    @elseif (session('status') === 'avatar-deleted')
        <div class="alert-success">
            <i class="fas fa-check-circle" style="margin-right: 8px;"></i>
            Foto profil berhasil dihapus.
        </div>
    @endif

    {{-- Header / Summary Card --}}
    <div class="profile-header-card">
        <div style="display: flex; align-items: center; gap: 20px;">
            <div class="profile-avatar-large" id="headerAvatarLarge">
                @if(Auth::user()->avatar)
                    <img src="{{ Auth::user()->avatar }}" alt="Avatar"
                         style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                @else
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                @endif
            </div>
            <div>
                <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                    <h2 style="font-size: 20px; font-weight: 600; color: var(--text-primary);">
                        {{ $user->name }}
                    </h2>
                    @if($user->isAdmin())
                        <span class="admin-badge" style="margin-left: 0;">ADMIN</span>
                    @endif
                </div>
                <div style="font-size: 14px; color: var(--text-secondary); margin-bottom: 8px;">
                    {{ $user->email }}
                </div>
                <div style="display: flex; gap: 16px; font-size: 12px; color: var(--text-tertiary);">
                    <span><i class="fas fa-calendar-check" style="margin-right: 4px;"></i> Bergabung: {{ $user->created_at->format('d M Y') }}</span>
                    <span><i class="fas fa-file" style="margin-right: 4px;"></i> {{ $fileCount }} file</span>
                </div>
            </div>
        </div>

        {{-- Mini Storage Card --}}
        @php
            $used = $storageUsed;
            $quota = $user->storage_quota;
            $percent = $quota > 0 ? min(100, round(($used / $quota) * 100, 1)) : 0;
            $barColor = $percent >= 90 ? 'var(--danger)' : ($percent >= 75 ? 'var(--warning)' : 'var(--accent)');
        @endphp
        <div style="min-width: 260px; background: var(--bg-subtle); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 16px;">
            <div style="display: flex; justify-content: space-between; font-size: 12px; color: var(--text-secondary); margin-bottom: 6px;">
                <span>Penyimpanan Akun</span>
                <span style="font-weight: 600; color: {{ $barColor }};">{{ $percent }}%</span>
            </div>
            <div class="storage-bar" style="margin-bottom: 8px;">
                <div class="storage-bar-fill" style="width: {{ $percent }}%; background: {{ $barColor }};"></div>
            </div>
            <div style="font-size: 12px; color: var(--text-tertiary);">
                {{ formatBytes($used) }} terpakai dari {{ formatBytes($quota) }}
            </div>
        </div>
    </div>

    {{-- Section Foto Profil --}}
    <div class="profile-card">
        <h3 class="profile-section-title">Foto Profil</h3>

        <div class="profile-avatar-wrapper">
            <div class="profile-avatar-display" id="avatarPreview">
                @if(Auth::user()->avatar)
                    <img src="{{ Auth::user()->avatar }}" alt="Avatar"
                         style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                @else
                    <span style="font-size: 32px; font-weight: 600; color: var(--accent);">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </span>
                @endif
            </div>

            <div class="profile-avatar-actions">
                <input type="file" id="avatarInput" accept="image/*" style="display: none;">
                <button type="button" class="btn btn-secondary" 
                        onclick="document.getElementById('avatarInput').click()">
                    <i class="fas fa-camera"></i> Ganti Foto
                </button>
                <button type="button" class="btn btn-secondary" 
                        id="deleteAvatarBtn"
                        onclick="deleteAvatar()" 
                        style="color: var(--danger); display: {{ Auth::user()->avatar ? 'inline-flex' : 'none' }};">
                    <i class="fas fa-trash-can"></i> Hapus
                </button>
            </div>

            <p style="font-size: 12px; color: var(--text-tertiary); margin-top: 12px; text-align: center;">
                Foto otomatis dikompres ke maks 200×200 px. Max 2MB.
            </p>
        </div>
    </div>

    {{-- Update Profile Information --}}
    <div class="profile-section-card">
        <div class="profile-section-header">
            <h3 class="profile-section-title">Informasi Akun</h3>
            <p class="profile-section-desc">Perbarui nama lengkap dan alamat email akun Anda.</p>
        </div>

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('patch')

            <div class="form-group">
                <label for="name">Nama Lengkap</label>
                <input type="text" id="name" name="name" class="input" 
                       value="{{ old('name', $user->name) }}" required autofocus>
                @if ($errors->has('name'))
                    <div class="form-error">{{ $errors->first('name') }}</div>
                @endif
            </div>

            <div class="form-group">
                <label for="email">Alamat Email</label>
                <input type="email" id="email" name="email" class="input" 
                       value="{{ old('email', $user->email) }}" required>
                @if ($errors->has('email'))
                    <div class="form-error">{{ $errors->first('email') }}</div>
                @endif
            </div>

            <div style="margin-top: 24px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    {{-- Update Password --}}
    <div class="profile-section-card">
        <div class="profile-section-header">
            <h3 class="profile-section-title">Perbarui Kata Sandi</h3>
            <p class="profile-section-desc">Gunakan kata sandi yang panjang dan acak demi keamanan akun Anda.</p>
        </div>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            <div class="form-group">
                <label for="current_password">Kata Sandi Saat Ini</label>
                <div style="position: relative;">
                    <input type="password" id="current_password" name="current_password" class="input" 
                           autocomplete="current-password" placeholder="••••••••" required>
                    <button type="button" onclick="togglePassVisibility('current_password', this)" 
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: var(--text-tertiary); font-size: 13px;">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @if ($errors->updatePassword->has('current_password'))
                    <div class="form-error">{{ $errors->updatePassword->first('current_password') }}</div>
                @endif
            </div>

            <div class="form-group">
                <label for="password">Kata Sandi Baru</label>
                <div style="position: relative;">
                    <input type="password" id="password" name="password" class="input" 
                           autocomplete="new-password" placeholder="••••••••" required>
                    <button type="button" onclick="togglePassVisibility('password', this)" 
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: var(--text-tertiary); font-size: 13px;">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @if ($errors->updatePassword->has('password'))
                    <div class="form-error">{{ $errors->updatePassword->first('password') }}</div>
                @endif
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Kata Sandi Baru</label>
                <div style="position: relative;">
                    <input type="password" id="password_confirmation" name="password_confirmation" class="input" 
                           autocomplete="new-password" placeholder="••••••••" required>
                    <button type="button" onclick="togglePassVisibility('password_confirmation', this)" 
                            style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: var(--text-tertiary); font-size: 13px;">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @if ($errors->updatePassword->has('password_confirmation'))
                    <div class="form-error">{{ $errors->updatePassword->first('password_confirmation') }}</div>
                @endif
            </div>

            <div style="margin-top: 24px;">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-key"></i> Perbarui Kata Sandi
                </button>
            </div>
        </form>
    </div>

    {{-- Danger Zone: Delete Account --}}
    <div class="profile-section-card" style="border-color: rgba(239, 68, 68, 0.25);">
        <div class="profile-section-header">
            <h3 class="profile-section-title" style="color: var(--danger);">Hapus Akun</h3>
            <p class="profile-section-desc">
                Setelah akun Anda dihapus, semua data dan file Anda di Cloud Sahaja akan dihapus secara permanen. Sebelum menghapus akun, silakan unduh semua file penting Anda.
            </p>
        </div>

        <button type="button" class="btn btn-danger" onclick="openDeleteModal()">
            <i class="fas fa-trash-alt"></i> Hapus Akun Saya
        </button>
    </div>
</div>

{{-- Delete Account Modal --}}
<div class="modal-overlay" id="deleteAccountModal">
    <div class="modal-content" style="max-width: 440px; text-align: left;">
        <div class="modal-icon" style="margin-left: 0;">
            <i class="fas fa-triangle-exclamation"></i>
        </div>
        <h3 class="modal-title" style="text-align: left;">Apakah Anda yakin ingin menghapus akun?</h3>
        <p class="modal-text" style="text-align: left; margin-bottom: 20px;">
            Tindakan ini tidak dapat dibatalkan. Masukkan kata sandi Anda untuk mengonfirmasi penghapusan akun secara permanen.
        </p>

        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')

            <div class="form-group">
                <label for="delete_password">Kata Sandi Anda</label>
                <input type="password" id="delete_password" name="password" class="input" 
                       placeholder="Masukkan kata sandi untuk konfirmasi" required>
                @if ($errors->userDeletion->has('password'))
                    <div class="form-error">{{ $errors->userDeletion->first('password') }}</div>
                @endif
            </div>

            <div class="modal-actions" style="margin-top: 24px;">
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()">
                    Batal
                </button>
                <button type="submit" class="btn btn-danger">
                    Konfirmasi Hapus Akun
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // ===== FOTO PROFIL UPLOAD & DELETE =====
    const avatarInput = document.getElementById('avatarInput');
    const avatarPreview = document.getElementById('avatarPreview');
    const deleteAvatarBtn = document.getElementById('deleteAvatarBtn');
    const headerAvatarLarge = document.getElementById('headerAvatarLarge');
    const headerUserAvatar = document.getElementById('headerUserAvatar');

    if (avatarInput) {
        avatarInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            if (file.size > 2 * 1024 * 1024) {
                showToast('Ukuran file foto maksimal 2MB.', 'error');
                avatarInput.value = '';
                return;
            }

            compressImage(file, 200, 200, 0.85, function(base64) {
                // Update tampilan avatar di halaman langsung
                const imgHtml = '<img src="' + base64 + '" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">';
                avatarPreview.innerHTML = imgHtml;
                if (headerAvatarLarge) headerAvatarLarge.innerHTML = imgHtml;
                if (headerUserAvatar) headerUserAvatar.innerHTML = imgHtml;
                if (deleteAvatarBtn) deleteAvatarBtn.style.display = 'inline-flex';

                // Simpan ke backend via PATCH /profile
                fetch('{{ route("profile.update") }}', {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        avatar_base64: base64
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showToast('Foto profil berhasil diperbarui', 'success');
                    } else {
                        showToast(data.message || 'Gagal menyimpan foto profil', 'error');
                    }
                })
                .catch(() => {
                    showToast('Terjadi kesalahan saat mengunggah foto profil', 'error');
                });
            });
        });
    }

    function compressImage(file, maxWidth, maxHeight, quality, callback) {
        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = function(event) {
            const img = new Image();
            img.src = event.target.result;
            img.onload = function() {
                let width = img.width;
                let height = img.height;

                if (width > height) {
                    if (width > maxWidth) {
                        height = Math.round((height * maxWidth) / width);
                        width = maxWidth;
                    }
                } else {
                    if (height > maxHeight) {
                        width = Math.round((width * maxHeight) / height);
                        height = maxHeight;
                    }
                }

                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);
                const compressedBase64 = canvas.toDataURL('image/jpeg', quality);
                callback(compressedBase64);
            };
        };
    }

    function deleteAvatar() {
        if (!confirm('Apakah Anda yakin ingin menghapus foto profil?')) {
            return;
        }

        fetch('{{ route("profile.deleteAvatar") }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                const initial = '{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}';
                avatarPreview.innerHTML = '<span style="font-size: 32px; font-weight: 600; color: var(--accent);">' + initial + '</span>';
                if (headerAvatarLarge) headerAvatarLarge.innerHTML = initial;
                if (headerUserAvatar) headerUserAvatar.innerHTML = initial;
                if (deleteAvatarBtn) deleteAvatarBtn.style.display = 'none';
                if (avatarInput) avatarInput.value = '';
                showToast('Foto profil berhasil dihapus', 'info');
            } else {
                showToast(data.message || 'Gagal menghapus foto profil', 'error');
            }
        })
        .catch(() => {
            showToast('Terjadi kesalahan saat menghapus foto profil', 'error');
        });
    }

    // ===== PASSWORD & MODAL HANDLERS =====
    function togglePassVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (!input) return;
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fas fa-eye';
        }
    }

    function openDeleteModal() {
        const modal = document.getElementById('deleteAccountModal');
        if (modal) {
            modal.classList.add('show');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                document.getElementById('delete_password')?.focus();
            }, 100);
        }
    }

    function closeDeleteModal() {
        const modal = document.getElementById('deleteAccountModal');
        if (modal) {
            modal.classList.remove('show');
            document.body.style.overflow = '';
        }
    }

    // Close on backdrop click
    document.getElementById('deleteAccountModal')?.addEventListener('click', (e) => {
        if (e.target.id === 'deleteAccountModal') {
            closeDeleteModal();
        }
    });

    // Toast Notification Helper
    function showToast(message, type = 'info') {
        let container = document.getElementById('toastContainer');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toastContainer';
            container.className = 'toast-container';
            document.body.appendChild(container);
        }
        const toast = document.createElement('div');
        toast.className = 'toast toast-' + type;
        const icon = type === 'success' ? 'fa-check-circle' : (type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle');
        toast.innerHTML = '<i class="fas ' + icon + '"></i><span>' + message + '</span>';
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Automatically open modal if there were errors deleting account
    @if ($errors->userDeletion->isNotEmpty())
        openDeleteModal();
    @endif
</script>
@endpush
@endsection
