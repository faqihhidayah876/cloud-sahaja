<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'avatar', 'password', 'is_admin', 'quota', 'is_disabled'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_admin'          => 'boolean',
            'is_disabled'       => 'boolean',
            'last_seen_at'      => 'datetime',
        ];
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    /**
     * Total storage yang dipakai user (bytes).
     * TIDAK pakai type-hint int — karena i386 32-bit bisa overflow > 2GB.
     */
    public function getStorageUsedAttribute()
    {
        return $this->files()->sum('size');
    }

    /**
     * Quota user dalam BYTES.
     *
     * PENTING: Kolom `quota` di DB menyimpan satuan MB (bukan bytes),
     * karena server i386 32-bit max int = 2,147,483,647 (≈2GB).
     * Saat dibaca, kita konversi MB → bytes (return float untuk antisipasi > 2GB).
     */
    public function getStorageQuotaAttribute()
    {
        $quotaMb = $this->quota ?? 2048; // default 2GB = 2048 MB
        return $quotaMb * 1048576;        // 1 MB = 1,048,576 bytes
    }

    /**
     * Quota user dalam GB (untuk display admin panel).
     */
    public function getStorageQuotaGbAttribute(): float
    {
        $quotaMb = $this->quota ?? 2048;
        return round($quotaMb / 1024, 2);
    }

    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    /**
     * Cek apakah user sudah melebihi quota.
     * Tidak pakai type-hint int pada $additionalBytes (antisipasi overflow).
     */
    public function hasExceededQuota($additionalBytes = 0): bool
    {
        return ($this->storage_used + $additionalBytes) > $this->storage_quota;
    }

    /**
     * Persentase pemakaian quota (0-100).
     */
    public function getQuotaPercentAttribute(): float
    {
        $quota = $this->storage_quota;
        if ($quota <= 0) return 0.0;
        return min(100.0, ($this->storage_used / $quota) * 100);
    }
}
