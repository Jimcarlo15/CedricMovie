<?php
// partials/hero.php - Featured Movie Hero Banner
// Expects: $featuredMovie (array|null)
$basePath = isset($isSubdir) && $isSubdir ? '../' : '';
?>
<section class="hero-section position-relative">
    <div class="hero-backdrop" style="background-image: url('<?= htmlspecialchars($featuredMovie['backdrop_url'] ?? $featuredMovie['poster_url'] ?? 'assets/images/banner-fallback.jpg') ?>');"></div>
    <div class="hero-overlay"></div>
    
    <div class="container-xl position-relative py-5">
        <div class="row align-items-center min-vh-50 py-4">
            <div class="col-lg-8 col-xl-7">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 mb-3 rounded-pill hero-badge">
                    <span class="hero-badge-dot"></span>
                    <span class="small fw-bold text-uppercase tracking-wider">🔥 Featured on CedCine</span>
                </div>
                
                <h1 class="display-4 fw-black text-white mb-2 hero-title">
                    <?= htmlspecialchars($featuredMovie['title'] ?? 'Explore the Cinema Universe') ?>
                </h1>
                
                <div class="d-flex flex-wrap align-items-center gap-3 text-light-emphasis mb-3 small fw-semibold">
                    <span class="text-warning fw-bold">
                        <i class="bi bi-star-fill text-warning me-1"></i>
                        <?= number_format((float)($featuredMovie['rating'] ?? 8.5), 1) ?> / 10
                    </span>
                    <span class="text-secondary">•</span>
                    <span class="text-white"><?= htmlspecialchars($featuredMovie['release_year'] ?? date('Y')) ?></span>
                    <span class="text-secondary">•</span>
                    <span class="text-white"><?= htmlspecialchars($featuredMovie['duration'] ?? '2h 15m') ?></span>
                    <span class="text-secondary">•</span>
                    <span class="badge bg-dark border border-secondary text-white px-3 py-2">
                        <?= htmlspecialchars($featuredMovie['genre'] ?? 'Action / Sci-Fi') ?>
                    </span>
                </div>
                
                <p class="lead text-light mb-4 hero-description">
                    <?= htmlspecialchars($featuredMovie['description'] ?? 'Discover in-depth movie ratings, director spotlights, full cast lists, and cinematic overviews on CedCine.') ?>
                </p>
                
                <div class="d-flex flex-wrap align-items-center gap-3 mb-4">
                    <?php if (!empty($featuredMovie)): ?>
                        <button class="btn btn-primary btn-lg px-4 d-inline-flex align-items-center gap-2 btn-play-trigger"
                                data-id="<?= htmlspecialchars($featuredMovie['id']) ?>"
                                data-title="<?= htmlspecialchars($featuredMovie['title']) ?>"
                                data-backdrop="<?= htmlspecialchars($featuredMovie['backdrop_url'] ?? $featuredMovie['poster_url']) ?>"
                                data-poster="<?= htmlspecialchars($featuredMovie['poster_url']) ?>">
                            <i class="bi bi-play-circle-fill fs-5"></i>
                            <span>Watch Movie</span>
                        </button>
                        <a href="<?= $basePath ?>movie.php?id=<?= urlencode($featuredMovie['id']) ?>" class="btn btn-outline-light btn-lg px-4 d-inline-flex align-items-center gap-2">
                            <i class="bi bi-info-circle"></i>
                            <span>Full Details</span>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Not Playable Yet Informational Notice -->
                <div class="alert alert-dark d-inline-flex align-items-center gap-2 border border-warning text-warning bg-black bg-opacity-75 py-2 px-3 small mb-0 rounded-3">
                    <i class="bi bi-exclamation-triangle-fill text-warning fs-6"></i>
                    <span class="text-light"><strong>Notice:</strong> CedCine is a movie catalog and rating platform. Full movie streaming is <strong class="text-warning">Not Playable Yet</strong>.</span>
                </div>
            </div>
        </div>
    </div>
</section>
