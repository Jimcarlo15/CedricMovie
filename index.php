<?php
// index.php - Movie Catalog Home (Modular Partials)
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/helpers/partial.php';

$pageTitle = 'CedricMovie - Discover Films, Ratings & Details';

// Handle Filter, Search & Sort
$genreFilter = $_GET['genre'] ?? 'all';
$sortOption = $_GET['sort'] ?? 'rating_desc';
$searchQuery = trim($_GET['q'] ?? '');

$query = "SELECT * FROM movies WHERE 1=1";
$params = [];

if ($genreFilter !== 'all' && !empty($genreFilter)) {
    $query .= " AND genre LIKE :genre";
    $params[':genre'] = '%' . $genreFilter . '%';
}

if (!empty($searchQuery)) {
    $query .= " AND (title LIKE :search OR description LIKE :search OR actors LIKE :search OR director LIKE :search)";
    $params[':search'] = '%' . $searchQuery . '%';
}

switch ($sortOption) {
    case 'rating_asc':
        $query .= " ORDER BY rating ASC";
        break;
    case 'year_desc':
        $query .= " ORDER BY year DESC";
        break;
    case 'year_asc':
        $query .= " ORDER BY year ASC";
        break;
    case 'title_asc':
        $query .= " ORDER BY title ASC";
        break;
    case 'rating_desc':
    default:
        $query .= " ORDER BY rating DESC";
        break;
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$movies = $stmt->fetchAll();

// If this is an AJAX partial request, return only the movie_grid partial
if (isset($_GET['partial']) && $_GET['partial'] === 'movies') {
    render_partial('movie_grid', ['movies' => $movies]);
    exit;
}

// Get featured movie for the hero banner partial
$featuredStmt = $pdo->query("SELECT * FROM movies WHERE featured = 1 LIMIT 1");
$featuredMovie = $featuredStmt->fetch();
if (!$featuredMovie && count($movies) > 0) {
    $featuredMovie = $movies[0];
}

// Render Modular Partials
render_partial('header', ['pageTitle' => $pageTitle]);
render_partial('navbar');
render_partial('hero', ['featuredMovie' => $featuredMovie]);
?>

<main class="main-container">
    <!-- Filter Bar Partial -->
    <?php render_partial('filter_bar'); ?>

    <!-- Movie Grid Partial Container -->
    <div id="moviesContainer">
        <?php render_partial('movie_grid', ['movies' => $movies]); ?>
    </div>
</main>

<?php
render_partial('player_modal');
render_partial('add_modal');
render_partial('footer');
?>
