<?php
// movie.php - Movie Detail View (Modular Partials)
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/helpers/auth.php';
require_once __DIR__ . '/helpers/partial.php';

$movieId = intval($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM movies WHERE id = :id");
$stmt->execute([':id' => $movieId]);
$movie = $stmt->fetch();

if (!$movie) {
    set_flash('warning', 'The requested movie was not found in the catalog.');
    header("Location: index.php");
    exit;
}

$pageTitle = htmlspecialchars($movie['title']) . " - Ratings & Synopsis | CedCine";

// Check if this movie is favorited by current user
$isFavorite = false;
if (is_logged_in()) {
    $userId = (int)$_SESSION['user']['id'];
    $favStmt = $pdo->prepare("SELECT id FROM favorites WHERE user_id = :uid AND movie_id = :mid");
    $favStmt->execute([':uid' => $userId, ':mid' => $movieId]);
    $isFavorite = (bool)$favStmt->fetch();
}

// Render Partials
render_partial('header', ['pageTitle' => $pageTitle]);
render_partial('navbar');
?>

<main class="container-xl py-4">
    <div class="mb-3">
        <a href="index.php#catalog" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
            <i class="bi bi-arrow-left"></i>
            <span>Back to Movie Catalog</span>
        </a>
    </div>

    <!-- Movie Detail Partial -->
    <?php render_partial('movie_detail', [
        'movie'      => $movie,
        'isFavorite' => $isFavorite
    ]); ?>
</main>

<?php 
render_partial('player_modal');
render_partial('footer'); 
?>
