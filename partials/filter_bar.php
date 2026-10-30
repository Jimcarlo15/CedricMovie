<?php
// partials/filter_bar.php - Genre Filter and Sort Options
$selectedGenre = $_GET['genre'] ?? 'all';
$selectedSort = $_GET['sort'] ?? 'rating_desc';

$genres = [
    'all'       => 'All Genres',
    'Action'    => 'Action',
    'Sci-Fi'    => 'Sci-Fi',
    'Crime'     => 'Crime',
    'Drama'     => 'Drama',
    'Animation' => 'Animation',
    'Fantasy'   => 'Fantasy',
    'Adventure' => 'Adventure',
    'Thriller'  => 'Thriller',
    'Mystery'   => 'Mystery'
];
?>
<div class="filter-bar p-3 mb-4 rounded-4 shadow-sm" id="filterBarSection">
    <div class="row g-3 align-items-center justify-content-between">
        <!-- Genre Pills -->
        <div class="col-12 col-lg-8">
            <div class="d-flex flex-wrap gap-2 genre-pills-list">
                <?php foreach ($genres as $key => $label): ?>
                    <?php 
                        $isActive = ($selectedGenre === $key) || ($key === 'all' && empty($_GET['genre']));
                        $genreUrl = 'index.php?' . http_build_query(array_merge($_GET, ['genre' => $key]));
                    ?>
                    <a href="<?= htmlspecialchars($genreUrl) ?>" 
                       class="genre-pill <?= $isActive ? 'active' : '' ?>"
                       data-genre="<?= htmlspecialchars($key) ?>">
                        <?= htmlspecialchars($label) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Sort Select -->
        <div class="col-12 col-lg-4 d-flex align-items-center justify-content-lg-end gap-2">
            <label for="sortSelect" class="text-white small fw-bold text-nowrap mb-0">
                <i class="bi bi-arrow-down-up me-1 text-danger"></i> Sort:
            </label>
            <select id="sortSelect" class="form-select form-select-sm bg-dark text-white fw-medium border-secondary" style="max-width: 200px;">
                <option value="rating_desc" <?= $selectedSort === 'rating_desc' ? 'selected' : '' ?>>⭐ Highest Rating</option>
                <option value="rating_asc" <?= $selectedSort === 'rating_asc' ? 'selected' : '' ?>>⭐ Lowest Rating</option>
                <option value="year_desc" <?= $selectedSort === 'year_desc' ? 'selected' : '' ?>>📅 Newest Year</option>
                <option value="year_asc" <?= $selectedSort === 'year_asc' ? 'selected' : '' ?>>📅 Oldest Year</option>
                <option value="title_asc" <?= $selectedSort === 'title_asc' ? 'selected' : '' ?>>🔤 Title A-Z</option>
                <option value="title_desc" <?= $selectedSort === 'title_desc' ? 'selected' : '' ?>>🔤 Title Z-A</option>
            </select>
        </div>
    </div>
</div>
