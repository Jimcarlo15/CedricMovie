<?php
// partials/movie_detail.php - Full Movie Detail Component
// Expects: $movie (array)
// Optional: $isFavorite (bool)
if (!isset($movie) || empty($movie)) return;

$basePath = isset($isSubdir) && $isSubdir ? '../' : '';
$isFav = $isFavorite ?? false;
$isLoggedIn = function_exists('is_logged_in') ? is_logged_in() : false;
$defaultPlaceholder = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='400' height='600' viewBox='0 0 400 600'><rect width='100%' height='100%' fill='%23121824'/><text x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' fill='%2364748b' font-family='sans-serif' font-size='22'>" . rawurlencode($movie['title']) . "</text></svg>";
?>
<section class="detail-hero rounded-4 overflow-hidden mb-5 border border-secondary-subtle">
    <!-- Backdrop Banner -->
    <div class="detail-backdrop position-relative">
        <img src="<?= htmlspecialchars($movie['backdrop_url'] ?? $movie['poster_url']) ?>" 
             alt="<?= htmlspecialchars($movie['title']) ?> Backdrop"
             class="w-100 h-100 object-fit-cover"
             onerror="this.onerror=null; this.src='<?= htmlspecialchars($movie['poster_url']) ?>';">
        <div class="detail-backdrop-overlay"></div>
    </div>

    <!-- Content Card -->
    <div class="detail-content-wrapper px-4 pb-4">
        <div class="row g-4 align-items-end">
            <!-- Poster Column -->
            <div class="col-md-4 col-lg-3 text-center text-md-start">
                <div class="detail-poster-box mx-auto mx-md-0 rounded-4 overflow-hidden shadow-lg border border-secondary">
                    <img src="<?= htmlspecialchars($movie['poster_url']) ?>" 
                         alt="<?= htmlspecialchars($movie['title']) ?> Poster"
                         class="img-fluid w-100 d-block"
                         onerror="this.onerror=null; this.src='<?= $defaultPlaceholder ?>';">
                </div>
            </div>

            <!-- Header Info Column -->
            <div class="col-md-8 col-lg-9">
                <div class="detail-tags d-flex flex-wrap align-items-center gap-2 mb-3">
                    <span class="rating-pill">
                        <i class="bi bi-star-fill text-warning me-1"></i>
                        <strong><?= number_format((float)$movie['rating'], 1) ?> / 10</strong>
                    </span>
                    <span class="badge bg-dark border border-secondary text-white px-3 py-2">
                        <i class="bi bi-calendar3 me-1 text-warning"></i> <?= htmlspecialchars($movie['release_year']) ?>
                    </span>
                    <span class="badge bg-dark border border-secondary text-white px-3 py-2">
                        <i class="bi bi-clock me-1 text-warning"></i> <?= htmlspecialchars($movie['duration'] ?? '2h 00m') ?>
                    </span>
                    <span class="badge bg-dark border border-info text-info px-3 py-2 fw-semibold">
                        <?= htmlspecialchars($movie['genre']) ?>
                    </span>
                    <span class="not-playable-badge">
                        <span class="dot"></span> Not Playable Yet
                    </span>
                </div>

                <h1 class="display-5 fw-bold text-white mb-3">
                    <?= htmlspecialchars($movie['title']) ?>
                </h1>

                <!-- Action Buttons -->
                <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
                    <button class="btn btn-primary btn-lg d-inline-flex align-items-center gap-2 btn-play-trigger"
                            data-id="<?= htmlspecialchars($movie['id']) ?>"
                            data-title="<?= htmlspecialchars($movie['title']) ?>"
                            data-backdrop="<?= htmlspecialchars($movie['backdrop_url'] ?? $movie['poster_url']) ?>"
                            data-poster="<?= htmlspecialchars($movie['poster_url']) ?>">
                        <i class="bi bi-play-circle-fill fs-5"></i>
                        <span>Watch Movie</span>
                    </button>

                    <button class="btn <?= $isFav ? 'btn-danger' : 'btn-outline-danger' ?> btn-lg d-inline-flex align-items-center gap-2 btn-fav-toggle"
                            data-movie-id="<?= htmlspecialchars($movie['id']) ?>"
                            data-logged-in="<?= $isLoggedIn ? '1' : '0' ?>"
                            id="detailFavBtn">
                        <i class="bi <?= $isFav ? 'bi-heart-fill' : 'bi-heart' ?>"></i>
                        <span class="fav-btn-text"><?= $isFav ? 'Favorited' : 'Add to Favorites' ?></span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Synopsis & Metadata Row -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="detail-synopsis p-4 rounded-4 bg-dark border border-secondary">
                    <h2 class="h5 fw-bold text-white mb-3">
                        <i class="bi bi-card-text me-2 text-danger"></i> Synopsis &amp; Plot Overview
                    </h2>
                    <p class="text-light mb-4" style="line-height: 1.8; font-size: 1.05rem;">
                        <?= nl2br(htmlspecialchars($movie['description'])) ?>
                    </p>

                    <hr class="border-secondary my-4">

                    <!-- Metadata Grid -->
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-4 g-3">
                        <div class="col">
                            <div class="text-light-emphasis small text-uppercase fw-bold mb-1">Director</div>
                            <div class="text-white fw-semibold fs-6"><?= htmlspecialchars($movie['director']) ?></div>
                        </div>
                        <div class="col">
                            <div class="text-light-emphasis small text-uppercase fw-bold mb-1">Starring Cast</div>
                            <div class="text-white fw-semibold fs-6"><?= htmlspecialchars($movie['cast']) ?></div>
                        </div>
                        <div class="col">
                            <div class="text-light-emphasis small text-uppercase fw-bold mb-1">Release Year</div>
                            <div class="text-white fw-semibold fs-6"><?= htmlspecialchars($movie['release_year']) ?></div>
                        </div>
                        <div class="col">
                            <div class="text-light-emphasis small text-uppercase fw-bold mb-1">Streaming Availability</div>
                            <div class="text-danger fw-bold d-flex align-items-center gap-1">
                                <i class="bi bi-lock-fill"></i> Not Playable Yet (Catalog Only)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
