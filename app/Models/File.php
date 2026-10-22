<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'parent_id',
        'original_name',
        'stored_name',
        'thumbnail_name',
        'mime_type',
        'size',
        'extension',
        'share_token',
        'share_expires_at',
        'is_public',
        'is_folder',
        'is_starred',
    ];

    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->thumbnail_name) return null;
        return route('files.thumbnail', $this->id);
    }

    protected $casts = [
        'is_public' => 'boolean',
        'is_folder' => 'boolean',
        'is_starred' => 'boolean',
        'share_expires_at' => 'datetime',
    ];

    public function isShareExpired(): bool
    {
        if (!$this->share_token) return true;
        if (!$this->share_expires_at) return false;
        return $this->share_expires_at->isPast();
    }

    /**
     * Relasi ke user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke folder parent.
     */
    public function parent()
    {
        return $this->belongsTo(File::class, 'parent_id');
    }

    /**
     * Relasi ke file / folder anak.
     */
    public function children()
    {
        return $this->hasMany(File::class, 'parent_id');
    }

    /**
     * Cek apakah folder.
     */
    public function isFolder(): bool
    {
        return $this->is_folder;
    }

    /**
     * Path lengkap file di disk (relative dari storage disk local root: storage/app/private).
     */
    public function getStoragePathAttribute(): string
    {
        return 'files/' . $this->user_id . '/' . $this->stored_name;
    }

    /**
     * Cek apakah file fisik masih ada.
     */
    public function existsOnDisk(): bool
    {
        return Storage::disk('local')->exists($this->storage_path);
    }

    /**
     * Human-readable size.
     */
    public function getHumanSizeAttribute(): string
    {
        if ($this->is_folder) {
            return '—';
        }

        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Generate share link URL.
     */
    public function getShareUrlAttribute(): ?string
    {
        if (!$this->share_token) return null;
        return url('/share/' . $this->share_token);
    }

    /**
     * Kategori file (untuk filter).
     */
    public function getCategoryAttribute(): string
    {
        if ($this->is_folder) return 'folder';

        $mime = strtolower($this->mime_type ?? '');
        
        if (str_starts_with($mime, 'image/')) return 'image';
        if (str_starts_with($mime, 'video/')) return 'video';
        if (str_starts_with($mime, 'audio/')) return 'audio';
        
        if (in_array($mime, [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'text/plain',
            'text/csv',
            'application/rtf',
        ])) return 'document';
        
        return 'other';
    }

    /**
     * Icon Font Awesome berdasarkan kategori / ekstensi.
     */
    public function getIconAttribute(): string
    {
        if ($this->is_folder) {
            return 'fa-folder';
        }

        return match ($this->category) {
            'image' => 'fa-file-image',
            'video' => 'fa-file-video',
            'audio' => 'fa-file-audio',
            'document' => 'fa-file-lines',
            default => 'fa-file',
        };
    }

    /**
     * Warna icon berdasarkan kategori.
     */
    public function getIconColorAttribute(): string
    {
        if ($this->is_folder) {
            return '#F59E0B'; // kuning untuk folder
        }

        return match ($this->category) {
            'image' => '#10B981',    // hijau
            'video' => '#EF4444',    // merah
            'audio' => '#8B5CF6',    // ungu
            'document' => '#3B82F6', // biru
            default => '#9AA0A6',    // abu
        };
    }

    /**
     * Tipe preview file (image, video, audio, pdf, text).
     */
    public function getPreviewTypeAttribute(): ?string
    {
        if ($this->is_folder) return null;

        $mime = strtolower($this->mime_type ?? '');
        $ext = strtolower($this->extension ?? '');

        if (str_starts_with($mime, 'image/') || in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp'])) {
            return 'image';
        }
        if (str_starts_with($mime, 'video/') || in_array($ext, ['mp4', 'webm', 'ogg', 'mov', 'avi', 'mkv'])) {
            return 'video';
        }
        if (str_starts_with($mime, 'audio/') || in_array($ext, ['mp3', 'wav', 'ogg', 'm4a', 'aac', 'flac'])) {
            return 'audio';
        }
        if ($mime === 'application/pdf' || $ext === 'pdf') {
            return 'pdf';
        }
        if (str_starts_with($mime, 'text/') || in_array($ext, ['txt', 'md', 'json', 'js', 'css', 'html', 'xml', 'csv', 'log', 'php', 'py', 'sh', 'sql', 'yaml', 'yml'])) {
            return 'text';
        }

        return null;
    }

    /**
     * Cek apakah file bisa di-preview.
     */
    public function getIsPreviewableAttribute(): bool
    {
        return $this->preview_type !== null;
    }

    public function isPreviewable(): bool
    {
        return $this->is_previewable;
    }
}
