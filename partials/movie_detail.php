<?php
// partials/movie_detail.php - Full Movie Detail Component
// Expects: $movie (array)
if (!isset($movie) || empty($movie)) return;

$defaultPlaceholder = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='400' height='600' viewBox='0 0 400 600'><rect width='100%' height='100%' fill='%23121824'/><text x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' fill='%2364748b' font-family='sans-serif' font-size='22'>" . rawurlencode($movie['title']) . "</text></svg>";
?>
<section class="detail-hero">
    <div class="detail-backdrop">
        <img src="<?= htmlspecialchars($movie['backdrop']) ?>" 
             alt="<?= htmlspecialchars($movie['title']) ?> Backdrop"
             onerror="this.onerror=null; this.src='<?= htmlspecialchars($movie['poster']) ?>';">
        <div class="detail-backdrop-overlay"></div>
    </div>

    <div class="detail-content-wrapper">
        <div class="detail-poster-box">
            <img src="<?= htmlspecialchars($movie['poster']) ?>" 
                 alt="<?= htmlspecialchars($movie['title']) ?> Poster"
                 onerror="this.onerror=null; this.src='<?= $defaultPlaceholder ?>';">
        </div>

        <div class="detail-info">
            <div class="detail-tags">
                <span class="rating-pill" style="position:static;">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="#fbbf24" stroke="#fbbf24">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                    <strong><?= number_format((float)$movie['rating'], 1) ?> / 10</strong>
                </span>
                <span class="detail-tag"><?= htmlspecialchars($movie['year']) ?></span>
                <span class="detail-tag"><?= htmlspecialchars($movie['duration']) ?></span>
                <span class="detail-tag"><?= htmlspecialchars($movie['genre']) ?></span>
                <span class="not-playable-badge" style="position:static;">
                    <span class="dot"></span> Not Playable Yet
                </span>
            </div>

            <h1><?= htmlspecialchars($movie['title']) ?></h1>

            <div class="detail-actions">
                <button class="btn btn-primary btn-play-trigger"
                        data-id="<?= htmlspecialchars($movie['id']) ?>"
                        data-title="<?= htmlspecialchars($movie['title']) ?>"
                        data-backdrop="<?= htmlspecialchars($movie['backdrop']) ?>"
                        data-poster="<?= htmlspecialchars($movie['poster']) ?>">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                    </svg>
                    Watch (Not Playable Yet)
                </button>
            </div>

            <div class="detail-synopsis">
                <h3>Description &amp; Synopsis</h3>
                <p style="color: var(--text-muted); line-height: 1.7; font-size: 1rem;">
                    <?= nl2br(htmlspecialchars($movie['description'])) ?>
                </p>

                <div class="detail-meta-grid">
                    <div>
                        <div class="meta-field-label">Director</div>
                        <div class="meta-field-val"><?= htmlspecialchars($movie['director']) ?></div>
                    </div>
                    <div>
                        <div class="meta-field-label">Cast Members</div>
                        <div class="meta-field-val"><?= htmlspecialchars($movie['actors']) ?></div>
                    </div>
                    <div>
                        <div class="meta-field-label">Release Year</div>
                        <div class="meta-field-val"><?= htmlspecialchars($movie['year']) ?></div>
                    </div>
                    <div>
                        <div class="meta-field-label">Streaming Availability</div>
                        <div class="meta-field-val" style="color: #ef4444; font-weight: 600;">Coming Soon (Not playable yet)</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

