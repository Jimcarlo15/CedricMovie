<?php
// add_movie.php - Root compatibility redirect to Admin Movie Creation
require_once __DIR__ . '/helpers/auth.php';

if (!is_logged_in() || !is_admin()) {
    header("Location: login.php");
    exit;
}

header("Location: admin/add_movie.php");
exit;
