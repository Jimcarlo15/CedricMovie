<?php
// admin/users.php - User Account Management
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/auth.php';
require_once __DIR__ . '/../helpers/partial.php';

require_admin('../login.php');

$pageTitle = 'Manage Users - CedCine Admin';
$activeAdminPage = 'users';
$isSubdir = true;

$currentAdminId = (int)$_SESSION['user']['id'];

// Handle Role Change or Deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $targetUserId = (int)($_POST['user_id'] ?? 0);
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!verify_csrf_token($csrfToken)) {
        set_flash('danger', 'Invalid security token.');
        header("Location: users.php");
        exit;
    }

    // Get target user details
    $uStmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $uStmt->execute([':id' => $targetUserId]);
    $targetUser = $uStmt->fetch();

    if (!$targetUser) {
        set_flash('danger', 'Target user does not exist.');
        header("Location: users.php");
        exit;
    }

    // Count how many admins exist currently
    $adminCount = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();

    // 1. Change Role
    if ($action === 'toggle_role') {
        $newRole = ($targetUser['role'] === 'admin') ? 'user' : 'admin';

        // Check if admin is trying to demote themselves
        if ($targetUser['id'] === $currentAdminId && $newRole === 'user') {
            set_flash('danger', 'Security rule: You cannot demote your own administrator account.');
            header("Location: users.php");
            exit;
        }

        // Check if demoting the last remaining admin
        if ($targetUser['role'] === 'admin' && $newRole === 'user' && $adminCount <= 1) {
            set_flash('danger', 'Security rule: Cannot demote the last remaining administrator account.');
            header("Location: users.php");
            exit;
        }

        $upd = $pdo->prepare("UPDATE users SET role = :role WHERE id = :id");
        $upd->execute([':role' => $newRole, ':id' => $targetUserId]);

        set_flash('success', 'User @' . htmlspecialchars($targetUser['username']) . ' role changed to ' . strtoupper($newRole) . '.');
        header("Location: users.php");
        exit;
    }

    // 2. Delete User
    if ($action === 'delete_user') {
        // Prevent deleting own account
        if ($targetUser['id'] === $currentAdminId) {
            set_flash('danger', 'Security rule: You cannot delete your own account while logged in.');
            header("Location: users.php");
            exit;
        }

        // Prevent deleting last admin
        if ($targetUser['role'] === 'admin' && $adminCount <= 1) {
            set_flash('danger', 'Security rule: Cannot delete the last remaining administrator account.');
            header("Location: users.php");
            exit;
        }

        $del = $pdo->prepare("DELETE FROM users WHERE id = :id");
        $del->execute([':id' => $targetUserId]);

        set_flash('success', 'User @' . htmlspecialchars($targetUser['username']) . ' has been permanently removed.');
        header("Location: users.php");
        exit;
    }
}

// Fetch all users
$users = $pdo->query("SELECT * FROM users ORDER BY id ASC")->fetchAll();

render_partial('header', [
    'pageTitle' => $pageTitle,
    'isSubdir'  => true,
    'extraCss'  => ['assets/css/admin.css']
]);
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php render_partial('admin_sidebar', ['activeAdminPage' => 'users']); ?>

        <!-- Main Content -->
        <main class="col-12 col-md-9 col-lg-10 p-4 ms-auto">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-secondary-subtle flex-wrap gap-2">
                <div>
                    <h1 class="h3 fw-bold text-white mb-1">
                        <i class="bi bi-people text-info me-2"></i> User Management
                    </h1>
                    <p class="text-muted small mb-0">Manage registered user accounts, assign roles, and maintain system security.</p>
                </div>
            </div>

            <!-- Search Filter Bar -->
            <div class="admin-card p-3 mb-4">
                <div class="row g-2 align-items-center justify-content-between">
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-dark-subtle border-secondary text-muted">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" id="adminTableSearch" class="form-control bg-dark text-light border-secondary" placeholder="Search by name, username, email, or role...">
                        </div>
                    </div>
                    <div class="col-auto text-muted small" id="adminSearchCount">
                        Total Users: <strong class="text-light"><?= count($users) ?></strong>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="admin-card p-4">
                <div class="table-responsive">
                    <table class="table table-admin align-middle admin-data-table mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">ID</th>
                                <th>User</th>
                                <th>Email Address</th>
                                <th>Role</th>
                                <th>Registered Date</th>
                                <th class="text-end" style="width: 200px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <?php $isSelf = ($u['id'] === $currentAdminId); ?>
                                <tr>
                                    <td class="text-light-emphasis small fw-semibold">#<?= $u['id'] ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="user-avatar" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                <?= strtoupper(substr($u['full_name'], 0, 1)) ?>
                                            </div>
                                            <div>
                                                <div class="text-white fw-bold">
                                                    <?= htmlspecialchars($u['full_name']) ?>
                                                    <?php if ($isSelf): ?>
                                                        <span class="badge bg-secondary text-white ms-1" style="font-size: 0.65rem;">You</span>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="text-light-emphasis small fw-medium">@<?= htmlspecialchars($u['username']) ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-white small fw-medium"><?= htmlspecialchars($u['email']) ?></span>
                                    </td>
                                    <td>
                                        <span class="badge <?= $u['role'] === 'admin' ? 'bg-warning text-dark fw-bold' : 'bg-primary' ?> text-uppercase px-2 py-1">
                                            <?= htmlspecialchars($u['role']) ?>
                                        </span>
                                    </td>
                                    <td class="text-light-emphasis small">
                                        <?= date('M d, Y h:i A', strtotime($u['created_at'])) ?>
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-1">
                                            <!-- Toggle Role Form -->
                                            <form action="users.php" method="POST" class="d-inline" onsubmit="return confirm('Change role for @<?= htmlspecialchars($u['username']) ?> to <?= $u['role'] === 'admin' ? 'USER' : 'ADMIN' ?>?');">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="action" value="toggle_role">
                                                <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                                <button type="submit" 
                                                        class="btn btn-outline-warning btn-sm" 
                                                        title="Switch Role" 
                                                        <?= ($isSelf && $u['role'] === 'admin') ? 'disabled' : '' ?>>
                                                    <i class="bi bi-shield-shaded me-1"></i>
                                                    <span><?= $u['role'] === 'admin' ? 'Make User' : 'Make Admin' ?></span>
                                                </button>
                                            </form>

                                            <!-- Delete User Form -->
                                            <form action="users.php" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to permanently delete user @<?= htmlspecialchars($u['username']) ?>?');">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="action" value="delete_user">
                                                <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                                                <button type="submit" 
                                                        class="btn btn-outline-danger btn-sm" 
                                                        title="Delete User"
                                                        <?= $isSelf ? 'disabled' : '' ?>>
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
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

