<?php
// Dashboard top header + opening layout
$user = current_user();
?>
<div class="main">
    <header class="topbar">
        <button class="btn btn-sm btn-primary" id="menuToggle" style="display:none" onclick="document.getElementById('sidebar').classList.toggle('open')">&#9776;</button>
        <div class="search"><input type="text" placeholder="Search portal..."></div>
        <div class="profile">
            <span class="badge badge-info"><?= role_label($user['role']) ?></span>
            <strong><?= e($user['full_name']) ?></strong>
            <a href="<?= base_url() ?>/modules/settings.php" title="Profile">&#9881;</a>
        </div>
    </header>
    <div class="content">
