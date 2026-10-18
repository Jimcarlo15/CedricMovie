<?php
// movie.php - Movie Detail View (Modular Partials)
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/helpers/partial.php';

$movieId = intval($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM movies WHERE id = :id");
$stmt->execute([':id' => $movieId]);
$movie = $stmt->fetch();

if (!$movie) {
    header("Location: index.php");
    exit;
}

$pageTitle = htmlspecialchars($movie['title']) . " - Ratings & Synopsis | CedricMovie";

// Render Partials
render_partial('header', ['pageTitle' => $pageTitle]);
render_partial('navbar');
?>

<main class="main-container">
    <div style="margin-bottom: 1.5rem;">
        <a href="index.php" class="btn btn-secondary btn-sm" style="display:inline-flex; align-items:center; gap:0.4rem;">
            &larr; Back to Movie List
        </a>
    </div>

    <!-- Movie Detail Partial -->
    <?php render_partial('movie_detail', ['movie' => $movie]); ?>
</main>

<?php 
render_partial('player_modal');
render_partial('add_modal');
render_partial('footer'); 
?>
