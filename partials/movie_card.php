<?php
// partials/movie_card.php - Reusable Movie Card Component
// Expects: $movie (array)
// Optional: $userFavoriteIds (array), $isSubdir (bool)
if (!isset($movie)) return;

$basePath = isset($isSubdir) && $isSubdir ? '../' : '';
$isFav = false;
if (isset($userFavoriteIds) && is_array($userFavoriteIds)) {
    $isFav = in_array((int)$movie['id'], $userFavoriteIds);
} elseif (isset($movie['is_favorite'])) {
    $isFav = (bool)$movie['is_favorite'];
}

$defaultPlaceholder = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='400' height='260' viewBox='0 0 400 260'><rect width='100%' height='100%' fill='%23121824'/><text x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' fill='%2364748b' font-family='sans-serif' font-size='18'>" . rawurlencode($movie['title']) . "</text></svg>";
$isLoggedIn = function_exists('is_logged_in') ? is_logged_in() : false;
?>
<div class="col">
    <article class="movie-card h-100 d-flex flex-column" 
             data-id="<?= htmlspecialchars($movie['id']) ?>" 
             data-title="<?= htmlspecialchars($movie['title']) ?>" 
             data-genre="<?= htmlspecialchars($movie['genre']) ?>" 
             data-desc="<?= htmlspecialchars($movie['description']) ?>">
        
        <!-- Poster & Badges -->
        <div class="card-poster position-relative">
            <img src="<?= htmlspecialchars(get_movie_image_url($movie['poster_url'], $isSubdir ?? false)) ?>" 
                 alt="<?= htmlspecialchars($movie['title']) ?> Poster" 
                 loading="lazy"
                 onerror="this.onerror=null; this.src='<?= $defaultPlaceholder ?>';">
            
            <!-- Not Playable Yet Status Badge -->
            <div class="not-playable-badge" title="Streaming is not playable yet">
                <span class="dot"></span> Not Playable Yet
            </div>

            <!-- Rating Pill -->
            <div class="rating-pill">
                <i class="bi bi-star-fill text-warning"></i>
                <span><?= number_format((float)$movie['rating'], 1) ?></span>
            </div>

            <!-- Favorite Toggle Button -->
            <button class="btn btn-fav-toggle position-absolute <?= $isFav ? 'active' : '' ?>"
                    type="button"
                    data-movie-id="<?= htmlspecialchars($movie['id']) ?>"
                    data-logged-in="<?= $isLoggedIn ? '1' : '0' ?>"
                    title="<?= $isFav ? 'Remove from Favorites' : 'Add to Favorites' ?>">
                <i class="bi <?= $isFav ? 'bi-heart-fill text-danger' : 'bi-heart' ?>"></i>
            </button>
        </div>

        <!-- Card Body -->
        <div class="card-body d-flex flex-column p-3">
            <div class="card-meta small mb-2 d-flex align-items-center gap-1 flex-wrap">
                <span class="text-white fw-bold"><?= htmlspecialchars($movie['release_year']) ?></span>
                <span class="meta-dot text-secondary">•</span>
                <span class="text-light-emphasis"><?= htmlspecialchars($movie['duration'] ?? '2h 00m') ?></span>
                <span class="meta-dot text-secondary">•</span>
                <span class="text-info text-truncate fw-semibold" style="max-width: 140px;"><?= htmlspecialchars($movie['genre']) ?></span>
            </div>

            <h3 class="card-title h6 fw-bold mb-2">
                <a href="<?= $basePath ?>movie.php?id=<?= urlencode($movie['id']) ?>" class="text-white text-decoration-none">
                    <?= htmlspecialchars($movie['title']) ?>
                </a>
            </h3>

            <p class="card-desc text-light-emphasis small flex-grow-1 mb-3">
                <?= htmlspecialchars($movie['description']) ?>
            </p>

            <div class="card-footer-actions d-flex gap-2 pt-2 border-top border-secondary-subtle">
                <button class="btn btn-secondary btn-sm flex-fill d-inline-flex align-items-center justify-content-center gap-1 btn-play-trigger" 
                        data-id="<?= htmlspecialchars($movie['id']) ?>"
                        data-title="<?= htmlspecialchars($movie['title']) ?>"
                        data-backdrop="<?= htmlspecialchars($movie['backdrop_url'] ?? $movie['poster_url']) ?>"
                        data-poster="<?= htmlspecialchars($movie['poster_url']) ?>">
                    <i class="bi bi-play-fill fs-6"></i>
                    <span>Watch</span>
                </button>
                <a href="<?= $basePath ?>movie.php?id=<?= urlencode($movie['id']) ?>" class="btn btn-primary btn-sm flex-fill d-inline-flex align-items-center justify-content-center gap-1">
                    <span>Details</span>
                    <i class="bi bi-chevron-right small"></i>
                </a>
            </div>
        </div>
    </article>
</div>
