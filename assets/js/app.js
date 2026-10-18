// assets/js/app.js - CedricMovie Interactivity & AJAX Partial Rendering

document.addEventListener('DOMContentLoaded', () => {
    // Elements
    const playerModal = document.getElementById('playerModal');
    const playerModalClose = document.getElementById('playerModalClose');
    const playerMovieTitle = document.getElementById('playerMovieTitle');
    const playerBackdrop = document.getElementById('playerBackdrop');
    const playerDetailLink = document.getElementById('playerDetailLink');

    const addMovieModal = document.getElementById('addMovieModal');
    const openAddMovieBtn = document.getElementById('openAddMovieBtn');
    const closeAddMovieBtn = document.getElementById('closeAddMovieBtn');

    const searchInput = document.getElementById('navbarSearch');
    const sortSelect = document.getElementById('sortSelect');
    const moviesContainer = document.getElementById('moviesContainer');

    // Attach Player Modal Event Listeners
    function bindPlayerTriggers() {
        document.querySelectorAll('.btn-play-trigger').forEach(btn => {
            // Remove existing listener clone to prevent duplicate events
            btn.onclick = (e) => {
                e.preventDefault();
                const id = btn.getAttribute('data-id');
                const title = btn.getAttribute('data-title');
                const backdrop = btn.getAttribute('data-backdrop') || btn.getAttribute('data-poster');

                if (playerMovieTitle) playerMovieTitle.textContent = title;
                if (playerBackdrop && backdrop) playerBackdrop.src = backdrop;
                if (playerDetailLink) playerDetailLink.href = `movie.php?id=${id}`;

                if (playerModal) {
                    playerModal.classList.add('active');
                    document.body.style.overflow = 'hidden';
                }
            };
        });
    }

    bindPlayerTriggers();

    // Close Player Modal
    if (playerModalClose && playerModal) {
        playerModalClose.addEventListener('click', () => {
            playerModal.classList.remove('active');
            document.body.style.overflow = '';
        });
    }

    // Open Add Movie Modal
    if (openAddMovieBtn && addMovieModal) {
        openAddMovieBtn.addEventListener('click', () => {
            addMovieModal.classList.add('active');
            document.body.style.overflow = 'hidden';
        });
    }

    // Close Add Movie Modal
    if (closeAddMovieBtn && addMovieModal) {
        closeAddMovieBtn.addEventListener('click', () => {
            addMovieModal.classList.remove('active');
            document.body.style.overflow = '';
        });
    }

    // Backdrop Click Close
    window.addEventListener('click', (e) => {
        if (e.target === playerModal) {
            playerModal.classList.remove('active');
            document.body.style.overflow = '';
        }
        if (e.target === addMovieModal) {
            addMovieModal.classList.remove('active');
            document.body.style.overflow = '';
        }
    });

    // Escape Key Close
    window.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (playerModal) playerModal.classList.remove('active');
            if (addMovieModal) addMovieModal.classList.remove('active');
            document.body.style.overflow = '';
        }
    });

    // Partial Loader Function
    let currentAbortCtrl = null;
    function fetchMovieGridPartial(genre, sort, query, pushToHistory = true) {
        if (!moviesContainer) return;

        if (currentAbortCtrl) {
            currentAbortCtrl.abort();
        }
        currentAbortCtrl = new AbortController();

        // Build URL params
        const urlParams = new URLSearchParams(window.location.search);
        if (genre !== undefined) {
            if (genre === 'all') urlParams.delete('genre');
            else urlParams.set('genre', genre);
        }
        if (sort !== undefined) {
            urlParams.set('sort', sort);
        }
        if (query !== undefined) {
            if (query.trim() === '') urlParams.delete('q');
            else urlParams.set('q', query.trim());
        }

        const newUrl = `${window.location.pathname}?${urlParams.toString()}`;
        if (pushToHistory) {
            window.history.pushState({}, '', newUrl);
        }

        // Fetch the partial
        const fetchUrl = `index.php?partial=movies&${urlParams.toString()}`;
        moviesContainer.style.opacity = '0.4';
        moviesContainer.style.transition = 'opacity 0.2s ease';

        fetch(fetchUrl, { signal: currentAbortCtrl.signal })
            .then(res => res.text())
            .then(html => {
                moviesContainer.innerHTML = html;
                moviesContainer.style.opacity = '1';
                bindPlayerTriggers();
                bindResetFilterTrigger();
            })
            .catch(err => {
                if (err.name !== 'AbortError') {
                    console.error('Partial load error:', err);
                    moviesContainer.style.opacity = '1';
                }
            });
    }

    // Intercept Genre Pill Clicks for Instant Partial Loading
    document.querySelectorAll('.genre-pill').forEach(pill => {
        pill.addEventListener('click', (e) => {
            e.preventDefault();
            document.querySelectorAll('.genre-pill').forEach(p => p.classList.remove('active'));
            pill.classList.add('active');

            const url = new URL(pill.href, window.location.origin);
            const genre = url.searchParams.get('genre') || 'all';
            const sort = sortSelect ? sortSelect.value : 'rating_desc';
            const query = searchInput ? searchInput.value : '';

            fetchMovieGridPartial(genre, sort, query);
        });
    });

    // Sort Dropdown Change -> Partial Update
    if (sortSelect) {
        sortSelect.addEventListener('change', () => {
            const activeGenrePill = document.querySelector('.genre-pill.active');
            const url = activeGenrePill ? new URL(activeGenrePill.href, window.location.origin) : null;
            const genre = url ? (url.searchParams.get('genre') || 'all') : 'all';
            const query = searchInput ? searchInput.value : '';

            fetchMovieGridPartial(genre, sortSelect.value, query);
        });
    }

    // Live Search with Debounce -> Partial Update
    let debounceTimer = null;
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const activeGenrePill = document.querySelector('.genre-pill.active');
                const url = activeGenrePill ? new URL(activeGenrePill.href, window.location.origin) : null;
                const genre = url ? (url.searchParams.get('genre') || 'all') : 'all';
                const sort = sortSelect ? sortSelect.value : 'rating_desc';

                fetchMovieGridPartial(genre, sort, e.target.value);
            }, 250);
        });
    }

    // Reset Filters Button Handler
    function bindResetFilterTrigger() {
        document.querySelectorAll('.btn-reset-filters').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                if (searchInput) searchInput.value = '';
                if (sortSelect) sortSelect.value = 'rating_desc';
                document.querySelectorAll('.genre-pill').forEach(p => {
                    p.classList.toggle('active', p.textContent.trim().toLowerCase().includes('all'));
                });
                fetchMovieGridPartial('all', 'rating_desc', '');
            });
        });
    }
    bindResetFilterTrigger();

    // Handle Browser Back/Forward navigation
    window.addEventListener('popstate', () => {
        const urlParams = new URLSearchParams(window.location.search);
        const genre = urlParams.get('genre') || 'all';
        const sort = urlParams.get('sort') || 'rating_desc';
        const query = urlParams.get('q') || '';

        if (searchInput) searchInput.value = query;
        if (sortSelect) sortSelect.value = sort;

        document.querySelectorAll('.genre-pill').forEach(p => {
            const pGenre = new URL(p.href, window.location.origin).searchParams.get('genre') || 'all';
            p.classList.toggle('active', pGenre === genre);
        });

        fetchMovieGridPartial(genre, sort, query, false);
    });
});
