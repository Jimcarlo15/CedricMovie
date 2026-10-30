<?php
// partials/player_modal.php - Not Playable Yet Dialog
$basePath = isset($isSubdir) && $isSubdir ? '../' : '';
?>
<div class="modal fade" id="playerModal" tabindex="-1" aria-labelledby="playerMovieTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-dark text-light border border-secondary shadow-lg rounded-4 overflow-hidden">
            <!-- Modal Header -->
            <div class="modal-header border-secondary-subtle py-3 px-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-danger text-uppercase px-2 py-1" style="font-size: 0.7rem;">Catalog Only</span>
                    <h5 class="modal-title h6 fw-bold mb-0 text-white" id="playerMovieTitle">Movie Player Preview</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Stage: Fake Player -->
            <div class="fake-player-stage position-relative bg-black" style="aspect-ratio: 16/9; overflow:hidden;">
                <img id="playerBackdrop" class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover" src="" alt="Backdrop" style="filter: brightness(0.25) blur(2px);">
                
                <!-- Notice Centerpiece -->
                <div class="position-absolute top-50 start-50 translate-middle text-center p-4 w-100" style="max-width: 500px; z-index: 5;">
                    <div class="lock-ring mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle border border-danger border-2 text-danger" style="width: 72px; height: 72px; background: rgba(239, 68, 68, 0.15);">
                        <i class="bi bi-lock-fill display-6"></i>
                    </div>
                    <span class="badge bg-danger rounded-pill px-3 py-1 mb-2 text-uppercase fw-bold" style="letter-spacing: 0.5px;">
                        Not Playable Yet
                    </span>
                    <h4 class="h5 fw-bold text-white mb-2">Video Streaming Unavailable</h4>
                    <p class="text-light-emphasis small mb-0" style="line-height: 1.6;">
                        <strong>CedCine</strong> is an informational movie catalog and rating platform. Full-length video playback and streaming capabilities are <strong>not playable yet</strong>.
                    </p>
                </div>

                <!-- Fake Player Bottom Bar -->
                <div class="fake-controls position-absolute bottom-0 start-0 end-0 p-3 d-flex align-items-center justify-content-between" style="background: linear-gradient(0deg, rgba(0,0,0,0.95) 0%, transparent 100%); z-index: 6;">
                    <div class="fake-timeline position-absolute top-0 start-0 end-0" style="height: 4px; background: rgba(255,255,255,0.2);">
                        <div class="fake-progress bg-danger" style="width: 0%; height: 100%;"></div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-white-50 small" title="Playback Disabled" style="cursor: not-allowed;">
                            <i class="bi bi-play-fill fs-5"></i>
                        </span>
                        <span class="text-secondary small font-monospace">00:00 / --:--</span>
                    </div>
                    <div class="d-flex align-items-center gap-3 text-secondary">
                        <i class="bi bi-volume-mute-fill" title="Audio disabled"></i>
                        <i class="bi bi-badge-hd-fill" title="HD unavailable"></i>
                        <i class="bi bi-fullscreen" title="Fullscreen unavailable"></i>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer border-secondary-subtle justify-content-between py-2 px-4">
                <span class="text-light-emphasis small">
                    Platform Status: <strong class="text-info fw-bold">Information &amp; Rating System</strong>
                </span>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-light btn-sm" data-bs-dismiss="modal">Close</button>
                    <a id="playerDetailLink" href="#" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1">
                        <span>View Movie Details</span>
                        <i class="bi bi-arrow-right small"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
