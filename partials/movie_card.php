<?php
// partials/movie_card.php
// Expects $movie array with keys: id, title, genre, year, duration, rating, poster, backdrop, description
if (!isset($movie)) return;

$ratingStars = round($movie['rating'] / 2, 1);
$defaultPlaceholder = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='400' height='250' viewBox='0 0 400 250'><rect width='100%' height='100%' fill='%23121824'/><text x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' fill='%2364748b' font-family='sans-serif' font-size='18'>" . rawurlencode($movie['title']) . "</text></svg>";
?>
<article class="movie-card" 
         data-id="<?= htmlspecialchars($movie['id']) ?>" 
         data-title="<?= htmlspecialchars($movie['title']) ?>" 
         data-genre="<?= htmlspecialchars($movie['genre']) ?>" 
         data-desc="<?= htmlspecialchars($movie['description']) ?>">
    <div class="card-poster">
        <img src="<?= htmlspecialchars($movie['poster']) ?>" 
             alt="<?= htmlspecialchars($movie['title']) ?> Poster" 
             loading="lazy"
             onerror="this.onerror=null; this.src='<?= $defaultPlaceholder ?>';">
        
        <!-- Status Badge: Not Playable Yet -->
        <div class="not-playable-badge" title="Streaming is not playable yet">
            <span class="dot"></span> Not Playable Yet
        </div>

        <!-- Rating Pill -->
        <div class="rating-pill">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="#fbbf24" stroke="#fbbf24" stroke-width="2">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
            </svg>
            <span><?= number_format((float)$movie['rating'], 1) ?></span>
        </div>
    </div>

    <div class="card-body">
        <div class="card-meta">
            <span><?= htmlspecialchars($movie['year']) ?></span>
            <span class="meta-dot">•</span>
            <span><?= htmlspecialchars($movie['duration']) ?></span>
            <span class="meta-dot">•</span>
            <span><?= htmlspecialchars($movie['genre']) ?></span>
        </div>

        <h3 class="card-title">
            <a href="movie.php?id=<?= urlencode($movie['id']) ?>">
                <?= htmlspecialchars($movie['title']) ?>
            </a>
        </h3>

        <p class="card-desc">
            <?= htmlspecialchars($movie['description']) ?>
        </p>

        <div class="card-footer">
            <button class="btn btn-secondary btn-sm btn-play-trigger" 
                    data-id="<?= htmlspecialchars($movie['id']) ?>"
                    data-title="<?= htmlspecialchars($movie['title']) ?>"
                    data-backdrop="<?= htmlspecialchars($movie['backdrop']) ?>"
                    data-poster="<?= htmlspecialchars($movie['poster']) ?>"
                    title="Click to try player">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                </svg>
                Watch
            </button>
            <a href="movie.php?id=<?= urlencode($movie['id']) ?>" class="btn btn-primary btn-sm">
                Details &rarr;
            </a>
        </div>
    </div>
</article>

