<?php
// partials/empty_state.php - Rendered when no movies match criteria
// Expects: optional $message
$basePath = isset($isSubdir) && $isSubdir ? '../' : '';
?>
<div class="empty-state-card text-center p-5 my-4 rounded-4 border border-secondary">
    <div class="empty-state-icon mb-3">
        <i class="bi bi-film text-danger opacity-75 display-3"></i>
    </div>
    <h3 class="h4 text-white fw-bold mb-2">No Movies Found</h3>
    <p class="text-light-emphasis mb-4 mx-auto" style="max-width: 480px; font-size: 1rem; line-height: 1.6;">
        <?= htmlspecialchars($message ?? 'Try selecting a different genre, adjusting your search term, or browsing our full movie list.') ?>
    </p>
    <a href="<?= $basePath ?>index.php" class="btn btn-outline-light btn-sm px-4 btn-reset-filters">
        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Filters
    </a>
</div>
