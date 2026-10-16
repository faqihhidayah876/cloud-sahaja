<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'is_admin', 'quota', 'is_disabled'])]
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
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_disabled' => 'boolean',
            'last_seen_at' => 'datetime',
        ];
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function getStorageUsedAttribute(): int
    {
        return $this->files()->sum('size');
    }

    public function getStorageQuotaAttribute(): int
    {
        // Kalau admin set custom quota, pakai itu. Kalau tidak, default 2GB.
        return $this->quota ?? (2 * 1024 * 1024 * 1024);
    }

    public function isAdmin(): bool
    {
        return $this->is_admin === true;
    }

    public function hasExceededQuota(int $additionalBytes = 0): bool
    {
        return ($this->storage_used + $additionalBytes) > $this->storage_quota;
    }

    public function getQuotaPercentAttribute(): float
    {
        if ($this->storage_quota <= 0) return 0;
        return min(100, ($this->storage_used / $this->storage_quota) * 100);
    }
}
