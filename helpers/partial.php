<?php
// helpers/partial.php - Partial View Rendering Helper

if (!function_exists('render_partial')) {
    /**
     * Renders a partial template and passes data variables to it.
     *
     * @param string $partialName Name of the partial file inside partials/ without .php
     * @param array $data Associative array of variables to extract into scope
     * @param bool $returnString If true, returns the rendered HTML as a string instead of echoing
     * @return string|void
     */
    function render_partial(string $partialName, array $data = [], bool $returnString = false) {
        $partialPath = __DIR__ . '/../partials/' . $partialName . '.php';

        if (!file_exists($partialPath)) {
            $error = "<!-- Partial [{$partialName}] not found at {$partialPath} -->";
            if ($returnString) return $error;
            echo $error;
            return;
        }

        if ($returnString) {
            ob_start();
            extract($data, EXTR_SKIP);
            require $partialPath;
            return ob_get_clean();
        }

        extract($data, EXTR_SKIP);
        require $partialPath;
    }
}

