/**
 * RealEstatePro — Property Details Page
 * Folder: public/assets/js/property-details.js
 * Loaded on: properties/show.blade.php
 */
(function () {
    'use strict';

    /* ==================================================================
     * 1. GALLERY LIGHTBOX
     * ================================================================== */
    (function initLightbox() {
        var triggers = document.querySelectorAll('.rep-gallery-trigger');
        if (!triggers.length) return;

        var images = window.repGalleryImages || [];
        var currentIndex = 0;
        var lightboxEl = document.getElementById('galleryLightbox');
        var lightboxImage = document.getElementById('lightboxImage');
        var modal = lightboxEl ? new bootstrap.Modal(lightboxEl) : null;

        function show(index) {
            if (!images.length) return;
            currentIndex = (index + images.length) % images.length;
            lightboxImage.src = images[currentIndex];
        }

        triggers.forEach(function (trigger) {
            trigger.addEventListener('click', function (e) {
                e.preventDefault();
                show(parseInt(trigger.getAttribute('data-gallery-index'), 10) || 0);
                if (modal) modal.show();
            });
        });

        var prevBtn = document.getElementById('lightboxPrev');
        var nextBtn = document.getElementById('lightboxNext');
        if (prevBtn) prevBtn.addEventListener('click', function () { show(currentIndex - 1); });
        if (nextBtn) nextBtn.addEventListener('click', function () { show(currentIndex + 1); });

        document.addEventListener('keydown', function (e) {
            if (!lightboxEl || !lightboxEl.classList.contains('show')) return;
            if (e.key === 'ArrowLeft') show(currentIndex - 1);
            if (e.key === 'ArrowRight') show(currentIndex + 1);
        });
    })();

    /* ==================================================================
     * 2. INTERACTIVE CANVAS FLOOR PLAN — zoom (wheel) + pan (drag) + fullscreen
     * ================================================================== */
    (function initFloorPlanCanvas() {
        var canvas = document.getElementById('floorPlanCanvas');
        if (!canvas) return;

        var ctx = canvas.getContext('2d');
        var wrapper = document.getElementById('floorPlanWrapper');
        var img = new Image();
        img.src = canvas.getAttribute('data-floor-plan-src');

        var scale = 1;
        var offsetX = 0;
        var offsetY = 0;
        var isDragging = false;
        var lastX = 0;
        var lastY = 0;
        var minScale = 0.5;
        var maxScale = 4;

        function resizeCanvas() {
            canvas.width = wrapper.clientWidth;
            canvas.height = wrapper.clientHeight;
            draw();
        }

        function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.save();
            ctx.translate(offsetX, offsetY);
            ctx.scale(scale, scale);

            if (img.complete && img.naturalWidth) {
                // Fit image within canvas while preserving aspect ratio, centered
                var fitScale = Math.min(canvas.width / img.naturalWidth, canvas.height / img.naturalHeight);
                var drawWidth = img.naturalWidth * fitScale;
                var drawHeight = img.naturalHeight * fitScale;
                var drawX = (canvas.width / scale - drawWidth) / 2;
                var drawY = (canvas.height / scale - drawHeight) / 2;
                ctx.drawImage(img, drawX, drawY, drawWidth, drawHeight);
            }
            ctx.restore();
        }

        img.onload = function () { resizeCanvas(); };
        window.addEventListener('resize', resizeCanvas);

        // Zoom via mouse wheel
        canvas.addEventListener('wheel', function (e) {
            e.preventDefault();
            var delta = e.deltaY < 0 ? 0.1 : -0.1;
            var newScale = Math.min(maxScale, Math.max(minScale, scale + delta));
            scale = newScale;
            draw();
        }, { passive: false });

        // Pan via drag
        canvas.addEventListener('mousedown', function (e) {
            isDragging = true;
            lastX = e.clientX;
            lastY = e.clientY;
            canvas.style.cursor = 'grabbing';
        });
        window.addEventListener('mousemove', function (e) {
            if (!isDragging) return;
            offsetX += e.clientX - lastX;
            offsetY += e.clientY - lastY;
            lastX = e.clientX;
            lastY = e.clientY;
            draw();
        });
        window.addEventListener('mouseup', function () {
            isDragging = false;
            canvas.style.cursor = 'grab';
        });

        // Touch support (mobile)
        var lastTouchDist = null;
        canvas.addEventListener('touchstart', function (e) {
            if (e.touches.length === 1) {
                isDragging = true;
                lastX = e.touches[0].clientX;
                lastY = e.touches[0].clientY;
            }
        });
        canvas.addEventListener('touchmove', function (e) {
            e.preventDefault();
            if (e.touches.length === 1 && isDragging) {
                offsetX += e.touches[0].clientX - lastX;
                offsetY += e.touches[0].clientY - lastY;
                lastX = e.touches[0].clientX;
                lastY = e.touches[0].clientY;
                draw();
            } else if (e.touches.length === 2) {
                var dist = Math.hypot(
                    e.touches[0].clientX - e.touches[1].clientX,
                    e.touches[0].clientY - e.touches[1].clientY
                );
                if (lastTouchDist) {
                    var deltaScale = (dist - lastTouchDist) * 0.005;
                    scale = Math.min(maxScale, Math.max(minScale, scale + deltaScale));
                    draw();
                }
                lastTouchDist = dist;
            }
        }, { passive: false });
        canvas.addEventListener('touchend', function () {
            isDragging = false;
            lastTouchDist = null;
        });

        // Controls
        var zoomInBtn = document.getElementById('floorPlanZoomIn');
        var zoomOutBtn = document.getElementById('floorPlanZoomOut');
        var resetBtn = document.getElementById('floorPlanReset');
        var fullscreenBtn = document.getElementById('floorPlanFullscreen');

        if (zoomInBtn) zoomInBtn.addEventListener('click', function () {
            scale = Math.min(maxScale, scale + 0.2);
            draw();
        });
        if (zoomOutBtn) zoomOutBtn.addEventListener('click', function () {
            scale = Math.max(minScale, scale - 0.2);
            draw();
        });
        if (resetBtn) resetBtn.addEventListener('click', function () {
            scale = 1; offsetX = 0; offsetY = 0;
            draw();
        });
        if (fullscreenBtn) fullscreenBtn.addEventListener('click', function () {
            if (!document.fullscreenElement) {
                wrapper.requestFullscreen().then(resizeCanvas).catch(function () {});
            } else {
                document.exitFullscreen().then(resizeCanvas);
            }
        });
    })();

    /* ==================================================================
     * 3. ENQUIRY FORM — AJAX submit
     * Endpoint wired in Phase 14/18: POST /enquiries
     * ================================================================== */
    (function initEnquiryForm() {
        var form = document.getElementById('enquiryForm');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            var submitBtn = document.getElementById('enquirySubmitBtn');
            var originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Sending...';

            var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            var payload = {
                property_id: form.getAttribute('data-property-id'),
                name: form.querySelector('[name="name"]').value,
                email: form.querySelector('[name="email"]').value,
                phone: form.querySelector('[name="phone"]').value,
                message: form.querySelector('[name="message"]').value,
            };

            fetch('/enquiries', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
                .then(function (res) {
                    if (!res.ok) throw new Error('Request failed');
                    return res.json();
                })
                .then(function () {
                    repToast('Your enquiry has been sent to the agent!', 'success');
                    form.reset();
                })
                .catch(function () {
                    repToast('Could not send your enquiry. Please try again.', 'danger');
                })
                .finally(function () {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
        });
    })();
})();
