<?php
// partials/movie_grid.php - Grid of Movie Cards or Empty State
// Expects: $movies (array)
// Optional: $userFavoriteIds (array)
require_once __DIR__ . '/../helpers/partial.php';
$movieCount = count($movies ?? []);
?>
<div id="moviesGridContainer">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h2 class="h4 fw-bold text-white mb-0">Movie Catalog</h2>
            <p id="movieCountText" class="text-light-emphasis small mb-0 fw-medium">
                Showing <strong class="text-white"><?= $movieCount ?></strong> <?= $movieCount === 1 ? 'movie' : 'movies' ?>
            </p>
        </div>
    </div>

    <?php if (empty($movies)): ?>
        <?php render_partial('empty_state', ['message' => 'No movies match your current search or genre filter. Try another keyword or reset the filter.']); ?>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-4" id="moviesGrid">
            <?php foreach ($movies as $movie): ?>
                <?php render_partial('movie_card', [
                    'movie'           => $movie,
                    'userFavoriteIds' => $userFavoriteIds ?? []
                ]); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
