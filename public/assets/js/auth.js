/**
 * RealEstatePro — Authentication pages behaviour
 * Folder: public/assets/js/auth.js
 * Loaded only via layouts/auth.blade.php (login, register, forgot/reset password)
 */
(function () {
    'use strict';

    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.rep-toggle-password');
        if (!btn) return;

        var targetId = btn.getAttribute('data-target');
        var input = document.getElementById(targetId);
        if (!input) return;

        var icon = btn.querySelector('i');
        var isHidden = input.type === 'password';

        input.type = isHidden ? 'text' : 'password';
        icon.classList.toggle('bi-eye', !isHidden);
        icon.classList.toggle('bi-eye-slash', isHidden);
    });
})();
