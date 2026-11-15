<?php
require_once 'config.php';
require_login();
$page_title = $page_title ?? 'Dashboard';
$current = basename($_SERVER['PHP_SELF']);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= e($page_title) ?> | PlaceTrack</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="app-shell">
        <aside class="sidebar"><a class="brand" href="dashboard.php"><span
                    class="brand-mark small">P</span><span>PlaceTrack<small>Campus placements</small></span></a>
            <nav>
                <a class="<?= $current === 'dashboard.php' ? 'active' : '' ?>" href="dashboard.php"><span>▦</span> Dashboard</a>
                <a class="<?= $current === 'students.php' ? 'active' : '' ?>" href="students.php"><span>♙</span> Students</a>
                <a class="<?= $current === 'companies.php' ? 'active' : '' ?>" href="companies.php"><span>▣</span> Companies</a>
                <a class="<?= $current === 'jobs.php' ? 'active' : '' ?>" href="jobs.php"><span>▤</span> Job openings</a>
                <a class="<?= $current === 'applications.php' ? 'active' : '' ?>" href="applications.php"><span>☷</span>
                    Applications</a>
            </nav>
            <div class="sidebar-bottom">
                <div class="avatar"><?= e(strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1))) ?></div>
                <div><strong><?= e($_SESSION['admin_name'] ?? 'Admin') ?></strong><small>Administrator</small></div><a
                    class="logout" href="logout.php" title="Log out">↗</a>
            </div>
        </aside>
        <main class="main-area">
            <header class="topbar">
                <div><span class="eyebrow">PLACEMENT OFFICE</span>
                    <h1><?= e($page_title) ?></h1>
                </div>
                <div class="topbar-right"><span class="status-dot"></span> System online <span
                        class="top-date"><?= date('d M Y') ?></span></div>
            </header>
            <section class="content">
                <?php show_flash(); ?>