<?php
// login.php - User & Admin Login
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/helpers/auth.php';
require_once __DIR__ . '/helpers/partial.php';

// If already logged in, redirect accordingly
if (is_logged_in()) {
    if (is_admin()) {
        header("Location: admin/index.php");
    } else {
        header("Location: index.php");
    }
    exit;
}

$pageTitle = 'Log In - CedCine';
$error = '';
$identity = '';
$userCount = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identity = trim($_POST['identity'] ?? '');
    $password = $_POST['password'] ?? '';
    $csrfToken = $_POST['csrf_token'] ?? '';

    if (!verify_csrf_token($csrfToken)) {
        $error = 'Invalid security session. Please try again.';
    } elseif (empty($identity) || empty($password)) {
        $error = 'Please enter your username/email and password.';
    } else {
        // Query user by username or email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username OR email = :email LIMIT 1");
        $stmt->execute([':username' => $identity, ':email' => $identity]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            // Regenerate session ID for session fixation security
            session_regenerate_id(true);

            // Store user in session (excluding password)
            unset($user['password']);
            $_SESSION['user'] = $user;

            set_flash('success', 'Welcome back, ' . htmlspecialchars($user['full_name']) . '!');

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = 'Invalid credentials. Please verify your username/email and password.';
        }
    }
}

render_partial('header', [
    'pageTitle' => $pageTitle,
    'extraCss'  => ['assets/css/auth.css']
]);
render_partial('navbar');
?>

<div class="auth-wrapper">
    <div class="auth-card">
        <!-- Auth Header -->
        <div class="auth-header">
            <a href="index.php" class="d-inline-flex align-items-center gap-2 text-decoration-none mb-2">
                <span class="brand-icon">
                    <i class="bi bi-film"></i>
                </span>
                <span class="h4 fw-bold text-white mb-0">Ced<span class="brand-accent">Cine</span></span>
            </a>
            <h2 class="h5 fw-bold text-white mb-1">Welcome Back</h2>
            <p class="text-muted small mb-0">Sign in to access your ratings and favorites</p>
        </div>

        <!-- Auth Body -->
        <div class="auth-body">
            <?php if ($userCount === 0): ?>
                <div class="alert alert-info py-2 px-3 small d-flex align-items-center gap-2 mb-3 rounded-3" role="alert">
                    <i class="bi bi-info-circle-fill text-info fs-5"></i>
                    <div>
                        No accounts registered yet. <a href="register.php" class="text-white fw-bold text-decoration-underline">Register now</a> to create the first account (initialized as Administrator).
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger py-2 px-3 small d-flex align-items-center gap-2 mb-3 rounded-3" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <div><?= htmlspecialchars($error) ?></div>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST" novalidate>
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="identity" class="form-label text-light-emphasis small fw-semibold">Username or Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-secondary text-muted">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="text" 
                               class="form-control form-control-auth border-start-0" 
                               id="identity" 
                               name="identity" 
                               placeholder="Enter username or email" 
                               value="<?= htmlspecialchars($identity) ?>" 
                               required 
                               autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <label for="password" class="form-label text-light-emphasis small fw-semibold">Password</label>
                    </div>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-secondary text-muted">
                            <i class="bi bi-key"></i>
                        </span>
                        <input type="password" 
                               class="form-control form-control-auth border-start-0 border-end-0" 
                               id="password" 
                               name="password" 
                               placeholder="••••••••" 
                               required>
                        <button class="btn password-toggle-btn" type="button" id="togglePasswordBtn" title="Show/Hide password">
                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-box-arrow-in-right"></i>
                        <span>Log In</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Auth Footer -->
        <div class="auth-footer text-muted small">
            Don't have an account yet? <a href="register.php" class="text-primary fw-semibold text-decoration-none">Create an account</a>
        </div>
    </div>
</div>

<script>
// Toggle Password Visibility
document.getElementById('togglePasswordBtn')?.addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const icon = document.getElementById('togglePasswordIcon');
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        passwordInput.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
});
</script>

<?php render_partial('footer'); ?>

