<?php
// partials/footer.php
?>
    <footer class="site-footer">
        <div class="footer-container">
            <div>
                <p class="footer-copy">
                    &copy; <?= date('Y') ?> <strong>CedricMovie</strong> &mdash; Movie catalog, ratings, and synopsis platform.
                </p>
                <p style="font-size: 0.8rem; color: var(--text-dim); margin-top: 0.25rem;">
                    Notice: Videos are not playable yet. Catalog only.
                </p>
            </div>

            <div class="footer-badges">
                <span class="tech-tag">PHP 8</span>
                <span class="tech-tag">SQLite 3</span>
                <span class="tech-tag">Modular Partials</span>
                <span class="tech-tag">Responsive UI</span>
            </div>
        </div>
    </footer>

    <!-- App Scripts -->
    <script src="assets/js/app.js"></script>
</body>
</html>

