<?php
// api/favorite.php - JSON API endpoint to toggle movie favorites
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../helpers/auth.php';

if (!is_logged_in()) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Please log in to manage favorites.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$movieId = isset($input['movie_id']) ? (int)$input['movie_id'] : (int)($_POST['movie_id'] ?? 0);

if ($movieId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid movie ID.']);
    exit;
}

$userId = (int)$_SESSION['user']['id'];

try {
    // Check if movie exists
    $checkMovie = $pdo->prepare("SELECT id FROM movies WHERE id = :mid");
    $checkMovie->execute([':mid' => $movieId]);
    if (!$checkMovie->fetch()) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Movie does not exist.']);
        exit;
    }

    // Check if already favorited
    $checkFav = $pdo->prepare("SELECT id FROM favorites WHERE user_id = :uid AND movie_id = :mid");
    $checkFav->execute([':uid' => $userId, ':mid' => $movieId]);
    $existing = $checkFav->fetch();

    if ($existing) {
        // Remove from favorites
        $del = $pdo->prepare("DELETE FROM favorites WHERE id = :fid");
        $del->execute([':fid' => $existing['id']]);
        $status = 'removed';
    } else {
        // Add to favorites
        $ins = $pdo->prepare("INSERT INTO favorites (user_id, movie_id, created_at) VALUES (:uid, :mid, NOW())");
        $ins->execute([':uid' => $userId, ':mid' => $movieId]);
        $status = 'added';
    }

    // Get updated total favorite count for the user
    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM favorites WHERE user_id = :uid");
    $countStmt->execute([':uid' => $userId]);
    $totalFavs = (int)$countStmt->fetchColumn();

    echo json_encode([
        'success'         => true,
        'status'          => $status,
        'movie_id'        => $movieId,
        'total_favorites' => $totalFavs
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}

