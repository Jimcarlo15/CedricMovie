<?php
// admin/index.php - Admin Dashboard Overview
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/partial.php';

require_admin('../login.php');

$pageTitle = 'Dashboard Overview - CedCine Admin';
$activeAdminPage = 'dashboard';
$isSubdir = true;

// Query Statistics
$totalMovies = (int)$pdo->query("SELECT COUNT(*) FROM movies")->fetchColumn();
$totalUsers = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalAdmins = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
$totalFavorites = (int)$pdo->query("SELECT COUNT(*) FROM favorites")->fetchColumn();

// Query Recently Added Movies (limit 5)
$recentMovies = $pdo->query("SELECT * FROM movies ORDER BY id DESC LIMIT 5")->fetchAll();

// Query Recently Registered Users (limit 5)
$recentUsers = $pdo->query("SELECT * FROM users ORDER BY id DESC LIMIT 5")->fetchAll();

render_partial('header', [
    'pageTitle' => $pageTitle,
    'isSubdir'  => true,
    'extraCss'  => ['assets/css/admin.css']
]);
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar Partial -->
        <?php render_partial('admin_sidebar', ['activeAdminPage' => 'dashboard']); ?>

        <!-- Main Admin Content Area -->
        <main class="col-12 col-md-9 col-lg-10 p-4 ms-auto">
            <!-- Top Bar -->
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary-subtle flex-wrap gap-2">
                <div>
                    <h1 class="h3 fw-bold text-white mb-1">
                        <i class="bi bi-speedometer2 text-danger me-2"></i> Admin Dashboard
                    </h1>
                    <p class="text-muted small mb-0">Overview of CedCine catalog, registered users, and system analytics.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="add_movie.php" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1">
                        <i class="bi bi-plus-lg"></i>
                        <span>Add Movie</span>
                    </a>
                    <a href="../index.php" class="btn btn-outline-light btn-sm d-inline-flex align-items-center gap-1" target="_blank">
                        <i class="bi bi-eye"></i>
                        <span>View Site</span>
                    </a>
                </div>
            </div>

            <!-- Statistics Summary Cards -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-card p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-light-emphasis small text-uppercase fw-bold mb-1">Total Movies</div>
                            <div class="display-6 fw-bold text-white"><?= $totalMovies ?></div>
                            <a href="movies.php" class="small text-danger fw-semibold text-decoration-none">View catalog &rarr;</a>
                        </div>
                        <div class="stat-icon movies">
                            <i class="bi bi-film"></i>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-card p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-light-emphasis small text-uppercase fw-bold mb-1">Registered Users</div>
                            <div class="display-6 fw-bold text-white"><?= $totalUsers ?></div>
                            <a href="users.php" class="small text-info fw-semibold text-decoration-none">Manage accounts &rarr;</a>
                        </div>
                        <div class="stat-icon users">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-card p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-light-emphasis small text-uppercase fw-bold mb-1">Administrators</div>
                            <div class="display-6 fw-bold text-white"><?= $totalAdmins ?></div>
                            <span class="small text-warning fw-semibold">System Staff</span>
                        </div>
                        <div class="stat-icon admins">
                            <i class="bi bi-shield-check"></i>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="admin-card p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-light-emphasis small text-uppercase fw-bold mb-1">Saved Favorites</div>
                            <div class="display-6 fw-bold text-white"><?= $totalFavorites ?></div>
                            <span class="small text-light-emphasis fw-semibold">User Watchlists</span>
                        </div>
                        <div class="stat-icon favorites">
                            <i class="bi bi-heart-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Recently Added Movies Table -->
                <div class="col-12 col-xl-8">
                    <div class="admin-card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="h5 fw-bold text-white mb-0">
                                <i class="bi bi-film text-danger me-2"></i> Recently Added Movies
                            </h2>
                            <a href="movies.php" class="btn btn-outline-secondary btn-sm">View All Movies</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-admin align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Poster</th>
                                        <th>Movie Title</th>
                                        <th>Genre</th>
                                        <th>Rating</th>
                                        <th>Year</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($recentMovies)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">No movies in database yet.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($recentMovies as $m): ?>
                                            <tr>
                                                <td style="width: 50px;">
                                                    <img src="<?= htmlspecialchars($m['poster_url']) ?>" alt="Poster" class="admin-poster-thumb">
                                                </td>
                                                <td>
                                                    <a href="../movie.php?id=<?= $m['id'] ?>" class="text-white text-decoration-none fw-bold" target="_blank">
                                                        <?= htmlspecialchars($m['title']) ?>
                                                    </a>
                                                    <div class="small text-light-emphasis"><?= htmlspecialchars($m['director']) ?></div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-dark border border-secondary text-white small">
                                                        <?= htmlspecialchars($m['genre']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="text-warning fw-bold small">
                                                        <i class="bi bi-star-fill me-1"></i><?= number_format((float)$m['rating'], 1) ?>
                                                    </span>
                                                </td>
                                                <td class="text-white"><?= htmlspecialchars($m['release_year']) ?></td>
                                                <td class="text-end">
                                                    <a href="edit_movie.php?id=<?= $m['id'] ?>" class="btn btn-outline-info btn-sm py-0 px-2" title="Edit Movie">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <a href="delete_movie.php?id=<?= $m['id'] ?>" class="btn btn-outline-danger btn-sm py-0 px-2 btn-confirm-delete" data-name="<?= htmlspecialchars($m['title']) ?>" title="Delete Movie">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Recent Registered Users -->
                <div class="col-12 col-xl-4">
                    <div class="admin-card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h2 class="h5 fw-bold text-white mb-0">
                                <i class="bi bi-people text-info me-2"></i> New Users
                            </h2>
                            <a href="users.php" class="btn btn-outline-secondary btn-sm">All Users</a>
                        </div>
                        <ul class="list-group list-group-flush bg-transparent">
                            <?php foreach ($recentUsers as $u): ?>
                                <li class="list-group-item bg-transparent border-secondary-subtle px-0 py-2 d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="user-avatar" style="width: 28px; height: 28px; font-size: 0.75rem;">
                                            <?= strtoupper(substr($u['full_name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="text-white small fw-semibold"><?= htmlspecialchars($u['full_name']) ?></div>
                                            <div class="text-muted" style="font-size: 0.75rem;">@<?= htmlspecialchars($u['username']) ?></div>
                                        </div>
                                    </div>
                                    <span class="badge <?= $u['role'] === 'admin' ? 'bg-warning text-dark' : 'bg-primary' ?> text-uppercase" style="font-size: 0.65rem;">
                                        <?= htmlspecialchars($u['role']) ?>
                                    </span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
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

