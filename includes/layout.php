<?php
// Full HTML document wrapper for dashboard/module pages
// Call after setting $active (current sidebar key) and $page_title
require_once __DIR__ . '/auth.php';
require_login();
require_once __DIR__ . '/functions.php';

$page_title = $page_title ?? 'AIML AcademicHub';
$active = $active ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($page_title) ?> - AIML AcademicHub</title>
<<<<<<< HEAD
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/style.css">
</head>
<body>
=======
<<<<<<< HEAD
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/style.css">
</head>
<body>
=======
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/style.css">
</head>
<body>
<!-- ===================== SKELETON LOADER (Dashboard) ===================== -->
<div class="page-loader" id="pageLoader">
    <div class="skeleton-layout">
        <!-- ─── Sidebar skeleton ─── -->
        <aside class="skeleton-sidebar">
            <div class="sk-sidebar-brand">
                <div class="sk" style="width:40px;height:40px;border-radius:8px;"></div>
                <div style="flex:1;min-width:0">
                    <div class="sk" style="width:130px;height:15px;border-radius:4px;"></div>
                    <div class="sk" style="width:80px;height:10px;border-radius:4px;margin-top:6px;"></div>
                </div>
            </div>
            <div class="sk-sidebar-item" style="width:82%;"></div>
            <div class="sk-sidebar-item" style="width:68%;"></div>
            <div class="sk-sidebar-item" style="width:74%;"></div>
            <div class="sk-sidebar-item" style="width:58%;"></div>
            <div class="sk-sidebar-item" style="width:78%;"></div>
            <div class="sk-sidebar-item" style="width:62%;"></div>
            <div class="sk-sidebar-divider"></div>
            <div class="sk-sidebar-item" style="width:70%;"></div>
            <div class="sk-sidebar-item" style="width:55%;"></div>
            <div class="sk-sidebar-item" style="width:65%;"></div>
            <div class="sk-sidebar-item" style="width:48%;"></div>
            <div class="sk-sidebar-item" style="width:72%;"></div>
        </aside>

        <!-- ─── Main area skeleton ─── -->
        <div class="skeleton-main">
            <!-- Topbar -->
            <div class="skeleton-topbar">
                <div class="sk" style="width:220px;height:15px;border-radius:4px;"></div>
                <div style="display:flex;align-items:center;gap:14px;">
                    <div class="sk" style="width:56px;height:15px;border-radius:4px;"></div>
                    <div class="sk" style="width:90px;height:15px;border-radius:4px;"></div>
                    <div class="sk" style="width:34px;height:34px;border-radius:50%;"></div>
                </div>
            </div>

            <!-- Content -->
            <div class="skeleton-content">
                <!-- Page title -->
                <div class="sk sk-title"></div>

                <!-- Stat cards -->
                <div class="sk-stats">
                    <div class="sk sk-stat"></div>
                    <div class="sk sk-stat"></div>
                    <div class="sk sk-stat"></div>
                    <div class="sk sk-stat"></div>
                </div>

                <!-- Card with table rows -->
                <div class="sk-card">
                    <div class="sk sk-card-head"></div>
                    <div class="sk sk-row" style="width:100%;"></div>
                    <div class="sk sk-row" style="width:94%;"></div>
                    <div class="sk sk-row" style="width:88%;"></div>
                    <div class="sk sk-row" style="width:96%;"></div>
                    <div class="sk sk-row" style="width:82%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
>>>>>>> 45e2ed514fc6a290a4cb5ac7331c5ed0f6b52cd7
>>>>>>> b6df4ad04a8aa0ab8bde74ecb2f0ff952f1c32f4
<?php require_once __DIR__ . '/sidebar.php'; ?>
<?php require_once __DIR__ . '/header.php'; ?>
