<?php
// partials/filter_bar.php
$selectedGenre = $_GET['genre'] ?? 'all';
$selectedSort = $_GET['sort'] ?? 'rating_desc';

$genres = ['all' => 'All Genres', 'Sci-Fi' => 'Sci-Fi', 'Action' => 'Action', 'Crime' => 'Crime', 'Drama' => 'Drama', 'Animation' => 'Animation', 'Fantasy' => 'Fantasy', 'Adventure' => 'Adventure'];
?>
<div class="filter-bar">
    <div class="genre-pills">
        <?php foreach ($genres as $key => $label): ?>
            <?php 
                $isActive = ($selectedGenre === $key) || ($key === 'all' && empty($_GET['genre']));
                $genreUrl = 'index.php?' . http_build_query(array_merge($_GET, ['genre' => $key]));
            ?>
            <a href="<?= htmlspecialchars($genreUrl) ?>" class="genre-pill <?= $isActive ? 'active' : '' ?>">
                <?= htmlspecialchars($label) ?>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="filter-sort">
        <label for="sortSelect">Sort By:</label>
        <select id="sortSelect">
            <option value="rating_desc" <?= $selectedSort === 'rating_desc' ? 'selected' : '' ?>>⭐ Highest Rating</option>
            <option value="rating_asc" <?= $selectedSort === 'rating_asc' ? 'selected' : '' ?>>Lowest Rating</option>
            <option value="year_desc" <?= $selectedSort === 'year_desc' ? 'selected' : '' ?>>📅 Newest Year</option>
            <option value="year_asc" <?= $selectedSort === 'year_asc' ? 'selected' : '' ?>>Oldest Year</option>
            <option value="title_asc" <?= $selectedSort === 'title_asc' ? 'selected' : '' ?>>🔤 Title A-Z</option>
        </select>
    </div>
</div>

