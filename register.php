<?php
// register.php - User Registration
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/helpers/auth.php';
require_once __DIR__ . '/helpers/partial.php';

// If already logged in, redirect
if (is_logged_in()) {
    header("Location: index.php");
    exit;
}

$pageTitle = 'Register - CedCine';
$errors = [];
$fullName = '';
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $csrfToken = $_POST['csrf_token'] ?? '';

    // CSRF verification
    if (!verify_csrf_token($csrfToken)) {
        $errors[] = 'Invalid security session. Please submit the form again.';
    }

    // Validation
    if (empty($fullName)) {
        $errors[] = 'Full name is required.';
    }

    if (empty($username)) {
        $errors[] = 'Username is required.';
    } elseif (!preg_match('/^[a-zA-Z0-9_]{3,30}$/', $username)) {
        $errors[] = 'Username must be 3-30 characters and contain only letters, numbers, or underscores.';
    }

    if (empty($email)) {
        $errors[] = 'Email address is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Please enter a valid email address.';
    }

    if (empty($password)) {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters long.';
    }

    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }

    // Check for duplicate username or email
    if (empty($errors)) {
        $checkStmt = $pdo->prepare("SELECT id, username, email FROM users WHERE username = :u OR email = :e LIMIT 1");
        $checkStmt->execute([':u' => $username, ':e' => $email]);
        $existing = $checkStmt->fetch();

        if ($existing) {
            if (strcasecmp($existing['username'], $username) === 0) {
                $errors[] = 'The username "' . htmlspecialchars($username) . '" is already taken.';
            }
            if (strcasecmp($existing['email'], $email) === 0) {
                $errors[] = 'An account with this email address already exists.';
            }
        }
    }

    // Insert user if no errors
    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // The very first registered user in the database becomes 'admin'.
        // All subsequent registrations strictly default to 'user'.
        $totalUsers = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $assignedRole = ($totalUsers === 0) ? 'admin' : 'user';

        $stmt = $pdo->prepare("
            INSERT INTO users (full_name, username, email, password, role, created_at)
            VALUES (:fname, :uname, :email, :pwd, :role, NOW())
        ");
        
        $stmt->execute([
            ':fname' => $fullName,
            ':uname' => $username,
            ':email' => $email,
            ':pwd'   => $hashedPassword,
            ':role'  => $assignedRole
        ]);

        $newUserId = (int)$pdo->lastInsertId();

        // Auto login
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id'        => $newUserId,
            'full_name' => $fullName,
            'username'  => $username,
            'email'     => $email,
            'role'      => $assignedRole
        ];

        if ($assignedRole === 'admin') {
            set_flash('success', 'Registration successful! As the first user, your account has been initialized as System Administrator.');
            header("Location: admin/index.php");
        } else {
            set_flash('success', 'Registration successful! Welcome to CedCine, ' . htmlspecialchars($fullName) . '.');
            header("Location: index.php");
        }
        exit;
    }
}

$isFirstUser = ((int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn() === 0);

render_partial('header', [
    'pageTitle' => $pageTitle,
    'extraCss'  => ['assets/css/auth.css']
]);
render_partial('navbar');
?>

<div class="auth-wrapper">
    <div class="auth-card" style="max-width: 520px;">
        <!-- Auth Header -->
        <div class="auth-header">
            <a href="index.php" class="d-inline-flex align-items-center gap-2 text-decoration-none mb-2">
                <span class="brand-icon">
                    <i class="bi bi-film"></i>
                </span>
                <span class="h4 fw-bold text-white mb-0">Ced<span class="brand-accent">Cine</span></span>
            </a>
            <h2 class="h5 fw-bold text-white mb-1">Create an Account</h2>
            <p class="text-muted small mb-0">Join CedCine to save favorite movies and track ratings</p>
        </div>

        <!-- Auth Body -->
        <div class="auth-body">
            <?php if ($isFirstUser): ?>
                <div class="alert alert-info py-2 px-3 small mb-3 rounded-3 d-flex align-items-center gap-2">
                    <i class="bi bi-shield-lock-fill text-warning fs-5"></i>
                    <div>
                        <strong>Initial Setup:</strong> As the first registered user, this account will be initialized as the <strong>System Administrator</strong>.
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger py-2 px-3 small mb-3 rounded-3" role="alert">
                    <div class="d-flex align-items-center gap-2 mb-1 fw-bold">
                        <i class="bi bi-exclamation-triangle-fill"></i> Please correct the following:
                    </div>
                    <ul class="mb-0 ps-3">
                        <?php foreach ($errors as $err): ?>
                            <li><?= htmlspecialchars($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST" novalidate>
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="full_name" class="form-label text-light-emphasis small fw-semibold">Full Name *</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-secondary text-muted">
                            <i class="bi bi-card-heading"></i>
                        </span>
                        <input type="text" 
                               class="form-control form-control-auth border-start-0" 
                               id="full_name" 
                               name="full_name" 
                               placeholder="e.g. Cedric Garcia" 
                               value="<?= htmlspecialchars($fullName) ?>" 
                               required>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-12 col-sm-6">
                        <label for="username" class="form-label text-light-emphasis small fw-semibold">Username *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-secondary text-muted">
                                <i class="bi bi-person"></i>
                            </span>
                            <input type="text" 
                                   class="form-control form-control-auth border-start-0" 
                                   id="username" 
                                   name="username" 
                                   placeholder="e.g. cedric12" 
                                   value="<?= htmlspecialchars($username) ?>" 
                                   required>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label for="email" class="form-label text-light-emphasis small fw-semibold">Email Address *</label>
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-secondary text-muted">
                                <i class="bi bi-envelope"></i>
                            </span>
                            <input type="email" 
                                   class="form-control form-control-auth border-start-0" 
                                   id="email" 
                                   name="email" 
                                   placeholder="name@example.com" 
                                   value="<?= htmlspecialchars($email) ?>" 
                                   required>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-12 col-sm-6">
                        <label for="password" class="form-label text-light-emphasis small fw-semibold">Password *</label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control form-control-auth border-end-0" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Min 6 chars" 
                                   required>
                            <button class="btn password-toggle-btn" type="button" onclick="toggleField('password', 'pwdIcon1')">
                                <i class="bi bi-eye" id="pwdIcon1"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-12 col-sm-6">
                        <label for="confirm_password" class="form-label text-light-emphasis small fw-semibold">Confirm Password *</label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control form-control-auth border-end-0" 
                                   id="confirm_password" 
                                   name="confirm_password" 
                                   placeholder="Repeat password" 
                                   required>
                            <button class="btn password-toggle-btn" type="button" onclick="toggleField('confirm_password', 'pwdIcon2')">
                                <i class="bi bi-eye" id="pwdIcon2"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="d-grid mt-4">
                    <button type="submit" class="btn btn-primary btn-lg d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-person-check-fill"></i>
                        <span>Create Account</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Auth Footer -->
        <div class="auth-footer text-muted small">
            Already have an account? <a href="login.php" class="text-primary fw-semibold text-decoration-none">Sign In</a>
        </div>
    </div>
</div>

<script>
function toggleField(fieldId, iconId) {
    const input = document.getElementById(fieldId);
    const icon = document.getElementById(iconId);
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>

<?php render_partial('footer'); ?>

