@extends('layouts.app')

@section('title', 'Beranda — Cloud Sahaja')
@section('page-title', 'Beranda')

@section('content')
@php
    $fileCount = Auth::user()->files()->count();
    $used = Auth::user()->storage_used;
    $quota = Auth::user()->storage_quota;
    
    $humanSize = function($bytes) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    };
@endphp

<div class="dashboard-wrapper">
    {{-- Welcome Hero --}}
    <div class="welcome-card">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div class="welcome-icon">
                <i class="fas fa-cloud"></i>
            </div>
            <div>
                <h2 style="font-size: 22px; font-weight: 600; margin-bottom: 4px;">
                    Halo, {{ Auth::user()->name }}!
                </h2>
                <p style="font-size: 14px; color: var(--text-secondary);">
                    Selamat datang di Cloud Sahaja — penyimpanan pribadi Anda.
                </p>
            </div>
        </div>
    </div>
    
    {{-- Stats Grid --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Total File</span>
                <i class="fas fa-file" style="color: var(--text-tertiary);"></i>
            </div>
            <div class="stat-value">{{ $fileCount }}</div>
            <div class="stat-desc">{{ $fileCount > 0 ? 'File tersimpan' : 'Belum ada file' }}</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Penyimpanan Terpakai</span>
                <i class="fas fa-database" style="color: var(--text-tertiary);"></i>
            </div>
            <div class="stat-value">{{ $humanSize($used) }}</div>
            <div class="stat-desc">Dari {{ $humanSize($quota) }} tersedia</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <span class="stat-label">Dibagikan</span>
                <i class="fas fa-share-nodes" style="color: var(--text-tertiary);"></i>
            </div>
            <div class="stat-value">{{ Auth::user()->files()->whereNotNull('share_token')->count() }}</div>
            <div class="stat-desc">File dengan link publik</div>
        </div>
    </div>
    
    {{-- Coming Soon / CTA --}}
    <div class="coming-soon-card">
        <div class="coming-soon-icon">
            <i class="fas fa-folder-open"></i>
        </div>
        <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 8px;">
            Mulai Upload File
        </h3>
        <p style="font-size: 14px; color: var(--text-secondary); max-width: 480px; line-height: 1.6; margin: 0 auto 20px;">
            Simpan file, dokumen, dan media Anda di Cloud Sahaja.
        </p>
        <a href="{{ route('files.index') }}" class="btn btn-primary">
            <i class="fas fa-arrow-right"></i> Buka File Manager
        </a>
    </div>
</div>
@endsection
