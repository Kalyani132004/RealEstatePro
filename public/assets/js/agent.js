/**
 * RealEstatePro — Agent dashboard behaviour
 * Folder: public/assets/js/agent.js
 * Loaded on: agent/properties/create.blade.php, edit.blade.php, agent/enquiries.blade.php
 */
(function () {
    'use strict';

    /* ------------------------------------------------------------------
     * 1. Cover image preview
     * ------------------------------------------------------------------ */
    var coverInput = document.getElementById('coverImageInput');
    if (coverInput) {
        coverInput.addEventListener('change', function (e) {
            var file = e.target.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function (ev) {
                document.getElementById('coverImagePreviewWrap').innerHTML =
                    '<img src="' + ev.target.result + '" alt="Cover preview" style="width:140px;height:100px;object-fit:cover;border-radius:var(--rep-radius-sm);">';
            };
            reader.readAsDataURL(file);
        });
    }

    /* ------------------------------------------------------------------
     * 2. Gallery multi-image preview (appends to existing previews)
     * ------------------------------------------------------------------ */
    var galleryInput = document.getElementById('galleryInput');
    if (galleryInput) {
        galleryInput.addEventListener('change', function (e) {
            var files = Array.from(e.target.files || []);
            var wrap = document.getElementById('galleryPreviewWrap');

            files.forEach(function (file) {
                if (!file.type.startsWith('image/')) return;
                var reader = new FileReader();
                reader.onload = function (ev) {
                    var div = document.createElement('div');
                    div.className = 'position-relative';
                    div.innerHTML = '<img src="' + ev.target.result + '" style="width:90px;height:90px;object-fit:cover;border-radius:var(--rep-radius-sm);">' +
                        '<span class="badge bg-dark position-absolute bottom-0 end-0 m-1" style="font-size:0.6rem;">NEW</span>';
                    wrap.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        });
    }

    /* ------------------------------------------------------------------
     * 3. Virtual tour video — handled by video-upload.js (Phase 17), which
     * uploads in chunks with a progress bar rather than a simple preview,
     * since these files can be up to 100MB.
     * ------------------------------------------------------------------ */

    /* ------------------------------------------------------------------
     * 4. Floor plan image preview
     * ------------------------------------------------------------------ */
    var floorPlanInput = document.getElementById('floorPlanInput');
    if (floorPlanInput) {
        floorPlanInput.addEventListener('change', function (e) {
            var file = e.target.files[0];
            if (!file) return;
            var reader = new FileReader();
            reader.onload = function (ev) {
                document.getElementById('floorPlanPreviewWrap').innerHTML =
                    '<img src="' + ev.target.result + '" style="width:140px;height:100px;object-fit:cover;border-radius:var(--rep-radius-sm);">';
            };
            reader.readAsDataURL(file);
        });
    }

    /* ------------------------------------------------------------------
     * 5. AJAX enquiry status update (agent/enquiries.blade.php)
     * Endpoint wired in Phase 14: PATCH /agent/enquiries/{id}/status
     * ------------------------------------------------------------------ */
    document.querySelectorAll('.rep-enquiry-status').forEach(function (select) {
        select.addEventListener('change', function () {
            var enquiryId = this.getAttribute('data-enquiry-id');
            var newStatus = this.value;
            var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/agent/enquiries/' + enquiryId + '/status', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: newStatus })
            })
                .then(function (res) { return res.json(); })
                .then(function () {
                    repToast('Enquiry status updated to "' + newStatus + '"', 'success');
                })
                .catch(function () {
                    repToast('Could not update status. Please try again.', 'danger');
                });
        });
    });
})();
