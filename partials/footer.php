<?php
// partials/footer.php
$basePath = isset($isSubdir) && $isSubdir ? '../' : '';
$currentYear = date('Y');
?>
    <footer class="site-footer mt-auto py-5 border-top border-secondary-subtle">
        <div class="container-xl">
            <div class="row g-4 justify-content-between align-items-center">
                <!-- Brand Info -->
                <div class="col-12 col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="brand-icon small">
                            <i class="bi bi-film"></i>
                        </span>
                        <span class="h5 fw-bold text-white mb-0">Ced<span class="text-danger">Cine</span></span>
                    </div>
                    <p class="text-light-emphasis small mb-2" style="max-width: 440px;">
                        A responsive movie catalog and rating platform developed with PHP, MySQL, Bootstrap 5, and JavaScript. 
                    </p>
                    <p class="text-light small mb-0">
                        <i class="bi bi-info-circle me-1 text-warning"></i> Notice: Full movie streaming is <strong class="text-warning">Not Playable Yet</strong>. Catalog &amp; ratings only.
                    </p>
                </div>

                <!-- Tech Badges & Copyright -->
                <div class="col-12 col-md-6 text-md-end">
                    <div class="d-flex flex-wrap gap-2 justify-content-md-end mb-3">
                        <span class="badge bg-dark border border-secondary text-white small px-2 py-1">Bootstrap 5.3</span>
                        <span class="badge bg-dark border border-secondary text-white small px-2 py-1">PHP 8 &amp; PDO</span>
                        <span class="badge bg-dark border border-secondary text-white small px-2 py-1">MySQL / MariaDB</span>
                        <span class="badge bg-dark border border-secondary text-white small px-2 py-1">AJAX &amp; JSON</span>
                    </div>
                    <p class="text-light-emphasis small mb-0">
                        &copy; <?= $currentYear ?> <strong>CedCine</strong>. Built for college BSIT student project.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS (Includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- CedCine Main Application JS -->
    <script src="<?= $basePath ?>assets/js/app.js?v=<?= filemtime(__DIR__ . '/../assets/js/app.js') ?>"></script>
    <?php if (isset($extraJs) && is_array($extraJs)): ?>
        <?php foreach ($extraJs as $jsFile): ?>
            <script src="<?= $basePath . htmlspecialchars($jsFile) ?>?v=<?= time() ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
