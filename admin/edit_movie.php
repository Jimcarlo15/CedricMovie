<?php
// admin/edit_movie.php - Edit Existing Movie Record
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/partial.php';

require_admin('../login.php');

$pageTitle = 'Edit Movie - CedCine Admin';
$activeAdminPage = 'movies';
$isSubdir = true;

$movieId = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM movies WHERE id = :id");
$stmt->execute([':id' => $movieId]);
$movie = $stmt->fetch();

if (!$movie) {
    set_flash('danger', 'Movie not found.');
    header("Location: movies.php");
    exit;
}

$errors = [];
$title = $movie['title'];
$genre = $movie['genre'];
$releaseYear = $movie['release_year'];
$rating = $movie['rating'];
$duration = $movie['duration'] ?? '2h 00m';
$director = $movie['director'];
$cast = $movie['cast'];
$posterUrl = $movie['poster_url'];
$backdropUrl = $movie['backdrop_url'] ?? $movie['poster_url'];
$description = $movie['description'];
$featured = (int)$movie['featured'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!verify_csrf_token($csrfToken)) {
        $errors[] = 'Invalid security session. Please try again.';
    }

    $title = trim($_POST['title'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $releaseYear = (int)($_POST['release_year'] ?? 0);
    $rating = (float)($_POST['rating'] ?? 0.0);
    $duration = trim($_POST['duration'] ?? '2h 00m');
    $director = trim($_POST['director'] ?? '');
    $cast = trim($_POST['cast'] ?? '');
    $posterUrl = trim($_POST['poster_url'] ?? '');
    $backdropUrl = trim($_POST['backdrop_url'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $featured = isset($_POST['featured']) ? 1 : 0;

    if (empty($backdropUrl)) {
        $backdropUrl = $posterUrl;
    }

    // Validation
    if (empty($title)) $errors[] = 'Movie title is required.';
    if (empty($genre)) $errors[] = 'Genre is required.';
    if ($releaseYear < 1888 || $releaseYear > 2100) $errors[] = 'Please enter a valid release year.';
    if ($rating < 0 || $rating > 10) $errors[] = 'Rating must be between 0.0 and 10.0.';
    if (empty($director)) $errors[] = 'Director is required.';
    if (empty($cast)) $errors[] = 'Starring cast is required.';
    if (empty($posterUrl)) $errors[] = 'Poster image URL is required.';
    if (empty($description)) $errors[] = 'Movie synopsis is required.';

    if (empty($errors)) {
        if ($featured === 1) {
            $pdo->exec("UPDATE movies SET featured = 0");
        }

        $updateStmt = $pdo->prepare("
            UPDATE movies SET
                title = :title,
                genre = :genre,
                release_year = :year,
                rating = :rating,
                duration = :duration,
                director = :director,
                cast = :cast,
                poster_url = :poster,
                backdrop_url = :backdrop,
                description = :description,
                featured = :featured
            WHERE id = :id
        ");

        $updateStmt->execute([
            ':title'       => $title,
            ':genre'       => $genre,
            ':year'        => $releaseYear,
            ':rating'      => $rating,
            ':duration'    => $duration,
            ':director'    => $director,
            ':cast'        => $cast,
            ':poster'      => $posterUrl,
            ':backdrop'    => $backdropUrl,
            ':description' => $description,
            ':featured'    => $featured,
            ':id'          => $movieId
        ]);

        set_flash('success', 'Movie "' . htmlspecialchars($title) . '" updated successfully.');
        header("Location: movies.php");
        exit;
    }
}

render_partial('header', [
    'pageTitle' => $pageTitle,
    'isSubdir'  => true,
    'extraCss'  => ['assets/css/admin.css']
]);
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php render_partial('admin_sidebar', ['activeAdminPage' => 'movies']); ?>

        <!-- Main Content -->
        <main class="col-12 col-md-9 col-lg-10 p-4 ms-auto">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary-subtle">
                <div>
                    <h1 class="h3 fw-bold text-white mb-1">
                        <i class="bi bi-pencil-square text-info me-2"></i> Edit Movie #<?= $movieId ?>
                    </h1>
                    <p class="text-muted small mb-0">Update movie details for "<?= htmlspecialchars($movie['title']) ?>".</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="../movie.php?id=<?= $movieId ?>" target="_blank" class="btn btn-outline-info btn-sm">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Public Page
                    </a>
                    <a href="movies.php" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left me-1"></i> Back to Movies
                    </a>
                </div>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger py-2 px-3 small mb-4 rounded-3" role="alert">
                    <div class="fw-bold mb-1"><i class="bi bi-exclamation-triangle-fill me-1"></i> Please fix the following errors:</div>
                    <ul class="mb-0 ps-3">
                        <?php foreach ($errors as $err): ?>
                            <li><?= htmlspecialchars($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="row g-4">
                <div class="col-12 col-lg-8">
                    <div class="admin-card p-4">
                        <form action="edit_movie.php?id=<?= $movieId ?>" method="POST" novalidate>
                            <?= csrf_field() ?>

                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-8">
                                    <label for="title" class="form-label text-light small fw-semibold">Movie Title *</label>
                                    <input type="text" class="form-control form-control-auth" id="title" name="title" value="<?= htmlspecialchars($title) ?>" required>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label for="genre" class="form-label text-light small fw-semibold">Genre *</label>
                                    <input type="text" class="form-control form-control-auth" id="genre" name="genre" value="<?= htmlspecialchars($genre) ?>" required>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-12 col-sm-4">
                                    <label for="release_year" class="form-label text-light small fw-semibold">Release Year *</label>
                                    <input type="number" class="form-control form-control-auth" id="release_year" name="release_year" value="<?= htmlspecialchars($releaseYear) ?>" min="1900" max="2100" required>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <label for="rating" class="form-label text-light small fw-semibold">Rating (0 - 10) *</label>
                                    <input type="number" step="0.1" class="form-control form-control-auth" id="rating" name="rating" value="<?= htmlspecialchars($rating) ?>" min="0" max="10" required>
                                </div>
                                <div class="col-12 col-sm-4">
                                    <label for="duration" class="form-label text-light small fw-semibold">Duration *</label>
                                    <input type="text" class="form-control form-control-auth" id="duration" name="duration" value="<?= htmlspecialchars($duration) ?>" required>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-6">
                                    <label for="director" class="form-label text-light small fw-semibold">Director *</label>
                                    <input type="text" class="form-control form-control-auth" id="director" name="director" value="<?= htmlspecialchars($director) ?>" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="cast" class="form-label text-light small fw-semibold">Starring Cast *</label>
                                    <input type="text" class="form-control form-control-auth" id="cast" name="cast" value="<?= htmlspecialchars($cast) ?>" required>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-6">
                                    <label for="poster_url" class="form-label text-light small fw-semibold">Poster Image URL *</label>
                                    <input type="url" class="form-control form-control-auth" id="poster_url" name="poster_url" value="<?= htmlspecialchars($posterUrl) ?>" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label for="backdrop_url" class="form-label text-light small fw-semibold">Backdrop Banner URL</label>
                                    <input type="url" class="form-control form-control-auth" id="backdrop_url" name="backdrop_url" value="<?= htmlspecialchars($backdropUrl) ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label text-light small fw-semibold">Synopsis / Description *</label>
                                <textarea class="form-control form-control-auth" id="description" name="description" rows="4" required><?= htmlspecialchars($description) ?></textarea>
                            </div>

                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" role="switch" id="featured" name="featured" value="1" <?= $featured ? 'checked' : '' ?>>
                                <label class="form-check-label text-light small" for="featured">
                                    Set as <strong>Featured Hero Movie</strong> on Public Homepage
                                </label>
                            </div>

                            <div class="d-flex justify-content-end gap-2 pt-3 border-top border-secondary-subtle">
                                <a href="movies.php" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                                    <i class="bi bi-save me-1"></i> Update Movie
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Preview Column -->
                <div class="col-12 col-lg-4">
                    <div class="admin-card p-4 text-center">
                        <h2 class="h6 fw-bold text-white mb-3">Poster Preview</h2>
                        <div class="detail-poster-box mx-auto rounded-3 overflow-hidden bg-black border border-secondary mb-3" style="max-width: 200px; aspect-ratio: 2/3;">
                            <img id="posterPreviewImg" src="<?= htmlspecialchars($posterUrl) ?>" alt="Poster Preview" class="w-100 h-100 object-fit-cover">
                        </div>
                        <p class="text-muted small mb-0">Changes to the poster URL will update this preview automatically.</p>
                    </div>
                </div>
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

