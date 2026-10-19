

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

function openSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    if (sidebar) {
        sidebar.classList.add('mobile-open');
    }
    if (backdrop) {
        backdrop.classList.add('show');
    }
    document.body.style.overflow = 'hidden';
}

function closeSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    if (sidebar) {
        sidebar.classList.remove('mobile-open');
    }
    if (backdrop) {
        backdrop.classList.remove('show');
    }
    document.body.style.overflow = '';
}

window.openSidebar = openSidebar;
window.closeSidebar = closeSidebar;

// Ganti event listener di tombol hamburger (mobileToggle) menjadi openSidebar()
// Listener mobileToggle juga dipasang jika elemen sudah ada di DOM
document.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.getElementById('mobileToggle');
    if (toggleBtn) {
        toggleBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            openSidebar();
        });
    }
});

// Listener global click outside
document.addEventListener('click', (e) => {
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.getElementById('mobileToggle');
    
    if (
        sidebar &&
        sidebar.classList.contains('mobile-open') &&
        !sidebar.contains(e.target) &&
        (!toggleBtn || !toggleBtn.contains(e.target))
    ) {
        closeSidebar();
    }
});
