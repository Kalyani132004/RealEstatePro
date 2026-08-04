/**
 * RealEstatePro — Chunked Virtual Tour Video Upload
 * Folder: public/assets/js/video-upload.js
 * Loaded on: agent/properties/create.blade.php, edit.blade.php
 *
 * Large video files (up to 100MB) are unreliable to send in a single
 * multipart POST alongside the rest of the property form — PHP's default
 * upload_max_filesize/post_max_size are often much smaller, and a single
 * huge request has no progress feedback and no resume-on-failure.
 *
 * Instead: the moment the agent picks a video file, we upload it in ~5MB
 * chunks directly to /agent/properties/video-chunk (Phase 17 backend),
 * show a progress bar, and on completion swap a hidden
 * "virtual_tour_video_path" field into the real form — THAT field, not
 * the raw file input, is what actually gets submitted with the rest of
 * the property data.
 */
(function () {
    'use strict';

    var CHUNK_SIZE = 5 * 1024 * 1024; // 5MB

    var videoInput = document.getElementById('videoInput');
    if (!videoInput) return;

    var form = videoInput.closest('form');
    var previewWrap = document.getElementById('videoPreviewWrap');
    var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var uploadUrl = videoInput.getAttribute('data-chunk-upload-url');

    // Hidden field that carries the finished upload's storage path into the
    // property form — created once, reused on every upload attempt.
    var hiddenPathInput = document.createElement('input');
    hiddenPathInput.type = 'hidden';
    hiddenPathInput.name = 'virtual_tour_video_path';
    form.appendChild(hiddenPathInput);

    function renderProgressUI(percent, statusText) {
        previewWrap.innerHTML =
            '<div class="mb-2">' +
                '<div class="progress" style="height:8px; border-radius:999px;">' +
                    '<div class="progress-bar" role="progressbar" style="width:' + percent + '%; background: var(--rep-secondary);"></div>' +
                '</div>' +
                '<p class="rep-small mt-1 mb-0">' + statusText + '</p>' +
            '</div>';
    }

    function renderError(message) {
        previewWrap.innerHTML = '<p class="rep-small mb-0" style="color: var(--rep-danger);"><i class="bi bi-exclamation-triangle"></i> ' + message + '</p>';
    }

    function renderSuccess(path) {
        var url = window.location.origin + '/storage/' + path;
        previewWrap.innerHTML =
            '<video src="' + url + '" style="width:100%;max-width:260px;border-radius:var(--rep-radius-sm);" controls></video>' +
            '<p class="rep-small mt-1 mb-0" style="color: var(--rep-success);"><i class="bi bi-check-circle"></i> Video uploaded successfully</p>';
    }

    function uploadChunk(file, uploadId, chunkIndex, totalChunks) {
        var start = chunkIndex * CHUNK_SIZE;
        var end = Math.min(start + CHUNK_SIZE, file.size);
        var chunk = file.slice(start, end);

        var formData = new FormData();
        formData.append('chunk', chunk, file.name);
        formData.append('upload_id', uploadId);
        formData.append('chunk_index', chunkIndex);
        formData.append('total_chunks', totalChunks);

        return fetch(uploadUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' },
            body: formData,
        }).then(function (res) {
            if (!res.ok) return res.json().then(function (data) { throw new Error(data.message || 'Upload failed'); });
            return res.json();
        });
    }

    function uploadFile(file) {
        var uploadId = 'vt-' + Date.now() + '-' + Math.random().toString(36).slice(2, 8);
        var totalChunks = Math.ceil(file.size / CHUNK_SIZE);
        var chunkIndex = 0;

        renderProgressUI(0, 'Starting upload...');

        function next() {
            uploadChunk(file, uploadId, chunkIndex, totalChunks)
                .then(function (result) {
                    var percent = Math.round(((chunkIndex + 1) / totalChunks) * 100);

                    if (result.complete) {
                        hiddenPathInput.value = result.path;
                        renderSuccess(result.path);
                        repToast('Virtual tour video uploaded.', 'success');
                        return;
                    }

                    renderProgressUI(percent, 'Uploading video... ' + percent + '%');
                    chunkIndex++;
                    next();
                })
                .catch(function (err) {
                    renderError(err.message || 'Video upload failed. Please try again.');
                    repToast('Video upload failed.', 'danger');
                });
        }

        next();
    }

    videoInput.addEventListener('change', function (e) {
        var file = e.target.files[0];
        if (!file) return;

        if (file.size > 100 * 1024 * 1024) {
            renderError('Video must be under 100MB.');
            videoInput.value = '';
            return;
        }

        // The file input itself is intentionally left out of the form submit —
        // its 'name' attribute was never set to a server-processed field, so
        // only the hidden virtual_tour_video_path carries data to the backend.
        uploadFile(file);
    });
})();
