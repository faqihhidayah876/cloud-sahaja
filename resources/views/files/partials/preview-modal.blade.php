<div class="preview-overlay" id="previewModal">
    <div class="preview-topbar">
        <div class="preview-filename" id="previewFileName">-</div>
        <div class="preview-actions">
            <a href="#" class="preview-btn" id="previewDownloadBtn" title="Download">
                <i class="fas fa-download"></i>
            </a>
            <button type="button" class="preview-btn" onclick="closePreview()" title="Tutup">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
    </div>
    <button type="button" class="preview-nav preview-nav-prev" id="previewPrev" onclick="navigatePreview(-1)">
        <i class="fas fa-chevron-left"></i>
    </button>
    <button type="button" class="preview-nav preview-nav-next" id="previewNext" onclick="navigatePreview(1)">
        <i class="fas fa-chevron-right"></i>
    </button>
    <div class="preview-body" id="previewBody"></div>
</div>
