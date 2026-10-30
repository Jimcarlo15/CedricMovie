<?php
// partials/navbar.php
$basePath = isset($isSubdir) && $isSubdir ? '../' : '';
$user = function_exists('current_user') ? current_user() : null;
$isAdmin = function_exists('is_admin') ? is_admin() : false;

// Get favorite count if logged in
$favCount = 0;
if ($user && isset($pdo)) {
    try {
        $stmtFav = $pdo->prepare("SELECT COUNT(*) FROM favorites WHERE user_id = :uid");
        $stmtFav->execute([':uid' => $user['id']]);
        $favCount = (int)$stmtFav->fetchColumn();
    } catch (Exception $e) {
        $favCount = 0;
    }
}
?>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top cedcine-nav">
    <div class="container-xl">
        <!-- Brand Logo -->
        <a class="navbar-brand d-flex align-items-center gap-2 brand-logo" href="<?= $basePath ?>index.php">
            <span class="brand-icon">
                <i class="bi bi-film"></i>
            </span>
            <span class="fw-bold tracking-tight">Ced<span class="brand-accent">Cine</span></span>
        </a>

        <!-- Mobile Toggler -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#cedcineNavbar" aria-controls="cedcineNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="cedcineNavbar">
            <!-- Nav Links -->
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link" href="<?= $basePath ?>index.php"><i class="bi bi-house-door me-1"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= $basePath ?>index.php#catalog"><i class="bi bi-collection-play me-1"></i> Catalog</a>
                </li>
                <?php if ($user): ?>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="<?= $basePath ?>favorites.php">
                            <i class="bi bi-heart-fill text-danger me-1"></i> Favorites
                            <?php if ($favCount > 0): ?>
                                <span class="badge rounded-pill bg-danger ms-1 fav-badge-count"><?= $favCount ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if ($isAdmin): ?>
                    <li class="nav-item">
                        <a class="nav-link text-warning fw-semibold" href="<?= $basePath ?>admin/index.php">
                            <i class="bi bi-speedometer2 me-1"></i> Admin Panel
                        </a>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- Search Bar -->
            <div class="nav-search-wrapper my-2 my-lg-0 me-lg-3 flex-grow-1" style="max-width: 440px;">
                <div class="input-group search-input-group">
                    <span class="input-group-text bg-transparent border-0 text-muted ps-3">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" id="navbarSearch" class="form-control bg-transparent border-0 text-light shadow-none" placeholder="Search by title, genre, actor..." autocomplete="off">
                    <button class="btn btn-sm btn-clear-search d-none text-muted" type="button" id="clearSearchBtn">
                        <i class="bi bi-x-circle-fill"></i>
                    </button>
                </div>
            </div>

            <!-- Auth Buttons / User Profile -->
            <div class="d-flex align-items-center gap-2 mt-2 mt-lg-0">
                <?php if ($user): ?>
                    <div class="dropdown">
                        <button class="btn btn-user-profile dropdown-toggle d-flex align-items-center gap-2" type="button" id="userMenuDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar">
                                <?= strtoupper(substr($user['full_name'] ?? $user['username'], 0, 1)) ?>
                            </div>
                            <span class="d-none d-sm-inline fw-semibold text-truncate" style="max-width: 140px;">
                                <?= htmlspecialchars($user['username']) ?>
                            </span>
                            <span class="badge <?= $isAdmin ? 'bg-warning text-dark' : 'bg-primary' ?> text-uppercase" style="font-size: 0.65rem;">
                                <?= htmlspecialchars($user['role']) ?>
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark shadow-lg border-0 py-2" aria-labelledby="userMenuDropdown">
                            <li class="dropdown-header text-muted">
                                Signed in as <br><strong class="text-light"><?= htmlspecialchars($user['email']) ?></strong>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="<?= $basePath ?>favorites.php">
                                    <i class="bi bi-heart me-2 text-danger"></i> My Favorites
                                </a>
                            </li>
                            <?php if ($isAdmin): ?>
                                <li>
                                    <a class="dropdown-item" href="<?= $basePath ?>admin/index.php">
                                        <i class="bi bi-shield-lock me-2 text-warning"></i> Admin Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= $basePath ?>admin/movies.php">
                                        <i class="bi bi-film me-2 text-info"></i> Manage Movies
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= $basePath ?>admin/users.php">
                                        <i class="bi bi-people me-2 text-success"></i> Manage Users
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item text-danger" href="<?= $basePath ?>logout.php">
                                    <i class="bi bi-box-arrow-right me-2"></i> Log Out
                                </a>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?= $basePath ?>login.php" class="btn btn-outline-light btn-sm px-3">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Log In
                    </a>
                    <a href="<?= $basePath ?>register.php" class="btn btn-primary btn-sm px-3">
                        <i class="bi bi-person-plus me-1"></i> Register
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
