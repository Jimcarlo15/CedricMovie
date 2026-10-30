<?php
// logout.php - Session Termination
require_once __DIR__ . '/helpers/auth.php';

// Unset user data
if (isset($_SESSION['user'])) {
    unset($_SESSION['user']);
}

// Destroy session cookies
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();

// Start fresh session to pass the flash message
session_start();
set_flash('info', 'You have been logged out successfully.');

header("Location: login.php");
exit;

