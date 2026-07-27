<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if (login_user($username, $password)) {
        redirect('dashboard.php');
    } else {
        $error = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In — AIML AcademicHub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url() ?>/assets/css/style.css">
</head>
<body class="login-page">

<!-- ========== VIDEO BACKGROUND ========== -->
<div class="login-video-bg">
    <video autoplay loop muted playsinline src="<?= base_url() ?>/assets/videos/login-bg.mp4"></video>
    <div class="login-overlay"></div>
</div>

<!-- ========== LOGIN CARD ========== -->
<div class="login-wrap">
    <div class="login-card">

        <h2 class="login-title">Welcome back</h2>
        <p class="login-sub">Sign in to your departmental dashboard</p>

        <?php if ($error): ?>
        <div class="alert-msg alert-error">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
            <?= e($error) ?>
        </div>
        <?php endif; ?>

        <form method="post" class="login-form">
            <?= csrf_field() ?>

            <div class="input-group">
                <label for="username">Username</label>
                <div class="input-wrap">
                    <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                    <input type="text" id="username" name="username" required placeholder="e.g. admin" autocomplete="username">
                </div>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <input type="password" id="password" name="password" required placeholder="password123" autocomplete="current-password">
                    <button type="button" class="pw-toggle" id="pwToggle" aria-label="Toggle password visibility">
                        <svg class="eye-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        <svg class="eye-icon eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="20" height="20"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><path d="M1 1l22 22"/></svg>
                    </button>
                </div>
            </div>

            <button class="btn-login" type="submit">
                Sign In
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="18" height="18"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            </button>
        </form>

        <div class="login-demo">
            <span class="demo-label">Demo Credentials</span>
            <div class="demo-grid">
                <span><strong>admin</strong> / password123</span>
                <span><strong>dipalishende</strong> / dipalishende123</span>
                <span><strong>faculty1</strong> / password123</span>
                <span><strong>student1</strong> / password123</span>
                <span><strong>tpo</strong> / password123</span>
            </div>
        </div>

        <div class="login-footer">
            <a href="<?= base_url() ?>/index.php">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="16" height="16"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Back to Home
            </a>
        </div>
    </div>
</div>

<script>
(function() {
    var toggle = document.getElementById('pwToggle');
    var input = document.getElementById('password');
    if (toggle && input) {
        toggle.addEventListener('click', function() {
            var show = input.type === 'password';
            input.type = show ? 'text' : 'password';
            toggle.classList.toggle('showing', show);
        });
    }
})();
</script>

</body>
</html>
