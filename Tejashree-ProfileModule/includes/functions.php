<?php
// Common helper functions
require_once __DIR__ . '/db.php';

function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

// CSRF protection
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return true;
    }
    $token = $_POST['csrf_token'] ?? '';
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        die('Invalid or missing CSRF token.');
    }
    return true;
}

// Validate a value against an allowed enum; return $default if invalid.
function enum_val($value, $allowed, $default = '') {
    return in_array($value, $allowed, true) ? $value : $default;
}

// Validate a YYYY-MM-DD date string. Returns the value or empty string if invalid.
function valid_date($value) {
    if (!is_string($value) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
        return '';
    }
    $dt = DateTime::createFromFormat('Y-m-d', $value);
    return ($dt && $dt->format('Y-m-d') === $value) ? $value : '';
}

function redirect($path) {
    header('Location: ' . base_url() . '/' . ltrim($path, '/'));
    exit;
}

function flash($msg, $type = 'success') {
    $_SESSION['flash'] = ['msg' => $msg, 'type' => $type];
}

function show_flash() {
    // Flash messages are rendered as JS toasts in footer.php; nothing to echo here.
}

function get_stats($table) {
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT(*) AS c FROM $table");
    return $stmt->fetch()['c'];
}

function role_label($role) {
    $map = [
        'admin' => 'Administrator',
        'hod' => 'Head of Department',
        'faculty' => 'Faculty',
        'student' => 'Student',
        'tpo' => 'Training & Placement Officer'
    ];
    return $map[$role] ?? $role;
}

// Safely execute an INSERT/UPDATE/DELETE. On constraint errors, sets a flash
// message and returns false instead of throwing a fatal error.
function safe_exec($sql, $params = []) {
    global $pdo;
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return true;
    } catch (PDOException $e) {
        $msg = $e->getMessage();
        if (strpos($msg, '1062') !== false) {
            flash('Record already exists (duplicate entry). Please use a unique value.', 'danger');
        } elseif (strpos($msg, '1452') !== false) {
            flash('Save failed: the selected reference (student/faculty/company) does not exist. It may have been deleted.', 'danger');
        } elseif (strpos($msg, '1406') !== false) {
            flash('Save failed: value too long for a field.', 'danger');
        } else {
            flash('Database error: ' . $e->getMessage(), 'danger');
        }
        return false;
    }
}
