<div class="preview-overlay" id="previewModal">
    <!-- Top Bar -->
    <div class="preview-topbar">
        <div class="preview-file-info">
            <div class="preview-file-icon" id="previewFileIcon">
                <i class="fas fa-file"></i>
            </div>
            <div class="preview-file-text">
                <div class="preview-file-name" id="previewFileName">-</div>
                <div class="preview-file-brand">Cloud Sahaja</div>
            </div>
        </div>
        
        <div class="preview-actions">
            <!-- Kebab Menu -->
            <div class="preview-kebab-wrapper">
                <button type="button" class="preview-action-btn" onclick="togglePreviewMenu(event)" title="Menu">
                    <i class="fas fa-ellipsis-vertical"></i>
                </button>
                <div class="preview-dropdown" id="previewDropdown">
                    <a href="#" class="preview-dropdown-item" id="previewDownloadBtn">
                        <i class="fas fa-download"></i> Download File
                    </a>
                </div>
            </div>
            <!-- Close Button -->
            <button type="button" class="preview-action-btn" onclick="closePreview()" title="Tutup">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
    </div>

    <!-- Navigasi Kiri/Kanan (Opsional, tetap dipertahankan) -->
    <button type="button" class="preview-nav preview-nav-prev" id="previewPrev" onclick="navigatePreview(-1)">
        <i class="fas fa-chevron-left"></i>
    </button>
    <button type="button" class="preview-nav preview-nav-next" id="previewNext" onclick="navigatePreview(1)">
        <i class="fas fa-chevron-right"></i>
    </button>

    <!-- Area Preview -->
    <div class="preview-body" id="previewBody">
        <!-- Konten di-inject via JS -->
    </div>
</div>
