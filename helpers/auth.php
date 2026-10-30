<?php
// helpers/auth.php - Authentication & Authorization Helpers

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Check if the user is logged in
 */
function is_logged_in(): bool {
    return isset($_SESSION['user']) && !empty($_SESSION['user']['id']);
}

/**
 * Get current logged in user details
 */
function current_user(): ?array {
    return $_SESSION['user'] ?? null;
}

/**
 * Check if the current user has the 'admin' role
 */
function is_admin(): bool {
    return is_logged_in() && (($_SESSION['user']['role'] ?? '') === 'admin');
}

/**
 * Enforce that the user must be logged in to view the page
 */
function require_login(string $redirect = 'login.php'): void {
    if (!is_logged_in()) {
        set_flash('danger', 'Please log in to access this page.');
        header("Location: {$redirect}");
        exit;
    }
}

/**
 * Enforce that the user must be an administrator
 */
function require_admin(string $redirect = '../login.php'): void {
    if (!is_logged_in()) {
        set_flash('danger', 'Administrator login required.');
        header("Location: {$redirect}");
        exit;
    }

    if (!is_admin()) {
        set_flash('danger', 'Access denied. You do not have administrator permissions.');
        header("Location: ../index.php");
        exit;
    }
}

/**
 * Generate or get existing CSRF token
 */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Output a hidden input field containing the CSRF token
 */
function csrf_field(): string {
    $token = htmlspecialchars(csrf_token());
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

/**
 * Verify submitted CSRF token
 */
function verify_csrf_token(?string $token): bool {
    if (empty($token) || empty($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Set a flash notification message in session
 */
function set_flash(string $type, string $message): void {
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }
    $_SESSION['flash'][$type] = $message;
}

/**
 * Retrieve and clear a flash message
 */
function get_flash(string $type): ?string {
    if (isset($_SESSION['flash'][$type])) {
        $msg = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $msg;
    }
    return null;
}

/**
 * Check if a flash message exists
 */
function has_flash(string $type): bool {
    return isset($_SESSION['flash'][$type]);
}

