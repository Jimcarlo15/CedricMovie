<?php
// partials/admin_sidebar.php - Admin Navigation Sidebar
// Expects: $activeAdminPage (string, e.g. 'dashboard', 'movies', 'add_movie', 'users')
$activePage = $activeAdminPage ?? 'dashboard';
$user = function_exists('current_user') ? current_user() : null;
?>
<aside class="col-12 col-md-3 col-lg-2 admin-sidebar bg-dark p-0 border-end border-secondary-subtle">
    <div class="d-flex flex-column h-100 p-3">
        <!-- Brand Link -->
        <a href="index.php" class="d-flex align-items-center gap-2 mb-4 text-decoration-none text-white brand-logo">
            <span class="brand-icon small">
                <i class="bi bi-film"></i>
            </span>
            <span class="h5 fw-bold mb-0">Ced<span class="text-danger">Cine</span> <small class="badge bg-warning text-dark text-uppercase ms-1" style="font-size: 0.6rem;">Admin</small></span>
        </a>

        <!-- Admin User Summary -->
        <div class="admin-user-card p-3 mb-4 rounded-3 bg-dark-subtle border border-secondary-subtle d-flex align-items-center gap-3">
            <div class="user-avatar bg-warning text-dark fw-bold">
                <?= strtoupper(substr($user['full_name'] ?? 'A', 0, 1)) ?>
            </div>
            <div class="overflow-hidden">
                <div class="fw-bold text-white text-truncate small"><?= htmlspecialchars($user['full_name'] ?? 'Administrator') ?></div>
                <div class="text-muted small text-truncate">@<?= htmlspecialchars($user['username'] ?? 'admin') ?></div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <ul class="nav nav-pills flex-column mb-auto gap-1 admin-nav-links">
            <li class="nav-item">
                <a href="index.php" class="nav-link <?= $activePage === 'dashboard' ? 'active' : 'text-light-emphasis' ?>">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard Overview
                </a>
            </li>
            <li class="nav-item">
                <a href="movies.php" class="nav-link <?= $activePage === 'movies' ? 'active' : 'text-light-emphasis' ?>">
                    <i class="bi bi-film me-2"></i> Manage Movies
                </a>
            </li>
            <li class="nav-item">
                <a href="add_movie.php" class="nav-link <?= $activePage === 'add_movie' ? 'active' : 'text-light-emphasis' ?>">
                    <i class="bi bi-plus-circle me-2"></i> Add New Movie
                </a>
            </li>
            <li class="nav-item">
                <a href="users.php" class="nav-link <?= $activePage === 'users' ? 'active' : 'text-light-emphasis' ?>">
                    <i class="bi bi-people me-2"></i> Manage Users
                </a>
            </li>
        </ul>

        <hr class="border-secondary-subtle my-3">

        <!-- Bottom Links -->
        <ul class="nav nav-pills flex-column gap-1">
            <li class="nav-item">
                <a href="../index.php" class="nav-link text-info" target="_blank">
                    <i class="bi bi-box-arrow-up-right me-2"></i> View Live Site
                </a>
            </li>
            <li class="nav-item">
                <a href="../logout.php" class="nav-link text-danger">
                    <i class="bi bi-box-arrow-right me-2"></i> Log Out
                </a>
            </li>
        </ul>
    </div>
</aside>

