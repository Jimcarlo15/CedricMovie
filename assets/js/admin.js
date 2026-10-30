// assets/js/admin.js - CedCine Admin Dashboard Interactivity

document.addEventListener('DOMContentLoaded', () => {
    // Poster live preview on Add/Edit Movie forms
    const posterInput = document.getElementById('poster_url');
    const posterPreview = document.getElementById('posterPreviewImg');
    const backdropInput = document.getElementById('backdrop_url');
    const backdropPreview = document.getElementById('backdropPreviewImg');

    if (posterInput && posterPreview) {
        const updatePoster = () => {
            const url = posterInput.value.trim();
            if (url) {
                posterPreview.src = url;
                posterPreview.classList.remove('d-none');
            }
        };
        posterInput.addEventListener('input', updatePoster);
        posterInput.addEventListener('change', updatePoster);
    }

    if (backdropInput && backdropPreview) {
        const updateBackdrop = () => {
            const url = backdropInput.value.trim();
            if (url) {
                backdropPreview.src = url;
                backdropPreview.classList.remove('d-none');
            }
        };
        backdropInput.addEventListener('input', updateBackdrop);
        backdropInput.addEventListener('change', updateBackdrop);
    }

    // Instant table search filter
    const adminTableSearch = document.getElementById('adminTableSearch');
    if (adminTableSearch) {
        adminTableSearch.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.admin-data-table tbody tr');

            let matchCount = 0;
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(query)) {
                    row.style.display = '';
                    matchCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            const countIndicator = document.getElementById('adminSearchCount');
            if (countIndicator) {
                countIndicator.textContent = `Showing ${matchCount} records`;
            }
        });
    }

    // Confirmation for destructive delete buttons
    document.querySelectorAll('.btn-confirm-delete').forEach(btn => {
        btn.addEventListener('click', (e) => {
            const itemName = btn.getAttribute('data-name') || 'this item';
            if (!confirm(`Are you sure you want to permanently delete "${itemName}"? This action cannot be undone.`)) {
                e.preventDefault();
            }
        });
    });
});

