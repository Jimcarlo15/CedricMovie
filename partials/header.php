<?php
// partials/header.php
if (!isset($pageTitle)) {
    $pageTitle = 'CedCine - Movie Catalog & Rating Platform';
}
$basePath = isset($isSubdir) && $isSubdir ? '../' : '';
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CedCine Main Stylesheet -->
    <link rel="stylesheet" href="<?= $basePath ?>assets/css/style.css?v=<?= filemtime(__DIR__ . '/../assets/css/style.css') ?>">
    <?php if (isset($extraCss) && is_array($extraCss)): ?>
        <?php foreach ($extraCss as $cssFile): ?>
            <link rel="stylesheet" href="<?= $basePath . htmlspecialchars($cssFile) ?>?v=<?= time() ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
<?php
// Display flash messages if any
if (function_exists('has_flash')) {
    foreach (['success', 'danger', 'warning', 'info'] as $type) {
        if (has_flash($type)) {
            $msg = get_flash($type);
            $icon = $type === 'success' ? 'check-circle-fill' : ($type === 'danger' ? 'exclamation-triangle-fill' : 'info-circle-fill');
            echo '<div class="alert alert-' . htmlspecialchars($type) . ' alert-dismissible fade show m-3 shadow" role="alert" style="max-width: 1200px; margin: 1rem auto !important; z-index: 1050;">
                <i class="bi bi-' . $icon . ' me-2"></i>' . htmlspecialchars($msg) . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
        }
    }
}
?>
