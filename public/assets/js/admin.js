/**
 * RealEstatePro — Admin dashboard behaviour
 * Folder: public/assets/js/admin.js
 * Loaded on: admin/dashboard.blade.php, admin/categories/index.blade.php,
 *            admin/locations/index.blade.php, admin/reports/index.blade.php
 */
(function () {
    'use strict';

    /* ------------------------------------------------------------------
     * 1. Category modal — switch between Add / Edit mode
     * ------------------------------------------------------------------ */
    var categoryModal = document.getElementById('categoryModal');
    if (categoryModal) {
        categoryModal.addEventListener('show.bs.modal', function (e) {
            var trigger = e.relatedTarget;
            var mode = trigger.getAttribute('data-mode');
            var form = document.getElementById('categoryForm');
            var methodInput = document.getElementById('categoryMethod');
            var title = document.getElementById('categoryModalTitle');

            if (mode === 'edit') {
                title.textContent = 'Edit Category';
                form.action = trigger.getAttribute('data-action');
                methodInput.value = 'PUT';
                document.getElementById('categoryName').value = trigger.getAttribute('data-name') || '';
                document.getElementById('categoryIcon').value = trigger.getAttribute('data-icon') || '';
                document.getElementById('categoryDescription').value = trigger.getAttribute('data-description') || '';
            } else {
                title.textContent = 'Add Category';
                form.action = form.getAttribute('data-store-url') || form.action;
                methodInput.value = 'POST';
                form.reset();
            }
        });
    }

    /* ------------------------------------------------------------------
     * 2. Location modal — switch between Add / Edit mode
     * ------------------------------------------------------------------ */
    var locationModal = document.getElementById('locationModal');
    if (locationModal) {
        var locationStoreUrl = document.getElementById('locationForm').action;

        locationModal.addEventListener('show.bs.modal', function (e) {
            var trigger = e.relatedTarget;
            var mode = trigger.getAttribute('data-mode');
            var form = document.getElementById('locationForm');
            var methodInput = document.getElementById('locationMethod');
            var title = document.getElementById('locationModalTitle');

            if (mode === 'edit') {
                title.textContent = 'Edit Location';
                form.action = trigger.getAttribute('data-action');
                methodInput.value = 'PUT';
                document.getElementById('locationCity').value = trigger.getAttribute('data-city') || '';
                document.getElementById('locationState').value = trigger.getAttribute('data-state') || '';
                document.getElementById('locationCountry').value = trigger.getAttribute('data-country') || '';
                document.getElementById('locationZip').value = trigger.getAttribute('data-zip') || '';
            } else {
                title.textContent = 'Add Location';
                form.action = locationStoreUrl;
                methodInput.value = 'POST';
                form.reset();
                document.getElementById('locationCountry').value = 'India';
            }
        });
    }

    /* ------------------------------------------------------------------
     * 3. Chart.js — Dashboard overview charts
     * ------------------------------------------------------------------ */
    var chartColors = {
        primary: getComputedStyle(document.documentElement).getPropertyValue('--rep-primary').trim() || '#1E3A5F',
        secondary: getComputedStyle(document.documentElement).getPropertyValue('--rep-secondary').trim() || '#0EA5A0',
        accent: getComputedStyle(document.documentElement).getPropertyValue('--rep-accent').trim() || '#D4A853',
        text: getComputedStyle(document.documentElement).getPropertyValue('--rep-text-muted').trim() || '#64748B',
    };

    var categoryCanvas = document.getElementById('categoryChart');
    if (categoryCanvas && window.repChartData) {
        new Chart(categoryCanvas, {
            type: 'doughnut',
            data: {
                labels: window.repChartData.categoryLabels,
                datasets: [{
                    data: window.repChartData.categoryData,
                    backgroundColor: [chartColors.primary, chartColors.secondary, chartColors.accent, '#8DBFEA', '#2DD4CF', '#E8C77E'],
                    borderWidth: 0,
                }],
            },
            options: {
                plugins: { legend: { position: 'bottom', labels: { color: chartColors.text, boxWidth: 12 } } },
            },
        });
    }

    var enquiriesCanvas = document.getElementById('enquiriesChart');
    if (enquiriesCanvas && window.repChartData) {
        new Chart(enquiriesCanvas, {
            type: 'line',
            data: {
                labels: window.repChartData.enquiryLabels,
                datasets: [{
                    label: 'Enquiries',
                    data: window.repChartData.enquiryData,
                    borderColor: chartColors.secondary,
                    backgroundColor: 'rgba(14,165,160,0.12)',
                    tension: 0.4,
                    fill: true,
                }],
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: chartColors.text }, grid: { display: false } },
                    y: { ticks: { color: chartColors.text }, beginAtZero: true },
                },
            },
        });
    }

    /* ------------------------------------------------------------------
     * 4. Chart.js — Reports page charts
     * ------------------------------------------------------------------ */
    var monthlyCanvas = document.getElementById('monthlyListingsChart');
    if (monthlyCanvas && window.repReportData) {
        new Chart(monthlyCanvas, {
            type: 'bar',
            data: {
                labels: window.repReportData.monthlyLabels,
                datasets: [{
                    label: 'Listings',
                    data: window.repReportData.monthlyData,
                    backgroundColor: chartColors.primary,
                    borderRadius: 6,
                }],
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    x: { ticks: { color: chartColors.text }, grid: { display: false } },
                    y: { ticks: { color: chartColors.text }, beginAtZero: true },
                },
            },
        });
    }

    var typeCanvas = document.getElementById('listingsByTypeChart');
    if (typeCanvas && window.repReportData) {
        new Chart(typeCanvas, {
            type: 'pie',
            data: {
                labels: window.repReportData.typeLabels,
                datasets: [{
                    data: window.repReportData.typeData,
                    backgroundColor: [chartColors.primary, chartColors.accent],
                    borderWidth: 0,
                }],
            },
            options: {
                plugins: { legend: { position: 'bottom', labels: { color: chartColors.text, boxWidth: 12 } } },
            },
        });
    }
})();
