<?php
// partials/add_modal.php
?>
<div id="addMovieModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="addMovieTitle">
    <div class="player-modal" style="max-width: 650px;">
        <div class="modal-header">
            <h3 id="addMovieTitle">Add New Movie to SQLite</h3>
            <button id="closeAddMovieBtn" class="modal-close" aria-label="Close modal">&times;</button>
        </div>

        <form action="add_movie.php" method="POST" style="padding: 1.5rem;">
            <div class="form-row">
                <div class="form-group">
                    <label for="inputTitle">Movie Title *</label>
                    <input type="text" id="inputTitle" name="title" placeholder="e.g. The Matrix" required>
                </div>
                <div class="form-group">
                    <label for="inputGenre">Genre *</label>
                    <input type="text" id="inputGenre" name="genre" placeholder="e.g. Sci-Fi / Action" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="inputYear">Release Year *</label>
                    <input type="number" id="inputYear" name="year" min="1900" max="2099" value="<?= date('Y') ?>" required>
                </div>
                <div class="form-group">
                    <label for="inputDuration">Duration *</label>
                    <input type="text" id="inputDuration" name="duration" placeholder="e.g. 2h 16m" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="inputRating">Rating (0 - 10) *</label>
                    <input type="number" step="0.1" min="0" max="10" id="inputRating" name="rating" placeholder="8.5" required>
                </div>
                <div class="form-group">
                    <label for="inputDirector">Director *</label>
                    <input type="text" id="inputDirector" name="director" placeholder="e.g. Lana Wachowski" required>
                </div>
            </div>

            <div class="form-group">
                <label for="inputActors">Key Cast / Actors</label>
                <input type="text" id="inputActors" name="actors" placeholder="e.g. Keanu Reeves, Laurence Fishburne">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="inputPoster">Poster Image URL *</label>
                    <input type="url" id="inputPoster" name="poster" placeholder="https://..." required>
                </div>
                <div class="form-group">
                    <label for="inputBackdrop">Backdrop Banner URL</label>
                    <input type="url" id="inputBackdrop" name="backdrop" placeholder="https://...">
                </div>
            </div>

            <div class="form-group">
                <label for="inputDescription">Movie Description / Synopsis *</label>
                <textarea id="inputDescription" name="description" rows="3" placeholder="Enter movie synopsis..." required></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1rem;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('addMovieModal').classList.remove('active')">Cancel</button>
                <button type="submit" class="btn btn-primary">Save to Database</button>
            </div>
        </form>
    </div>
</div>

