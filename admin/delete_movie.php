<?php
// admin/delete_movie.php - Safe Movie Deletion Handler
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/partial.php';

require_admin('../login.php');

$movieId = (int)($_REQUEST['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM movies WHERE id = :id");
$stmt->execute([':id' => $movieId]);
$movie = $stmt->fetch();

if (!$movie) {
    set_flash('danger', 'Movie not found or already deleted.');
    header("Location: movies.php");
    exit;
}

// If POST request with CSRF verification, execute deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (verify_csrf_token($_POST['csrf_token'] ?? '')) {
        $delStmt = $pdo->prepare("DELETE FROM movies WHERE id = :id");
        $delStmt->execute([':id' => $movieId]);

        set_flash('success', 'Movie "' . htmlspecialchars($movie['title']) . '" has been permanently deleted.');
        header("Location: movies.php");
        exit;
    } else {
        set_flash('danger', 'Invalid security token. Deletion aborted.');
        header("Location: movies.php");
        exit;
    }
}

// If accessed via GET, render a dedicated confirmation page
$pageTitle = 'Confirm Deletion - CedCine Admin';
$activeAdminPage = 'movies';
$isSubdir = true;

render_partial('header', [
    'pageTitle' => $pageTitle,
    'isSubdir'  => true,
    'extraCss'  => ['assets/css/admin.css']
]);
?>

<div class="container-fluid">
    <div class="row">
        <?php render_partial('admin_sidebar', ['activeAdminPage' => 'movies']); ?>

        <main class="col-12 col-md-9 col-lg-10 p-4 ms-auto">
            <div class="admin-card p-4 mx-auto text-center" style="max-width: 500px; margin-top: 5vh;">
                <div class="stat-icon movies mx-auto mb-3" style="width: 64px; height: 64px; font-size: 2rem;">
                    <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                </div>
                <h2 class="h4 fw-bold text-white mb-2">Delete Movie?</h2>
                <p class="text-muted small mb-4">
                    Are you sure you want to permanently delete <strong class="text-white">"<?= htmlspecialchars($movie['title']) ?>"</strong> (#<?= $movie['id'] ?>)? This will also remove it from any user favorite lists. This action cannot be undone.
                </p>

                <form action="delete_movie.php?id=<?= $movieId ?>" method="POST">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $movieId ?>">
                    <div class="d-flex justify-content-center gap-2">
                        <a href="movies.php" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-danger d-inline-flex align-items-center gap-1">
                            <i class="bi bi-trash-fill"></i>
                            <span>Confirm &amp; Delete</span>
                        </button>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

<?php 
render_partial('footer', [
    'isSubdir' => true,
    'extraJs'  => ['assets/js/admin.js']
]); 
?>

