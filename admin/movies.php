<?php
// admin/movies.php - Manage Movie Records
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/partial.php';

require_admin('../login.php');

$pageTitle = 'Manage Movies - CedCine Admin';
$activeAdminPage = 'movies';
$isSubdir = true;

// Query all movies
$movies = $pdo->query("SELECT * FROM movies ORDER BY id DESC")->fetchAll();

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
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary-subtle flex-wrap gap-2">
                <div>
                    <h1 class="h3 fw-bold text-white mb-1">
                        <i class="bi bi-film text-danger me-2"></i> Movie Management
                    </h1>
                    <p class="text-muted small mb-0">Browse, search, edit, or remove movies from the CedCine database.</p>
                </div>
                <div>
                    <a href="add_movie.php" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 shadow-sm">
                        <i class="bi bi-plus-lg"></i>
                        <span>Add New Movie</span>
                    </a>
                </div>
            </div>

            <!-- Filter & Search Toolbar -->
            <div class="admin-card p-3 mb-4">
                <div class="row g-2 align-items-center justify-content-between">
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-dark-subtle border-secondary text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="adminTableSearch" class="form-control bg-dark text-light border-secondary" placeholder="Search by title, director, or genre...">
                        </div>
                    </div>
                    <div class="col-auto text-muted small" id="adminSearchCount">
                        Total Movies: <strong class="text-light"><?= count($movies) ?></strong>
                    </div>
                </div>
            </div>

            <!-- Movies Table -->
            <div class="admin-card p-4">
                <div class="table-responsive">
                    <table class="table table-admin align-middle admin-data-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">ID</th>
                                <th style="width: 60px;">Poster</th>
                                <th>Title &amp; Director</th>
                                <th>Genre</th>
                                <th>Year</th>
                                <th>Rating</th>
                                <th>Featured</th>
                                <th>Date Added</th>
                                <th class="text-end" style="width: 140px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($movies)): ?>
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-5">
                                        <i class="bi bi-film display-5 d-block mb-2 text-secondary"></i>
                                        No movies registered in database yet.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($movies as $movie): ?>
                                    <tr>
                                        <td class="text-light-emphasis small fw-semibold">#<?= $movie['id'] ?></td>
                                        <td>
                                            <img src="<?= htmlspecialchars($movie['poster_url']) ?>" alt="Poster" class="admin-poster-thumb">
                                        </td>
                                        <td>
                                            <a href="../movie.php?id=<?= $movie['id'] ?>" target="_blank" class="text-white fw-bold text-decoration-none">
                                                <?= htmlspecialchars($movie['title']) ?>
                                            </a>
                                            <div class="text-light-emphasis small"><?= htmlspecialchars($movie['director']) ?></div>
                                        </td>
                                        <td>
                                            <span class="badge bg-dark border border-secondary text-white small">
                                                <?= htmlspecialchars($movie['genre']) ?>
                                            </span>
                                        </td>
                                        <td class="text-white fw-medium"><?= htmlspecialchars($movie['release_year']) ?></td>
                                        <td>
                                            <span class="text-warning fw-bold small">
                                                <i class="bi bi-star-fill me-1"></i><?= number_format((float)$movie['rating'], 1) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if (!empty($movie['featured'])): ?>
                                                <span class="badge bg-warning text-dark fw-bold small">Featured</span>
                                            <?php else: ?>
                                                <span class="badge bg-dark border border-secondary-subtle text-light-emphasis small">Standard</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-light-emphasis small">
                                            <?= date('M d, Y', strtotime($movie['created_at'])) ?>
                                        </td>
                                        <td class="text-end">
                                            <div class="btn-group btn-group-sm">
                                                <a href="../movie.php?id=<?= $movie['id'] ?>" target="_blank" class="btn btn-outline-secondary" title="View Public Page">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="edit_movie.php?id=<?= $movie['id'] ?>" class="btn btn-outline-info" title="Edit Movie">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="delete_movie.php?id=<?= $movie['id'] ?>" class="btn btn-outline-danger btn-confirm-delete" data-name="<?= htmlspecialchars($movie['title']) ?>" title="Delete Movie">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
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

