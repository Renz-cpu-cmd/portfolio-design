<?php
$pageTitle = $pageTitle ?? 'Renz Alvarez';
$activePage = $activePage ?? '';
$loadMainScript = $loadMainScript ?? false;
$extraHead = $extraHead ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle, ENT_QUOTES, 'UTF-8') ?></title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/portfolio/assets/css/base.css">
    <link rel="stylesheet" href="/portfolio/assets/css/modal.css">
    <?php
    $cssMap = [
        'home' => 'home.css',
        'about' => 'about.css',
        'certification' => 'certification.css',
        'project' => 'project.css',
        'contact' => 'contact.css'
    ];
    if (isset($cssMap[$activePage])) {
        echo '<link rel="stylesheet" href="/portfolio/assets/css/' . $cssMap[$activePage] . '">';
    }

    // Check if we are in admin or if it's a page that needs CRUD styles
    $currentPath = $_SERVER['PHP_SELF'];
    if (strpos($currentPath, '/admin/') !== false || in_array($activePage, ['project', 'certification'])) {
        echo '<link rel="stylesheet" href="/portfolio/assets/css/crud.css">';
    }
    ?>
    <script src="/portfolio/assets/js/background.js" defer></script>
    <script src="/portfolio/assets/js/modal.js" defer></script>
    <?php if ($loadMainScript): ?>
        <script src="/portfolio/assets/js/script.js" defer></script>
    <?php endif; ?>
    <?= $extraHead ?>
</head>

<body>
    <div class="live-wallpaper" aria-hidden="true">
        <div class="liquid-layer">
            <span class="liquid-blob blob-a"></span>
            <span class="liquid-blob blob-b"></span>
            <span class="liquid-blob blob-c"></span>
            <span class="liquid-blob blob-d"></span>
        </div>
        <div class="glass-wave wave-a"></div>
        <div class="glass-wave wave-b"></div>
        <div class="noise-overlay"></div>
    </div>

    <nav>
        <div class="nav-box">
            <img id="logo" src="/portfolio/image/a3dd212965b4d0a0d9abc1004b524c69.jpg" alt="Logo">
            <ul>
                <li><a href="/portfolio/pages/index.php" class="<?= $activePage === 'home' ? 'active' : '' ?>">Home</a></li>
                <li><a href="/portfolio/pages/about.php" class="<?= $activePage === 'about' ? 'active' : '' ?>">About</a></li>
                <li><a href="/portfolio/pages/certification.php" class="<?= $activePage === 'certification' ? 'active' : '' ?>">Certification</a></li>
                <li><a href="/portfolio/pages/project.php" class="<?= $activePage === 'project' ? 'active' : '' ?>">Project</a></li>
                <li><a href="/portfolio/pages/contact.php" class="<?= $activePage === 'contact' ? 'active' : '' ?>">Contact</a></li>
            </ul>
            <span class="nav-swipe-indicator" aria-hidden="true"></span>
        </div>
    </nav>
>
