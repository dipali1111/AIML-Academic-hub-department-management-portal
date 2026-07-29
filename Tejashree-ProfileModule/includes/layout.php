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
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/style.css">
</head>
<body>
<?php require_once __DIR__ . '/sidebar.php'; ?>
<?php require_once __DIR__ . '/header.php'; ?>
