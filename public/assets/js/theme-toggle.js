/**
 * RealEstatePro — Dark / Light theme toggle
 * Folder: public/assets/js/theme-toggle.js
 *
 * The initial theme is already applied in an inline <script> in
 * layouts/app.blade.php (before CSS loads) to avoid a flash of
 * the wrong theme. This file only wires up the toggle buttons.
 */
(function () {
    'use strict';

    var STORAGE_KEY = 'rep-theme';

    function currentTheme() {
        return document.documentElement.getAttribute('data-theme') || 'light';
    }

    function applyTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem(STORAGE_KEY, theme);
        updateIcons(theme);
    }

    function updateIcons(theme) {
        // Any toggle button - identified by id starting with "repThemeToggle" - or
        // explicitly marked with the .js-theme-toggle class works.
        document.querySelectorAll('[id^="repThemeToggle"] i, .js-theme-toggle i').forEach(function (icon) {
            icon.classList.remove('bi-moon-stars-fill', 'bi-sun-fill');
            icon.classList.add(theme === 'dark' ? 'bi-sun-fill' : 'bi-moon-stars-fill');
        });
    }

    function toggleTheme() {
        applyTheme(currentTheme() === 'dark' ? 'light' : 'dark');
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateIcons(currentTheme());

        // Wire up every theme toggle button present on the page, however many there are
        // (e.g. auth layout renders a mobile-row button AND a desktop-row button).
        document.querySelectorAll('[id^="repThemeToggle"], .js-theme-toggle').forEach(function (btn) {
            btn.addEventListener('click', toggleTheme);
        });
    });
})();
