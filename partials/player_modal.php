<?php
// partials/player_modal.php
?>
<div id="playerModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="playerMovieTitle">
    <div class="player-modal">
        <div class="modal-header">
            <h3 id="playerMovieTitle">Movie Player Preview</h3>
            <button id="playerModalClose" class="modal-close" aria-label="Close modal">&times;</button>
        </div>

        <div class="fake-player-stage">
            <img id="playerBackdrop" class="stage-backdrop" src="" alt="Backdrop">
            
            <div class="player-overlay-message">
                <div class="lock-ring">
                    <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                </div>
                <div class="player-status-tag">Not Playable Yet</div>
                <h4>Playback is not enabled</h4>
                <p>This movie is listed for browsing, ratings, and synopsis exploration. Media streaming and playback capabilities are not supported at this stage.</p>
            </div>

            <!-- Fake Player Controls Bar -->
            <div class="fake-controls">
                <div class="fake-timeline">
                    <div class="fake-progress"></div>
                </div>
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <span class="fake-btn" title="Disabled">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                        </svg>
                    </span>
                    <span style="font-size:0.75rem; color:var(--text-dim);">00:00 / --:--</span>
                </div>
                <div style="display:flex; align-items:center; gap:0.75rem;">
                    <span class="fake-btn" title="Audio disabled">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon>
                            <line x1="23" y1="9" x2="17" y2="15"></line>
                            <line x1="17" y1="9" x2="23" y2="15"></line>
                        </svg>
                    </span>
                    <span class="fake-btn" title="Fullscreen disabled">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M8 3H5a2 2 0 0 0-2 2v3m18 0V5a2 2 0 0 0-2-2h-3m0 18h3a2 2 0 0 0 2-2v-3M3 16v3a2 2 0 0 0 2 2h3"></path>
                        </svg>
                    </span>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <span style="font-size:0.85rem; color:var(--text-dim);">
                Status: <strong>Catalog Only</strong>
            </span>
            <div style="display:flex; gap:0.75rem;">
                <a id="playerDetailLink" href="#" class="btn btn-primary btn-sm">Read Full Description &amp; Cast</a>
            </div>
        </div>
    </div>
</div>

