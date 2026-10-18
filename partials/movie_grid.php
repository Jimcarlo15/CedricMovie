<?php
// partials/movie_grid.php - Grid of Movie Cards or Empty State
// Expects: $movies (array)
require_once __DIR__ . '/../helpers/partial.php';
?>
<div id="moviesGridContainer">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 1.25rem;">
        <div>
            <h2 style="font-size: 1.6rem; font-weight: 800; color: #fff;">Featured Catalog</h2>
            <p id="movieCountText" style="color: var(--text-dim); font-size: 0.9rem;">
                Showing <?= count($movies) ?> movies in catalog
            </p>
        </div>
    </div>

    <?php if (empty($movies)): ?>
        <?php render_partial('empty_state'); ?>
    <?php else: ?>
        <div class="movies-grid" id="moviesGrid">
            <?php foreach ($movies as $movie): ?>
                <?php render_partial('movie_card', ['movie' => $movie]); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

