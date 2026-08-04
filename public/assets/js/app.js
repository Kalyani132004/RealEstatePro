/**
 * RealEstatePro — Core frontend behaviour
 * Folder: public/assets/js/app.js
 * Loaded on every page via layouts/app.blade.php
 */
(function () {
    'use strict';

    /* ------------------------------------------------------------------
     * 1. Page loader — hide once window has fully loaded
     * ------------------------------------------------------------------ */
    window.addEventListener('load', function () {
        var loader = document.getElementById('repPageLoader');
        if (loader) {
            loader.classList.add('rep-loaded');
            setTimeout(function () { loader.style.display = 'none'; }, 400);
        }
    });

    /* ------------------------------------------------------------------
     * 2. Toast notifications
     * Usage: repToast('Enquiry sent successfully', 'success' | 'danger' | 'warning')
     * ------------------------------------------------------------------ */
    window.repToast = function (message, type) {
        type = type || 'success';
        var container = document.getElementById('repToastContainer');
        if (!container) return;

        var icons = {
            success: 'bi-check-circle-fill',
            danger: 'bi-x-circle-fill',
            warning: 'bi-exclamation-triangle-fill'
        };

        var toast = document.createElement('div');
        toast.className = 'rep-toast rep-toast-' + type;
        toast.innerHTML =
            '<i class="bi ' + (icons[type] || icons.success) + '" style="font-size:1.25rem;"></i>' +
            '<div class="flex-grow-1 rep-small">' + message + '</div>' +
            '<button type="button" class="btn-close btn-sm" aria-label="Close"></button>';

        container.appendChild(toast);

        var closeBtn = toast.querySelector('.btn-close');
        var dismiss = function () {
            toast.classList.add('rep-toast-hide');
            setTimeout(function () { toast.remove(); }, 350);
        };
        closeBtn.addEventListener('click', dismiss);
        setTimeout(dismiss, 4500);
    };

    /* ------------------------------------------------------------------
     * 3. Animated stat counters (Home page) — IntersectionObserver
     * ------------------------------------------------------------------ */
    var counters = document.querySelectorAll('.rep-counter');
    if (counters.length && 'IntersectionObserver' in window) {
        var animateCounter = function (el) {
            var target = parseInt(el.getAttribute('data-target'), 10) || 0;
            var current = 0;
            var increment = Math.max(1, Math.ceil(target / 60));
            var step = function () {
                current += increment;
                if (current >= target) {
                    el.textContent = target.toLocaleString();
                } else {
                    el.textContent = current.toLocaleString();
                    requestAnimationFrame(step);
                }
            };
            step();
        };

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    animateCounter(entry.target);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.4 });

        counters.forEach(function (el) { observer.observe(el); });
    }

    /* ------------------------------------------------------------------
     * 4. Wishlist / Save Property toggle (AJAX)
     * Endpoint wired in Phase 14: POST /saved-properties/toggle
     * ------------------------------------------------------------------ */
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('.rep-wishlist-btn');
        if (!btn) return;
        e.preventDefault();

        var propertyId = btn.getAttribute('data-property-id');
        var icon = btn.querySelector('i');
        var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/saved-properties/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({ property_id: propertyId })
        })
            .then(function (res) {
                if (res.status === 401) {
                    window.location.href = '/login';
                    return null;
                }
                return res.json();
            })
            .then(function (data) {
                if (!data) return;
                if (data.saved) {
                    icon.classList.remove('bi-heart');
                    icon.classList.add('bi-heart-fill', 'text-danger');
                    repToast('Property added to your saved list', 'success');
                } else {
                    icon.classList.remove('bi-heart-fill', 'text-danger');
                    icon.classList.add('bi-heart');
                    repToast('Property removed from your saved list', 'warning');
                }
            })
            .catch(function () {
                repToast('Something went wrong. Please try again.', 'danger');
            });
    });

    /* ------------------------------------------------------------------
     * 5. Newsletter form (footer) — placeholder submit handler
     * ------------------------------------------------------------------ */
    var newsletterForm = document.getElementById('repNewsletterForm');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function (e) {
            e.preventDefault();
            repToast('Thanks for subscribing to RealEstatePro updates!', 'success');
            newsletterForm.reset();
        });
    }
})();
