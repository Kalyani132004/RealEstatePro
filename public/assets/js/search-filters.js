/**
 * RealEstatePro — Property Search & Filters
 * Folder: public/assets/js/search-filters.js
 * Loaded on: properties/search.blade.php
 *
 * Progressive enhancement: the filter form is a plain GET form and works with
 * zero JavaScript (full page reload, PropertyController@search renders the
 * full properties.search view). When JS is available, we intercept the
 * submit and fetch the same URL with an X-Requested-With header — the
 * controller (Phase 15) detects that and returns only the lightweight
 * properties.partials._results view, which we swap directly into
 * #searchResults. Much cheaper than fetching/parsing the full HTML document.
 */
(function () {
    'use strict';

    var form = document.getElementById('filterForm');
    if (!form) return;

    var resultsContainer = document.getElementById('searchResults');

    function setLoading(isLoading) {
        if (!resultsContainer) return;
        resultsContainer.style.opacity = isLoading ? '0.4' : '1';
        resultsContainer.style.pointerEvents = isLoading ? 'none' : 'auto';
    }

    function fetchAndSwap(url, pushHistory) {
        setLoading(true);

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(function (res) {
                if (!res.ok) throw new Error('Request failed');
                return res.text();
            })
            .then(function (html) {
                resultsContainer.innerHTML = html;

                if (pushHistory) {
                    window.history.pushState({}, '', url);
                }
                resultsContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
            })
            .catch(function () {
                // Fall back gracefully to a full page navigation if the fetch fails
                window.location.href = url;
            })
            .finally(function () {
                setLoading(false);
            });
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        var params = new URLSearchParams(new FormData(form)).toString();
        var url = form.action + (params ? '?' + params : '');
        fetchAndSwap(url, true);
    });

    // Pagination links render inside the swapped-in partial, so delegate clicks
    document.addEventListener('click', function (e) {
        var link = e.target.closest('#resultsPagination a');
        if (!link) return;
        e.preventDefault();
        fetchAndSwap(link.href, true);
    });

    // Support browser back/forward through the pushState history above
    window.addEventListener('popstate', function () {
        fetchAndSwap(window.location.href, false);
    });
})();
