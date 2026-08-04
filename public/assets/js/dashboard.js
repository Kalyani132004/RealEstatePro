/**
 * RealEstatePro — Dashboard shell behaviour
 * Folder: public/assets/js/dashboard.js
 * Loaded via layouts/dashboard.blade.php (User, Agent, Admin dashboards)
 */
(function () {
    'use strict';

    var sidebar = document.getElementById('repDashSidebar');
    var backdrop = document.getElementById('repDashBackdrop');
    var openBtn = document.getElementById('repSidebarOpen');
    var closeBtn = document.getElementById('repSidebarClose');

    function openSidebar() {
        if (!sidebar || !backdrop) return;
        sidebar.classList.add('rep-sidebar-open');
        backdrop.classList.add('rep-backdrop-visible');
    }

    function closeSidebar() {
        if (!sidebar || !backdrop) return;
        sidebar.classList.remove('rep-sidebar-open');
        backdrop.classList.remove('rep-backdrop-visible');
    }

    if (openBtn) openBtn.addEventListener('click', openSidebar);
    if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
    if (backdrop) backdrop.addEventListener('click', closeSidebar);

    // Close sidebar automatically when a nav link is tapped (mobile)
    document.querySelectorAll('.rep-dash-nav-link').forEach(function (link) {
        link.addEventListener('click', function () {
            if (window.innerWidth < 992) closeSidebar();
        });
    });

    /**
     * Generic "confirm before destructive action" helper.
     * Add data-confirm="Are you sure?" to any form's submit button
     * or delete link to require a native confirmation dialog first.
     */
    document.addEventListener('click', function (e) {
        var el = e.target.closest('[data-confirm]');
        if (!el) return;
        var message = el.getAttribute('data-confirm') || 'Are you sure?';
        if (!window.confirm(message)) {
            e.preventDefault();
            e.stopPropagation();
        }
    });
})();
