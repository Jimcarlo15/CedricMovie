<?php
// favorites.php - User's Favorite Movies
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/helpers/auth.php';
require_once __DIR__ . '/helpers/partial.php';

require_login('login.php');

$userId = (int)$_SESSION['user']['id'];
$pageTitle = 'My Favorite Movies - CedCine';

// Handle removal via POST if submitted as a form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'remove_favorite') {
    if (verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $removeMovieId = (int)($_POST['movie_id'] ?? 0);
        $del = $pdo->prepare("DELETE FROM favorites WHERE user_id = :uid AND movie_id = :mid");
        $del->execute([':uid' => $userId, ':mid' => $removeMovieId]);
        set_flash('success', 'Movie removed from your favorites.');
    } else {
        set_flash('danger', 'Invalid security token.');
    }
    header("Location: favorites.php");
    exit;
}

// Fetch user's favorited movies
$stmt = $pdo->prepare("
    SELECT m.*, f.created_at AS favorited_at
    FROM favorites f
    JOIN movies m ON f.movie_id = m.id
    WHERE f.user_id = :uid
    ORDER BY f.created_at DESC
");
$stmt->execute([':uid' => $userId]);
$favoriteMovies = $stmt->fetchAll();

$userFavoriteIds = array_column($favoriteMovies, 'id');

render_partial('header', ['pageTitle' => $pageTitle]);
render_partial('navbar');
?>

<main class="container-xl py-5">
    <!-- Header Title -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2 pb-3 border-bottom border-secondary-subtle">
        <div>
            <h1 class="h3 fw-bold text-white mb-1">
                <i class="bi bi-heart-fill text-danger me-2"></i> My Favorite Movies
            </h1>
            <p class="text-light-emphasis small mb-0 fw-medium">
                You have saved <strong class="text-white"><?= count($favoriteMovies) ?></strong> <?= count($favoriteMovies) === 1 ? 'title' : 'titles' ?> to your watchlist.
            </p>
        </div>
        <a href="index.php#catalog" class="btn btn-outline-light btn-sm d-inline-flex align-items-center gap-1">
            <i class="bi bi-collection-play"></i>
            <span>Browse More Movies</span>
        </a>
    </div>

    <!-- Movies Grid or Empty State -->
    <?php if (empty($favoriteMovies)): ?>
        <div class="empty-state-card text-center p-5 my-4 rounded-4 border border-secondary">
            <div class="mb-3">
                <i class="bi bi-heart text-danger opacity-75 display-3"></i>
            </div>
            <h3 class="h4 text-white fw-bold mb-2">No Favorites Yet</h3>
            <p class="text-light-emphasis mb-4 mx-auto" style="max-width: 460px; font-size: 1rem;">
                You haven't added any movies to your favorites. Explore our movie catalog and click the heart icon on any movie you like!
            </p>
            <a href="index.php#catalog" class="btn btn-primary btn-sm px-4">
                <i class="bi bi-film me-1"></i> Explore Catalog
            </a>
        </div>
    <?php else: ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-xl-4 g-4" id="moviesContainer">
            <?php foreach ($favoriteMovies as $movie): ?>
                <?php render_partial('movie_card', [
                    'movie'           => $movie,
                    'userFavoriteIds' => $userFavoriteIds
                ]); ?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php
render_partial('player_modal');
render_partial('footer');
?>

