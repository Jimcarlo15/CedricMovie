<?php
// partials/empty_state.php - Rendered when no movies match criteria
// Expects: optional $message
?>
<div class="empty-state-card" style="text-align: center; padding: 4rem 1.5rem; background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border-color); margin: 1rem 0;">
    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--text-dim)" stroke-width="1.5" style="margin-bottom: 1rem;">
        <circle cx="12" cy="12" r="10"></circle>
        <line x1="8" y1="12" x2="16" y2="12"></line>
    </svg>
    <h3 style="color: #fff; margin-bottom: 0.5rem;">No Movies Found</h3>
    <p style="color: var(--text-muted); margin-bottom: 1.5rem; max-width: 460px; margin-left: auto; margin-right: auto;">
        <?= htmlspecialchars($message ?? 'Try selecting a different genre, adjusting your search term, or add a new movie to the catalog.') ?>
    </p>
    <a href="index.php" class="btn btn-secondary btn-reset-filters">Reset Filters</a>
</div>

