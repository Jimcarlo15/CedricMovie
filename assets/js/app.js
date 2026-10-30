// assets/js/app.js - CedCine Public Application JS

document.addEventListener('DOMContentLoaded', () => {
    // ── Elements ──────────────────────────────────────────────────────────────
    const playerModalEl    = document.getElementById('playerModal');
    const playerMovieTitle = document.getElementById('playerMovieTitle');
    const playerBackdrop   = document.getElementById('playerBackdrop');
    const playerDetailLink = document.getElementById('playerDetailLink');
    const searchInput      = document.getElementById('navbarSearch');
    const clearSearchBtn   = document.getElementById('clearSearchBtn');
    const sortSelect       = document.getElementById('sortSelect');
    const moviesContainer  = document.getElementById('moviesContainer');

    let bsPlayerModal = null;
    if (playerModalEl && window.bootstrap) {
        bsPlayerModal = new bootstrap.Modal(playerModalEl);
    }

    // ── Update favorites badge in navbar ──────────────────────────────────────
    function updateFavBadge(count) {
        let badge = document.querySelector('.fav-badge-count');
        const favLink = document.querySelector('a[href*="favorites.php"]');
        if (count > 0) {
            if (!badge && favLink) {
                badge = document.createElement('span');
                badge.className = 'badge rounded-pill bg-danger ms-1 fav-badge-count';
                favLink.appendChild(badge);
            }
            if (badge) badge.textContent = count;
        } else if (badge) {
            badge.remove();
        }
    }

    // ── Favorite toggle ───────────────────────────────────────────────────────
    function bindFavoriteTriggers() {
        document.querySelectorAll('.btn-fav-toggle').forEach(function(btn) {
            // Prevent double-binding
            if (btn.__favBound) return;
            btn.__favBound = true;

            btn.addEventListener('click', async function(e) {
                e.preventDefault();
                e.stopPropagation();

                const isLoggedIn = btn.getAttribute('data-logged-in') === '1';
                if (!isLoggedIn) {
                    if (confirm('Please log in to add this movie to your favorites. Go to the login page?')) {
                        window.location.href = 'login.php';
                    }
                    return;
                }

                const movieId = parseInt(btn.getAttribute('data-movie-id'), 10);
                if (!movieId) return;

                btn.disabled = true;

                try {
                    const response = await fetch('api/favorite.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({ movie_id: movieId })
                    });

                    if (!response.ok) {
                        throw new Error('Server returned ' + response.status);
                    }

                    const data = await response.json();

                    if (data.success) {
                        const isFav = data.status === 'added';
                        btn.classList.toggle('active', isFav);

                        const icon = btn.querySelector('i');
                        if (icon) {
                            icon.className = isFav
                                ? 'bi bi-heart-fill text-danger'
                                : 'bi bi-heart';
                        }
                        btn.title = isFav ? 'Remove from Favorites' : 'Add to Favorites';

                        // Detail page has a text label inside the button
                        const btnText = btn.querySelector('.fav-btn-text');
                        if (btnText) {
                            btnText.textContent = isFav ? 'Favorited' : 'Add to Favorites';
                            btn.className = 'btn ' + (isFav ? 'btn-danger' : 'btn-outline-danger')
                                + ' btn-lg d-inline-flex align-items-center gap-2 btn-fav-toggle';
                        }

                        updateFavBadge(data.total_favorites);
                    } else {
                        alert(data.message || 'Unable to update favorite status.');
                    }
                } catch (err) {
                    console.error('Favorite toggle error:', err);
                    alert('Could not update favorites. Please try again.');
                } finally {
                    btn.disabled = false;
                }
            });
        });
    }

    bindFavoriteTriggers();

    // ── Watch-movie modal ─────────────────────────────────────────────────────
    function bindPlayerTriggers() {
        document.querySelectorAll('.btn-play-trigger').forEach(btn => {
            btn.onclick = (e) => {
                e.preventDefault();
                const id       = btn.getAttribute('data-id');
                const title    = btn.getAttribute('data-title');
                const backdrop = btn.getAttribute('data-backdrop') || btn.getAttribute('data-poster');

                if (playerMovieTitle) playerMovieTitle.textContent = title;
                if (playerBackdrop && backdrop) playerBackdrop.src = backdrop;
                if (playerDetailLink) playerDetailLink.href = `movie.php?id=${id}`;

                if (bsPlayerModal) {
                    bsPlayerModal.show();
                } else if (playerModalEl) {
                    playerModalEl.classList.add('show');
                    playerModalEl.style.display = 'block';
                }
            };
        });
    }
    bindPlayerTriggers();

    // ── AJAX partial movie-grid ───────────────────────────────────────────────
    let currentAbortCtrl = null;
    function fetchMovieGridPartial(genre, sort, query, pushToHistory = true) {
        if (!moviesContainer) return;
        if (currentAbortCtrl) currentAbortCtrl.abort();
        currentAbortCtrl = new AbortController();

        const urlParams = new URLSearchParams(window.location.search);
        if (genre !== undefined) {
            if (genre === 'all') urlParams.delete('genre'); else urlParams.set('genre', genre);
        }
        if (sort  !== undefined) urlParams.set('sort', sort);
        if (query !== undefined) {
            if (query.trim() === '') urlParams.delete('q'); else urlParams.set('q', query.trim());
        }

        if (pushToHistory) {
            window.history.pushState({}, '', `${window.location.pathname}?${urlParams}`);
        }

        moviesContainer.style.opacity    = '0.4';
        moviesContainer.style.transition = 'opacity 0.2s ease';

        fetch(`index.php?partial=movies&${urlParams}`, {
            credentials: 'same-origin',
            signal: currentAbortCtrl.signal
        })
            .then(res => res.text())
            .then(html => {
                moviesContainer.innerHTML    = html;
                moviesContainer.style.opacity = '1';
                bindPlayerTriggers();
                bindFavoriteTriggers();
                bindResetFilterTrigger();
            })
            .catch(err => {
                if (err.name !== 'AbortError') {
                    console.error('Partial load error:', err);
                    moviesContainer.style.opacity = '1';
                }
            });
    }

    // ── Genre pills ───────────────────────────────────────────────────────────
    function bindGenrePills() {
        document.querySelectorAll('.genre-pill').forEach(pill => {
            pill.onclick = (e) => {
                e.preventDefault();
                document.querySelectorAll('.genre-pill').forEach(p => p.classList.remove('active'));
                pill.classList.add('active');
                fetchMovieGridPartial(
                    pill.getAttribute('data-genre') || 'all',
                    sortSelect  ? sortSelect.value  : 'rating_desc',
                    searchInput ? searchInput.value : ''
                );
            };
        });
    }
    bindGenrePills();

    // Sort dropdown
    if (sortSelect) {
        sortSelect.addEventListener('change', () => {
            const active = document.querySelector('.genre-pill.active');
            fetchMovieGridPartial(
                active ? active.getAttribute('data-genre') || 'all' : 'all',
                sortSelect.value,
                searchInput ? searchInput.value : ''
            );
        });
    }

    // ── Live search ───────────────────────────────────────────────────────────
    let debounceTimer = null;
    if (searchInput) {
        function toggleClearBtn() {
            if (clearSearchBtn) {
                clearSearchBtn.classList.toggle('d-none', searchInput.value.trim().length === 0);
            }
        }
        toggleClearBtn();

        searchInput.addEventListener('input', (e) => {
            toggleClearBtn();
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                const active = document.querySelector('.genre-pill.active');
                fetchMovieGridPartial(
                    active ? active.getAttribute('data-genre') || 'all' : 'all',
                    sortSelect ? sortSelect.value : 'rating_desc',
                    e.target.value
                );
            }, 260);
        });

        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', () => {
                searchInput.value = '';
                toggleClearBtn();
                const active = document.querySelector('.genre-pill.active');
                fetchMovieGridPartial(
                    active ? active.getAttribute('data-genre') || 'all' : 'all',
                    sortSelect ? sortSelect.value : 'rating_desc',
                    ''
                );
            });
        }
    }

    // ── Reset filters ─────────────────────────────────────────────────────────
    function bindResetFilterTrigger() {
        document.querySelectorAll('.btn-reset-filters').forEach(btn => {
            btn.onclick = (e) => {
                e.preventDefault();
                if (searchInput) {
                    searchInput.value = '';
                    if (clearSearchBtn) clearSearchBtn.classList.add('d-none');
                }
                if (sortSelect) sortSelect.value = 'rating_desc';
                document.querySelectorAll('.genre-pill').forEach(p => {
                    p.classList.toggle('active', p.getAttribute('data-genre') === 'all');
                });
                fetchMovieGridPartial('all', 'rating_desc', '');
            };
        });
    }
    bindResetFilterTrigger();

    // ── Browser back / forward ────────────────────────────────────────────────
    window.addEventListener('popstate', () => {
        const p     = new URLSearchParams(window.location.search);
        const genre = p.get('genre') || 'all';
        const sort  = p.get('sort')  || 'rating_desc';
        const query = p.get('q')     || '';

        if (searchInput) searchInput.value = query;
        if (sortSelect)  sortSelect.value  = sort;
        document.querySelectorAll('.genre-pill').forEach(pill => {
            pill.classList.toggle('active', (pill.getAttribute('data-genre') || 'all') === genre);
        });
        fetchMovieGridPartial(genre, sort, query, false);
    });
});
