<?php
// config/db.php - MySQL/MariaDB Database Connection & Auto-Setup

$dbHost = '127.0.0.1';
$dbPort = '3306';
$dbName = 'cedcine_db';
$dbUser = 'root';
$dbPass = '';

try {
    // Attempt connecting to the specific database
    $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    // If the database does not exist yet (code 1049), attempt auto-creation from cedcine.sql
    if ($e->getCode() == 1049) {
        try {
            $rootPdo = new PDO("mysql:host={$dbHost};port={$dbPort};charset=utf8mb4", $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ]);
            $sqlFile = __DIR__ . '/../database/cedcine.sql';
            if (file_exists($sqlFile)) {
                $sql = file_get_contents($sqlFile);
                $rootPdo->exec($sql);
            } else {
                $rootPdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            }
            // Reconnect
            $pdo = new PDO("mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4", $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $ex) {
            die("<div style='background:#182030; color:#ef4444; padding:2rem; font-family:sans-serif; text-align:center;'>
                <h2>Database Connection Error</h2>
                <p>" . htmlspecialchars($ex->getMessage()) . "</p>
                <p style='color:#94a3b8;'>Please ensure MySQL is running in your XAMPP Control Panel and <code>cedcine_db</code> is created.</p>
            </div>");
        }
    } else {
        die("<div style='background:#182030; color:#ef4444; padding:2rem; font-family:sans-serif; text-align:center;'>
            <h2>Database Connection Error</h2>
            <p>" . htmlspecialchars($e->getMessage()) . "</p>
            <p style='color:#94a3b8;'>Please ensure MySQL is running in your XAMPP Control Panel.</p>
        </div>");
    }
}
