<?php
// api/movies.php - JSON API endpoint for movies
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/db.php';

try {
    // If specific ID is requested
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM movies WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $movie = $stmt->fetch();

        if ($movie) {
            echo json_encode(['success' => true, 'data' => $movie], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Movie not found'], JSON_PRETTY_PRINT);
        }
        exit;
    }

    // Filters and search
    $genre = $_GET['genre'] ?? 'all';
    $sort = $_GET['sort'] ?? 'rating_desc';
    $search = trim($_GET['q'] ?? '');

    $query = "SELECT * FROM movies WHERE 1=1";
    $params = [];

    if ($genre !== 'all' && !empty($genre)) {
        $query .= " AND genre LIKE :genre";
        $params[':genre'] = '%' . $genre . '%';
    }

    if (!empty($search)) {
        $query .= " AND (title LIKE :search OR description LIKE :search OR cast LIKE :search OR director LIKE :search)";
        $params[':search'] = '%' . $search . '%';
    }

    switch ($sort) {
        case 'rating_asc':
            $query .= " ORDER BY rating ASC";
            break;
        case 'year_desc':
            $query .= " ORDER BY release_year DESC";
            break;
        case 'year_asc':
            $query .= " ORDER BY release_year ASC";
            break;
        case 'title_asc':
            $query .= " ORDER BY title ASC";
            break;
        case 'title_desc':
            $query .= " ORDER BY title DESC";
            break;
        case 'rating_desc':
        default:
            $query .= " ORDER BY rating DESC";
            break;
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $movies = $stmt->fetchAll();

    echo json_encode([
        'success' => true,
        'count'   => count($movies),
        'data'    => $movies
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server Error: ' . $e->getMessage()]);
}

