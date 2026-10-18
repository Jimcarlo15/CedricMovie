<?php
// partials/hero.php - Featured Movie Hero Banner
// Expects: $featuredMovie (array|null)
?>
<section class="hero">
    <div class="hero-content">
        <span class="hero-tag">🔥 Featured Catalog Item</span>
        <h1><?= htmlspecialchars($featuredMovie['title'] ?? 'Explore Cinema') ?></h1>
        <p><?= htmlspecialchars($featuredMovie['description'] ?? 'Browse ratings, directors, cast lists, and detailed plot overviews.') ?></p>
        
        <div style="display:flex; flex-wrap:wrap; align-items:center; gap:1rem; margin-bottom:1.5rem;">
            <?php if (!empty($featuredMovie)): ?>
                <button class="btn btn-primary btn-play-trigger"
                        data-id="<?= htmlspecialchars($featuredMovie['id']) ?>"
                        data-title="<?= htmlspecialchars($featuredMovie['title']) ?>"
                        data-backdrop="<?= htmlspecialchars($featuredMovie['backdrop']) ?>"
                        data-poster="<?= htmlspecialchars($featuredMovie['poster']) ?>">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                    </svg>
                    Watch Preview
                </button>
                <a href="movie.php?id=<?= urlencode($featuredMovie['id']) ?>" class="btn btn-secondary">
                    Read Full Synopsis
                </a>
            <?php endif; ?>

            <div class="notice-banner">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                Streaming playback is not playable yet (Catalog &amp; Ratings only).
            </div>
        </div>
    </div>
</section>

