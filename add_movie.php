<?php
// add_movie.php - Handles POST request to insert new movie into SQLite
require_once __DIR__ . '/config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $year = intval($_POST['year'] ?? date('Y'));
    $duration = trim($_POST['duration'] ?? '2h 00m');
    $rating = floatval($_POST['rating'] ?? 0);
    $director = trim($_POST['director'] ?? '');
    $actors = trim($_POST['actors'] ?? '');
    $poster = trim($_POST['poster'] ?? '');
    $backdrop = trim($_POST['backdrop'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (empty($backdrop)) {
        $backdrop = $poster;
    }

    if (!empty($title) && !empty($genre) && !empty($poster) && !empty($description)) {
        $stmt = $pdo->prepare("
            INSERT INTO movies (title, genre, year, duration, rating, poster, backdrop, description, director, actors, is_playable, featured)
            VALUES (:title, :genre, :year, :duration, :rating, :poster, :backdrop, :description, :director, :actors, 0, 0)
        ");
        $stmt->execute([
            ':title' => $title,
            ':genre' => $genre,
            ':year' => $year,
            ':duration' => $duration,
            ':rating' => $rating,
            ':poster' => $poster,
            ':backdrop' => $backdrop,
            ':description' => $description,
            ':director' => $director,
            ':actors' => $actors
        ]);
    }
}

header("Location: index.php");
exit;

